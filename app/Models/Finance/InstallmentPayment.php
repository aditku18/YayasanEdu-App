<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstallmentPayment extends Model
{
    protected $fillable = [
        'installment_plan_id',
        'payment_id',
        'installment_number',
        'amount_due',
        'amount_paid',
        'due_date',
        'paid_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'amount_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    public function installmentPlan(): BelongsTo
    {
        return $this->belongsTo(InstallmentPlan::class, 'installment_plan_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late')
            ->orWhere(function ($q) {
                $q->where('status', 'pending')
                  ->where('due_date', '<', now()->toDateString());
            });
    }

    public static function getStatuses(): array
    {
        return [
            'pending' => 'Menunggu',
            'paid' => 'Dibayar',
            'late' => 'Terlambat',
            'defaulted' => 'Melanggar',
        ];
    }

    public function markAsPaid(?int $paymentId = null): void
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => now()->toDateString(),
            'payment_id' => $paymentId,
        ]);

        // Check if all installments are paid
        $this->installmentPlan->checkCompletion();
    }

    public function isOverdue(): bool
    {
        return $this->status === 'pending' && $this->due_date < now()->toDateString();
    }
}
