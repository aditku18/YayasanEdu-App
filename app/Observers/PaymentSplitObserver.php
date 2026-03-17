<?php

namespace App\Observers;

use App\Models\Finance\PaymentSplit;
use App\Models\Transaction;

class PaymentSplitObserver
{
    /**
     * Handle the PaymentSplit "created" event.
     */
    public function created(PaymentSplit $paymentSplit): void
    {
        $this->createTransactionIfCompleted($paymentSplit);
    }

    /**
     * Handle the PaymentSplit "updated" event.
     */
    public function updated(PaymentSplit $paymentSplit): void
    {
        // If status changed to completed, create transaction
        if ($paymentSplit->isDirty('status') && $paymentSplit->status === 'completed') {
            $this->createTransactionIfCompleted($paymentSplit);
        }
    }

    /**
     * Create Transaction from PaymentSplit if status is completed.
     */
    private function createTransactionIfCompleted(PaymentSplit $paymentSplit): void
    {
        if ($paymentSplit->status !== 'completed') {
            return;
        }

        // Check if transaction already exists for this payment split
        $existingTransaction = Transaction::where('description', $paymentSplit->notes)
            ->orWhere('amount', $paymentSplit->amount)
            ->where('foundation_id', $paymentSplit->foundation_id)
            ->whereDate('created_at', $paymentSplit->created_at)
            ->first();

        if ($existingTransaction) {
            return; // Transaction already exists
        }

        // Get payment method from gateway
        $paymentMethod = 'manual';
        if ($paymentSplit->paymentGateway) {
            $paymentMethod = $paymentSplit->paymentGateway->name;
        }

        // Create Transaction
        Transaction::create([
            'foundation_id' => $paymentSplit->foundation_id,
            'plan_id' => 1, // Default - could be determined from invoice
            'amount' => $paymentSplit->amount,
            'type' => 'subscription',
            'status' => 'success',
            'description' => $paymentSplit->notes ?? 'Payment from split #' . $paymentSplit->id,
            'payment_method' => $paymentMethod,
            'transaction_id' => 'TXN-SPLIT-' . $paymentSplit->id . '-' . time(),
            'created_by' => $paymentSplit->created_by ?? 1
        ]);
    }

    /**
     * Handle the PaymentSplit "deleted" event.
     */
    public function deleted(PaymentSplit $paymentSplit): void
    {
        // Optionally delete related transaction
        // Transaction::where('description', 'like', '%#' . $paymentSplit->id . '%')->delete();
    }
}
