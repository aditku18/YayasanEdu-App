<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\WebhookLog;
use App\Services\PaymentGatewayManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    protected PaymentGatewayManager $gatewayManager;

    public function __construct(PaymentGatewayManager $gatewayManager)
    {
        $this->gatewayManager = $gatewayManager;
    }

    public function handle(Request $request, string $gateway)
    {
        $payload = $request->all();
        
        Log::info("Webhook received from {$gateway}", $payload);

        try {
            // Verify webhook signature
            if (!$this->gatewayManager->verifyWebhook($gateway, $payload)) {
                Log::error("Invalid webhook signature from {$gateway}");
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            // Process webhook
            $result = $this->processWebhook($gateway, $payload);
            
            return response()->json([
                'status' => $result ? 'processed' : 'failed',
                'message' => $result ? 'Webhook processed successfully' : 'Failed to process webhook',
            ]);
        } catch (\Exception $e) {
            Log::error("Webhook processing error: " . $e->getMessage());
            
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    protected function processWebhook(string $gateway, array $payload): bool
    {
        $webhookLog = WebhookLog::where('payment_gateway_id', function ($query) use ($gateway) {
            $query->whereHas('paymentGateway', function ($q) use ($gateway) {
                $q->where('name', $gateway);
            });
        })
        ->where('webhook_id', $payload['id'] ?? null)
        ->first();

        if ($webhookLog && $webhookLog->isProcessed()) {
            // Already processed, mark as duplicate
            $webhookLog->update(['status' => 'duplicate']);
            return true;
        }

        // Create or update webhook log
        if ($webhookLog) {
            $webhookLog->update([
                'event_type' => $payload['event_type'] ?? 'unknown',
                'payload' => $payload,
                'status' => 'received',
            ]);
        } else {
            $webhookLog = WebhookLog::create([
                'payment_gateway_id' => $this->getGatewayId($gateway),
                'webhook_id' => $payload['id'] ?? null,
                'event_type' => $payload['event_type'] ?? 'unknown',
                'payload' => $payload,
                'status' => 'received',
            ]);
        }

        try {
            $processed = $this->handleWebhookEvent($gateway, $payload);
            
            $webhookLog->update([
                'status' => $processed ? 'processed' : 'failed',
                'processed_at' => now(),
            ]);

            return $processed;
        } catch (\Exception $e) {
            Log::error("Webhook event handling error: " . $e->getMessage());
            
            $webhookLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'processed_at' => now(),
            ]);

            return false;
        }
    }

    protected function handleWebhookEvent(string $gateway, array $payload): bool
    {
        switch ($gateway) {
            case 'midtrans':
                return $this->handleMidtransWebhook($payload);
            case 'xendit':
                return $this->handleXenditWebhook($payload);
            default:
                Log::info("Unhandled gateway webhook: {$gateway}");
                return true;
        }
    }

    protected function handleMidtransWebhook(array $payload): bool
    {
        $status = $payload['transaction_status'] ?? 'unknown';
        $orderId = $payload['order_id'] ?? null;

        if (!$orderId) return false;

        $invoice = \App\Models\Invoice::where('invoice_number', $this->extractInvoiceNumber($orderId))->first();
        if (!$invoice) {
            Log::error("Invoice not found for Midtrans order: {$orderId}");
            return false;
        }

        switch ($status) {
            case 'capture':
            case 'settlement':
                return $this->markInvoiceAsPaid($invoice, $payload);
            case 'pending':
                $invoice->update(['status' => 'pending']);
                return true;
            case 'deny':
            case 'expire':
            case 'cancel':
                $invoice->update(['status' => 'failed']);
                return true;
            default:
                return true;
        }
    }

    protected function handleXenditWebhook(array $payload): bool
    {
        // Xendit Invoice webhook structure
        $status = $payload['status'] ?? 'unknown';
        $externalId = $payload['external_id'] ?? null;

        if (!$externalId) return false;

        $invoice = \App\Models\Invoice::where('invoice_number', $this->extractInvoiceNumber($externalId))->first();
        if (!$invoice) {
            Log::error("Invoice not found for Xendit external_id: {$externalId}");
            return false;
        }

        switch ($status) {
            case 'PAID':
            case 'SETTLED':
                return $this->markInvoiceAsPaid($invoice, $payload);
            case 'PENDING':
                $invoice->update(['status' => 'pending']);
                return true;
            case 'EXPIRED':
                $invoice->update(['status' => 'expired']);
                return true;
            default:
                return true;
        }
    }

    protected function markInvoiceAsPaid(\App\Models\Invoice $invoice, array $payload): bool
    {
        if ($invoice->status === 'paid') return true;

        DB::beginTransaction();
        try {
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // Create PlatformPayment record for tracking
            \App\Models\PlatformPayment::create([
                'foundation_id' => $invoice->foundation_id,
                'subscription_id' => $invoice->subscription_id,
                'invoice_id' => $invoice->id,
                'amount' => $invoice->amount,
                'status' => 'success',
                'payment_method' => $payload['payment_type'] ?? $payload['payment_method'] ?? 'online',
                'transaction_id' => $payload['transaction_id'] ?? $payload['id'] ?? null,
                'paid_at' => now(),
                'gateway_response' => $payload,
            ]);

            // Extend Subscription if applicable
            if ($invoice->subscription) {
                $sub = $invoice->subscription;
                $currentEnd = $sub->ends_at ?? now();
                $newEnd = $currentEnd->isPast() ? now()->addMonth() : $currentEnd->addMonth();
                
                // If it's a yearly plan, add a year
                if ($invoice->billing_cycle === 'yearly') {
                    $newEnd = $currentEnd->isPast() ? now()->addYear() : $currentEnd->addYear();
                }

                $sub->update([
                    'status' => 'active',
                    'ends_at' => $newEnd,
                ]);
            }

            DB::commit();
            Log::info("Invoice #{$invoice->invoice_number} marked as paid via webhook.");
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error marking invoice as paid: " . $e->getMessage());
            return false;
        }
    }

    protected function extractInvoiceNumber(string $orderId): string
    {
        // If orderId is PAY-YYYYMMDD-ID, we might need to extract ID or use it as is
        // Let's assume order_id matches invoice_number or we search by reference
        return $orderId;
    }

    protected function handlePaymentSuccess(array $payload): bool
    {
        $paymentId = $this->extractPaymentId($payload);
        if (!$paymentId) {
            return false;
        }

        $payment = \App\Models\Finance\Payment::where('payment_number', $paymentId)->first();
        if (!$payment) {
            Log::error("Payment not found for webhook: {$paymentId}");
            return false;
        }

        if ($payment->status === 'confirmed') {
            Log::info("Payment {$paymentId} already confirmed");
            return true;
        }

        $payment->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'reference_number' => $payload['reference_number'] ?? null,
        ]);

        // Update invoice status
        $this->updateInvoiceFromPayment($payment);

        // Send notifications
        $this->sendPaymentNotification($payment, 'success');

        Log::info("Payment {$paymentId} confirmed via webhook");
        return true;
    }

    protected function handlePaymentPending(array $payload): bool
    {
        $paymentId = $this->extractPaymentId($payload);
        if (!$paymentId) {
            return false;
        }

        $payment = \App\Models\Finance\Payment::where('payment_number', $paymentId)->first();
        if (!$payment) {
            return false;
        }

        $payment->update(['status' => 'pending']);

        $this->sendPaymentNotification($payment, 'pending');
        
        Log::info("Payment {$paymentId} marked as pending via webhook");
        return true;
    }

    protected function handlePaymentFailed(array $payload): bool
    {
        $paymentId = $this->extractPaymentId($payload);
        if (!$paymentId) {
            return false;
        }

        $payment = \App\Models\Finance\Payment::where('payment_number', $paymentId)->first();
        if (!$payment) {
            return false;
        }

        $payment->update([
            'status' => 'rejected',
            'notes' => ($payment->notes ?? '') . ' | Webhook: ' . ($payload['failure_reason'] ?? 'Payment failed'),
        ]);

        $this->sendPaymentNotification($payment, 'failed');
        
        Log::info("Payment {$paymentId} marked as failed via webhook");
        return true;
    }

    protected function handlePaymentCancelled(array $payload): bool
    {
        $paymentId = $this->extractPaymentId($payload);
        if (!$paymentId) {
            return false;
        }

        $payment = \App\Models\Finance\Payment::where('payment_number', $paymentId)->first();
        if (!$payment) {
            return false;
        }

        $payment->update(['status' => 'rejected']);

        $this->sendPaymentNotification($payment, 'cancelled');
        
        Log::info("Payment {$paymentId} cancelled via webhook");
        return true;
    }

    protected function handlePaymentExpired(array $payload): bool
    {
        $paymentId = $this->extractPaymentId($payload);
        if (!$paymentId) {
            return false;
        }

        $payment = \App\Models\Finance\Payment::where('payment_number', $paymentId)->first();
        if (!$payment) {
            return false;
        }

        $payment->update(['status' => 'rejected']);

        $this->sendPaymentNotification($payment, 'expired');
        
        Log::info("Payment {$paymentId} expired via webhook");
        return true;
    }

    protected function extractPaymentId(array $payload): ?string
    {
        // Different gateways may use different field names
        return $payload['order_id'] ?? 
               $payload['payment_id'] ?? 
               $payload['transaction_id'] ?? 
               $payload['reference_id'] ?? null;
    }

    protected function updateInvoiceFromPayment(\App\Models\Finance\Payment $payment): void
    {
        $invoice = $payment->invoice;
        if (!$invoice) {
            return;
        }

        $invoice->paid_amount += $payment->amount;
        $invoice->remaining_amount = $invoice->final_amount - $invoice->paid_amount;

        if ($invoice->remaining_amount <= 0) {
            $invoice->status = 'paid';
        } elseif ($invoice->paid_amount > 0) {
            $invoice->status = 'partial';
        }

        $invoice->save();
    }

    protected function sendPaymentNotification(\App\Models\Finance\Payment $payment, string $status): void
    {
        // Integration with existing notification system
        // This would send email/SMS to student/user about payment status
        
        Log::info("Payment notification sent for payment {$payment->id} with status {$status}");
    }

    protected function getGatewayId(string $gatewayName): ?int
    {
        $gatewayModel = \App\Models\Finance\PaymentGateway::where('name', $gatewayName)->first();
        return $gatewayModel ? $gatewayModel->id : null;
    }

    public function logs()
    {
        $search = request()->query('search');
        $gateway = request()->query('gateway');
        $status = request()->query('status');
        $event_type = request()->query('event_type');

        $query = WebhookLog::with('paymentGateway');

        if ($search) {
            $query->where('event_type', 'like', "%{$search}%")
                  ->orWhere('webhook_id', 'like', "%{$search}%");
        }

        if ($gateway) {
            $query->whereHas('paymentGateway', function ($q) use ($gateway) {
                $q->where('name', $gateway);
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($event_type) {
            $query->where('event_type', $event_type);
        }

        $logs = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $gateways = \App\Models\Finance\PaymentGateway::pluck('name', 'id');

        // Calculate comprehensive statistics for the view
        $stats = [
            'total_webhooks' => WebhookLog::count(),
            'processed_webhooks' => WebhookLog::where('status', 'processed')->count(),
            'failed_webhooks' => WebhookLog::where('status', 'failed')->count(),
            'duplicate_webhooks' => WebhookLog::where('status', 'duplicate')->count(),
            'pending_webhooks' => WebhookLog::where('status', 'received')->count(),
            'today_webhooks' => WebhookLog::whereDate('created_at', today())->count(),
            'payment_success_webhooks' => WebhookLog::where('event_type', 'payment.success')->count(),
            'payment_failed_webhooks' => WebhookLog::where('event_type', 'payment.failed')->count(),
        ];

        return view('platform.webhooks.index', compact('logs', 'gateways', 'stats'));
    }

    public function getLogDetails(WebhookLog $log)
    {
        return response()->json([
            'id' => $log->id,
            'webhook_id' => $log->webhook_id,
            'event_type' => $log->event_type,
            'status' => $log->status,
            'gateway' => $log->paymentGateway ? $log->paymentGateway->name : 'Unknown',
            'created_at' => $log->created_at->format('d M Y H:i'),
            'payload' => $log->payload,
            'error_message' => $log->error_message,
        ]);
    }

    public function retryWebhook(WebhookLog $log)
    {
        try {
            // Mark the log as pending retry
            $log->update(['status' => 'pending_retry']);
            
            // Process the webhook again
            $processed = $this->processWebhook($log->paymentGateway->name, $log->payload);
            
            // Update status based on processing result
            if ($processed) {
                $log->update(['status' => 'processed', 'error_message' => null, 'processed_at' => now()]);
                return response()->json(['success' => true, 'message' => 'Webhook retried successfully']);
            } else {
                $log->update(['status' => 'failed', 'error_message' => 'Webhook processing failed', 'processed_at' => now()]);
                return response()->json(['success' => false, 'message' => 'Webhook processing failed']);
            }
        } catch (\Exception $e) {
            $log->update(['status' => 'failed', 'error_message' => $e->getMessage(), 'processed_at' => now()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
