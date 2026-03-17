<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $connection = 'central';

    protected $fillable = [
        'foundation_id',
        'subscription_id',
        'invoice_number',
        'amount',
        'status',
        'due_date',
        'paid_at',
        'file_path',
        'items',
        'notes',
        'payment_token',
        'payment_receipt',
        'billing_cycle',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'items' => 'array',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    /**
     * Get the invoice statuses
     */
    public static function getStatuses(): array
    {
        return [
            'pending' => 'Menunggu Pembayaran',
            'verifying' => 'Menunggu Verifikasi',
            'paid' => 'Lunas',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Dikembalikan',
            'overdue' => 'Jatuh Tempo',
        ];
    }

    /**
     * Generate unique invoice number
     */
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $date = now()->format('Ym');
        $lastInvoice = self::where('invoice_number', 'like', "{$prefix}{$date}%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int)substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        }
        else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmount(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Check if invoice is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'pending' &&
            $this->due_date &&
            $this->due_date->isPast();
    }

    /**
     * Check if invoice is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if invoice is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function foundation()
    {
        return $this->belongsTo(Foundation::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
