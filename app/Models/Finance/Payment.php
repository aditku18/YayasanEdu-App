<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
        protected $connection = 'tenant';

    protected $fillable = [
        'payment_number',
        'invoice_id',
        'student_id',
        'amount',
        'admin_fee',
        'total_amount',
        'payment_date',
        'payment_method',
        'bank_name',
        'account_number',
        'account_name',
        'reference_number',
        'payment_proof',
        'status',
        'notes',
        'confirmed_by',
        'confirmed_at',
        'school_unit_id',
        'payment_gateway_id',
        'gateway_response',
        'split_payment_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'payment_date' => 'date',
        'confirmed_at' => 'datetime',
        'gateway_response' => 'array',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Student::class, 'student_id');
    }

    public function confirmer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'confirmed_by');
    }

    public function paymentGateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class, 'payment_gateway_id');
    }

    public function splits(): HasMany
    {
        return $this->hasMany(PaymentSplit::class, 'payment_id');
    }

    public function schoolUnit(): BelongsTo
    {
        return $this->belongsTo(\App\Models\SchoolUnit::class, 'school_unit_id');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_unit_id', $schoolId);
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    public static function getStatuses(): array
    {
        return [
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'rejected' => 'Ditolak',
            'refunded' => 'Dikembalikan',
        ];
    }

    public static function getPaymentMethods(): array
    {
        return [
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'virtual_account' => 'Virtual Account',
            'qris' => 'QRIS',
            'other' => 'Lainnya',
        ];
    }

    public static function generatePaymentNumber(): string
    {
        $prefix = 'PAY';
        $date = now()->format('Ym');
        $lastPayment = self::where('payment_number', 'like', "{$prefix}{$date}%")
            ->orderBy('payment_number', 'desc')
            ->first();

        if ($lastPayment) {
            $lastNumber = (int) substr($lastPayment->payment_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function confirm(?int $confirmedBy = null): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_by' => $confirmedBy ?? auth()->id(),
            'confirmed_at' => now(),
        ]);

        // Update invoice paid amount
        $invoice = $this->invoice;
        $invoice->paid_amount += $this->total_amount;
        $invoice->updatePaymentStatus();
    }

    public function reject(?string $notes = null): void
    {
        $this->update([
            'status' => 'rejected',
            'notes' => $notes,
        ]);
    }
}
