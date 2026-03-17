<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Finance\RecurringPayment;
use App\Services\PaymentGatewayManager;
use Illuminate\Support\Facades\Log;

class ProcessRecurringPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 300, 900]; // 1 min, 5 min, 15 min

    public function __construct(
        public RecurringPayment $recurringPayment
    ) {
    }

    public function handle(PaymentGatewayManager $gatewayManager): void
    {
        try {
            Log::info("Processing recurring payment {$this->recurringPayment->id}");

            $gateway = $this->recurringPayment->paymentToken->paymentGateway;
            
            $paymentData = [
                'order_id' => 'RECUR-' . time() . '-' . $this->recurringPayment->id,
                'amount' => $this->recurringPayment->amount,
                'payment_type' => $this->recurringPayment->paymentToken->payment_method,
                'token' => $this->recurringPayment->paymentToken->gateway_token,
                'customer' => [
                    'first_name' => $this->recurringPayment->user->name,
                    'email' => $this->recurringPayment->user->email,
                ],
                'description' => $this->recurringPayment->description,
            ];

            $gatewayResponse = $gatewayManager->createPayment($gateway->name, $paymentData);

            // Update recurring payment
            $this->recurringPayment->update([
                'last_charge_date' => now(),
                'next_charge_date' => $this->recurringPayment->calculateNextChargeDate(),
                'total_charges' => $this->recurringPayment->total_charges + 1,
                'last_gateway_response' => $gatewayResponse,
            ]);

            // Check if should be completed
            if ($this->recurringPayment->hasReachedMaxCharges() || 
                ($this->recurringPayment->end_date && $this->recurringPayment->end_date->isPast())) {
                $this->recurringPayment->update(['status' => 'completed']);
            }

            // Send notification
            $this->sendNotification($gatewayResponse, 'success');

            Log::info("Recurring payment {$this->recurringPayment->id} processed successfully");

        } catch (\Exception $e) {
            Log::error("Failed to process recurring payment {$this->recurringPayment->id}: " . $e->getMessage());
            
            // Update retry count
            $this->recurringPayment->increment('retry_count');
            
            // Send failure notification
            $this->sendNotification(['error' => $e->getMessage()], 'failed');
            
            // Mark as failed if max retries reached
            if ($this->recurringPayment->retry_count >= 3) {
                $this->recurringPayment->update(['status' => 'failed']);
            }
            
            throw $e;
        }
    }

    protected function sendNotification(array $response, string $status): void
    {
        // Implementation for email/SMS notification
        Log::info("Recurring payment notification sent for payment {$this->recurringPayment->id} with status {$status}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("Recurring payment job failed for payment {$this->recurringPayment->id}: " . $exception->getMessage());
        
        $this->recurringPayment->increment('retry_count');
        
        if ($this->recurringPayment->retry_count >= 3) {
            $this->recurringPayment->update(['status' => 'failed']);
        }
    }
}
