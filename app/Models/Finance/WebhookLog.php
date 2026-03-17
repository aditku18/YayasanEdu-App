<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebhookLog extends Model
{
    protected $fillable = [
        'payment_gateway_id',
        'webhook_id',
        'event_type',
        'payload',
        'status',
        'error_message',
        'processed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'processed_at' => 'datetime',
    ];

    public function paymentGateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    public function isProcessed(): bool
    {
        return $this->status === 'processed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isDuplicate(): bool
    {
        return $this->status === 'duplicate';
    }

    public function getEventTypeText(): string
    {
        $eventTypes = [
            'payment.success' => 'Pembayaran Berhasil',
            'payment.pending' => 'Pembayaran Pending',
            'payment.failed' => 'Pembayaran Gagal',
            'payment.cancelled' => 'Pembayaran Dibatalkan',
            'payment.expired' => 'Pembayaran Kadaluarsa',
            'recurring.created' => 'Recurring Dibuat',
            'recurring.paused' => 'Recurring Dijeda',
            'recurring.resumed' => 'Recurring Dilanjutkan',
            'recurring.cancelled' => 'Recurring Dibatalkan',
        ];

        return $eventTypes[$this->event_type] ?? $this->event_type;
    }
}
