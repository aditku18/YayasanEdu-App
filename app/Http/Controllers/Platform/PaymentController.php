<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\PaymentSplit;
use App\Models\Finance\PaymentToken;
use App\Models\Finance\RecurringPayment;
use App\Models\PlatformPayment;
use App\Models\Invoice;
use App\Services\PaymentGatewayManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PaymentGatewayManager $gatewayManager;

    public function __construct(PaymentGatewayManager $gatewayManager)
    {
        $this->gatewayManager = $gatewayManager;
    }

    public function index(Request $request)
    {
        $payments = PlatformPayment::with(['foundation', 'subscription'])
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('transaction_id', 'like', "%{$search}%")
                        ->orWhere('payment_method', 'like', "%{$search}%")
                        ->orWhereHas('foundation', function ($fq) use ($search) {
                            $fq->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->date_from, function ($query, $date) {
                return $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                return $query->whereDate('created_at', '<=', $date);
            })
            ->latest()
            ->paginate(20);

        // Calculate statistics for the view
        $stats = [
            'total_payments' => PlatformPayment::count(),
            'pending_payments' => PlatformPayment::where('status', 'pending')->count(),
            'processing_payments' => PlatformPayment::where('status', 'processing')->count(),
            'completed_payments' => PlatformPayment::where('status', 'success')->count(),
            'failed_payments' => PlatformPayment::where('status', 'failed')->count(),
            'total_amount' => PlatformPayment::where('status', 'success')->sum('amount'),
            'today_amount' => PlatformPayment::where('status', 'success')->whereDate('created_at', today())->sum('amount'),
            'this_month_amount' => PlatformPayment::where('status', 'success')->whereMonth('created_at', now()->month)->sum('amount'),
            'pending_amount' => PlatformPayment::where('status', 'pending')->sum('amount'),
        ];

        return view('platform.payments.index', compact('payments', 'stats'));
    }

    public function create()
    {
        // For platform level, we create payment splits or manage recurring payments
        $gateways = $this->gatewayManager->getActiveGateways();
        $supportedMethods = $this->gatewayManager->getSupportedMethods();
        $foundations = \App\Models\Foundation::pluck('name', 'id');

        return view('admin.payments.create', compact('gateways', 'supportedMethods', 'foundations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'foundation_id' => 'required|exists:foundations,id',
            'invoice_id' => 'nullable|string|max:50',
            'payment_gateway_id' => 'required|exists:payment_gateways,id',
            'amount' => 'required|numeric|min:1000',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $gateway = $this->gatewayManager->getGatewayById($validated['payment_gateway_id']);

            // For manual payment recording, don't call gateway API
            // Just record the payment as completed
            $gatewayResponse = [
                'status' => 'manual',
                'type' => 'manual_payment_record',
                'recorded_at' => now()->toISOString(),
            ];

            // Find invoice if invoice_id is provided
            $invoice = null;
            if (!empty($validated['invoice_id'])) {
                $invoice = Invoice::where('invoice_number', $validated['invoice_id'])->first();
            }

            $payment = PaymentSplit::create([
                'foundation_id' => $validated['foundation_id'],
                'invoice_id' => $validated['invoice_id'],
                'payment_gateway_id' => $gateway->id,
                'amount' => $validated['amount'],
                'status' => 'completed', // Manual payment is already completed
                'gateway_response' => $gatewayResponse,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update invoice status if found
            if ($invoice) {
                $invoice->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('platform.payments.index')
                ->with('success', 'Payment split berhasil dibuat.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment split creation error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuat payment split: ' . $e->getMessage());
        }
    }

    protected function mapGatewayStatus(array $gatewayResponse): string
    {
        // Map gateway response status to our internal status
        if (isset($gatewayResponse['status_code'])) {
            switch ($gatewayResponse['status_code']) {
                case '200':
                    return 'completed';
                case '201':
                    return 'pending';
                case '202':
                    return 'pending';
                default:
                    return 'failed';
            }
        }

        if (isset($gatewayResponse['status'])) {
            switch (strtolower($gatewayResponse['status'])) {
                case 'success':
                case 'completed':
                case 'paid':
                    return 'completed';
                case 'pending':
                case 'processing':
                    return 'pending';
                case 'failed':
                case 'cancelled':
                case 'expired':
                    return 'failed';
                default:
                    return 'pending';
            }
        }

        return 'pending';
    }

    public function show($paymentId)
    {
        $paymentSplit = PaymentSplit::with(['paymentGateway', 'foundation', 'invoice'])->findOrFail($paymentId);

        return view('platform.payments.show', compact('paymentSplit'));
    }

    public function confirm(Request $request, $paymentId)
    {
        $paymentSplit = PaymentSplit::findOrFail($paymentId);

        if ($paymentSplit->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Hanya payment split pending yang bisa dikonfirmasi.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $paymentSplit->update([
            'status' => 'completed',
            'notes' => $validated['notes'],
        ]);

        // Update associated invoice
        if ($paymentSplit->invoice_id) {
            Invoice::where('id', $paymentSplit->invoice_id)->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        return redirect()->route('platform.payments.show', $paymentSplit->id)
            ->with('success', 'Payment split berhasil dikonfirmasi.');
    }

    public function reject(Request $request, $paymentId)
    {
        $paymentSplit = PaymentSplit::findOrFail($paymentId);
        if ($paymentSplit->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Hanya payment split pending yang bisa ditolak.');
        }

        $validated = $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        $paymentSplit->update([
            'status' => 'failed',
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('platform.payments.show', $paymentSplit->id)
            ->with('success', 'Payment split berhasil ditolak.');
    }

    /**
     * Confirm platform payment (from PlatformPayment model)
     */
    public function confirmPlatformPayment(Request $request, PlatformPayment $payment)
    {
        if ($payment->isSuccessful()) {
            return redirect()->back()
                ->with('error', 'Pembayaran sudah dikonfirmasi.');
        }

        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'notes' => 'nullable|string|max:500',
            ]);

            $payment->markAsSuccessful([
                'confirmed_by' => auth()->id(),
                'confirmed_at' => now()->toISOString(),
                'manual_confirmation_notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Pembayaran berhasil dikonfirmasi. Langganan telah diperpanjang.');

        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment confirmation error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengonfirmasi pembayaran.');
        }
    }
}
