<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\RecurringPayment;
use App\Models\Finance\PaymentToken;
use App\Services\PaymentGatewayManager;
use Illuminate\Support\Facades\Log;

class RecurringPaymentController extends Controller
{
    protected PaymentGatewayManager $gatewayManager;

    public function __construct(PaymentGatewayManager $gatewayManager)
    {
        $this->gatewayManager = $gatewayManager;
    }

    public function index()
    {
        $search = request()->query('search');
        $status = request()->query('status');

        $query = RecurringPayment::with(['user', 'paymentToken.paymentGateway']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $recurringPayments = $query->orderBy('next_charge_date', 'asc')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => RecurringPayment::count(),
            'active' => RecurringPayment::where('status', 'active')->count(),
            'paused' => RecurringPayment::where('status', 'paused')->count(),
            'completed' => RecurringPayment::where('status', 'completed')->count(),
            'monthly_amount' => RecurringPayment::where('status', 'active')
                ->where('frequency', 'monthly')
                ->sum('amount'),
        ];

        return view('admin.recurring-payments.index', compact('recurringPayments', 'search', 'status', 'stats'));
    }

    public function create()
    {
        $paymentTokens = PaymentToken::with('paymentGateway')
            ->where('user_id', auth()->id())
            ->where('is_active', true)
            ->get();

        return view('admin.recurring-payments.create', compact('paymentTokens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_token_id' => 'required|exists:payment_tokens,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000',
            'frequency' => 'required|in:daily,weekly,monthly,quarterly,yearly',
            'frequency_value' => 'required|integer|min:1',
            'next_charge_date' => 'required|date|after:today',
            'end_date' => 'nullable|date|after:next_charge_date',
            'max_charges' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $paymentToken = PaymentToken::findOrFail($validated['payment_token_id']);
        
        // Validate token ownership
        if ($paymentToken->user_id !== auth()->id()) {
            return redirect()->back()
                ->with('error', 'Payment token tidak valid.');
        }

        RecurringPayment::create([
            'user_id' => auth()->id(),
            'payment_token_id' => $validated['payment_token_id'],
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'frequency' => $validated['frequency'],
            'frequency_value' => $validated['frequency_value'],
            'next_charge_date' => $validated['next_charge_date'],
            'end_date' => $validated['end_date'],
            'max_charges' => $validated['max_charges'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('platform.recurring-payments.index')
            ->with('success', 'Recurring payment berhasil dibuat.');
    }

    public function show(RecurringPayment $recurringPayment)
    {
        $recurringPayment->load(['user', 'paymentToken.paymentGateway']);
        
        return view('admin.recurring-payments.show', compact('recurringPayment'));
    }

    public function edit(RecurringPayment $recurringPayment)
    {
        // Validate ownership
        if ($recurringPayment->user_id !== auth()->id()) {
            return redirect()->back()
                ->with('error', 'Tidak memiliki akses ke recurring payment ini.');
        }

        $recurringPayment->load(['paymentToken.paymentGateway']);
        
        return view('admin.recurring-payments.edit', compact('recurringPayment'));
    }

    public function update(Request $request, RecurringPayment $recurringPayment)
    {
        // Validate ownership
        if ($recurringPayment->user_id !== auth()->id()) {
            return redirect()->back()
                ->with('error', 'Tidak memiliki akses ke recurring payment ini.');
        }

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000',
            'frequency' => 'required|in:daily,weekly,monthly,quarterly,yearly',
            'frequency_value' => 'required|integer|min:1',
            'next_charge_date' => 'required|date',
            'end_date' => 'nullable|date|after:next_charge_date',
            'max_charges' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $recurringPayment->update($validated);

        return redirect()->route('platform.recurring-payments.show', $recurringPayment->id)
            ->with('success', 'Recurring payment berhasil diperbarui.');
    }

    public function pause(RecurringPayment $recurringPayment)
    {
        // Validate ownership
        if ($recurringPayment->user_id !== auth()->id()) {
            return redirect()->back()
                ->with('error', 'Tidak memiliki akses ke recurring payment ini.');
        }

        if ($recurringPayment->status !== 'active') {
            return redirect()->back()
                ->with('error', 'Hanya recurring payment aktif yang bisa dijeda.');
        }

        $recurringPayment->update(['status' => 'paused']);

        return redirect()->back()
            ->with('success', 'Recurring payment berhasil dijeda.');
    }

    public function resume(RecurringPayment $recurringPayment)
    {
        // Validate ownership
        if ($recurringPayment->user_id !== auth()->id()) {
            return redirect()->back()
                ->with('error', 'Tidak memiliki akses ke recurring payment ini.');
        }

        if ($recurringPayment->status !== 'paused') {
            return redirect()->back()
                ->with('error', 'Hanya recurring payment yang dijeda yang bisa dilanjutkan.');
        }

        $recurringPayment->update(['status' => 'active']);

        return redirect()->back()
            ->with('success', 'Recurring payment berhasil dilanjutkan.');
    }

    public function cancel(RecurringPayment $recurringPayment)
    {
        // Validate ownership
        if ($recurringPayment->user_id !== auth()->id()) {
            return redirect()->back()
                ->with('error', 'Tidak memiliki akses ke recurring payment ini.');
        }

        if (in_array($recurringPayment->status, ['completed', 'cancelled'])) {
            return redirect()->back()
                ->with('error', 'Recurring payment sudah selesai atau dibatalkan.');
        }

        $recurringPayment->update(['status' => 'cancelled']);

        return redirect()->back()
            ->with('success', 'Recurring payment berhasil dibatalkan.');
    }

    public function processScheduled()
    {
        // This method would be called by a cron job
        $duePayments = RecurringPayment::where('status', 'active')
            ->where('next_charge_date', '<=', now())
            ->where(function ($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>', now());
            })
            ->where(function ($query) {
                $query->whereNull('max_charges')
                      ->orWhere('total_charges', '<', \DB::raw('max_charges'));
            })
            ->with(['paymentToken', 'user'])
            ->get();

        $processedCount = 0;
        $failedCount = 0;

        foreach ($duePayments as $recurringPayment) {
            try {
                $this->processRecurringCharge($recurringPayment);
                $processedCount++;
            } catch (\Exception $e) {
                Log::error("Failed to process recurring payment {$recurringPayment->id}: " . $e->getMessage());
                $failedCount++;
            }
        }

        return response()->json([
            'processed' => $processedCount,
            'failed' => $failedCount,
            'total' => $duePayments->count(),
        ]);
    }

    protected function processRecurringCharge(RecurringPayment $recurringPayment): void
    {
        $gateway = $recurringPayment->paymentToken->paymentGateway;
        
        $paymentData = [
            'order_id' => 'RECUR-' . time() . '-' . $recurringPayment->id,
            'amount' => $recurringPayment->amount,
            'payment_type' => $recurringPayment->paymentToken->payment_method,
            'token' => $recurringPayment->paymentToken->gateway_token,
            'customer' => [
                'first_name' => $recurringPayment->user->name,
                'email' => $recurringPayment->user->email,
            ],
            'description' => $recurringPayment->description,
        ];

        $gatewayResponse = $this->gatewayManager->createPayment($gateway->name, $paymentData);

        // Update recurring payment
        $recurringPayment->update([
            'last_charge_date' => now(),
            'next_charge_date' => $recurringPayment->calculateNextChargeDate(),
            'total_charges' => $recurringPayment->total_charges + 1,
            'last_gateway_response' => $gatewayResponse,
        ]);

        // Check if should be completed
        if ($recurringPayment->hasReachedMaxCharges() || 
            ($recurringPayment->end_date && $recurringPayment->end_date->isPast())) {
            $recurringPayment->update(['status' => 'completed']);
        }

        // Send confirmation email/notification
        $this->sendRecurringChargeNotification($recurringPayment, $gatewayResponse);
    }

    protected function sendRecurringChargeNotification(RecurringPayment $recurringPayment, array $gatewayResponse): void
    {
        // Implementation for email/SMS notification
        // This would integrate with existing notification system
        Log::info("Recurring payment notification sent for payment {$recurringPayment->id}");
    }
}
