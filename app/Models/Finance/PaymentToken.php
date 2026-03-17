<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentToken extends Model
{
    protected $fillable = [
        'user_id',
        'payment_gateway_id',
        'gateway_token',
        'payment_method',
        'method_identifier',
        'method_display_name',
        'method_metadata',
        'is_default',
        'is_active',
        'expires_at',
        'last_used_at',
    ];

    protected $casts = [
        'method_metadata' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function paymentGateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    public function recurringPayments(): HasMany
    {
        return $this->hasMany(RecurringPayment::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    public function getMaskedIdentifier(): string
    {
        $identifier = $this->method_identifier;
        $length = strlen($identifier);
        
        if ($length <= 4) {
            return str_repeat('*', $length);
        }
        
        return str_repeat('*', $length - 4) . substr($identifier, -4);
    }

    public function getDisplayText(): string
    {
        $methodNames = [
            'credit_card' => 'Kartu Kredit',
            'bank_account' => 'Rekening Bank',
            'ewallet' => 'E-Wallet',
            'qris' => 'QRIS',
        ];

        $name = $methodNames[$this->payment_method] ?? $this->payment_method;
        $masked = $this->getMaskedIdentifier();
        
        return "{$name} - {$masked}";
    }
}
