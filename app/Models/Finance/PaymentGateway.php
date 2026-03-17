<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentGateway extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'type',
        'config',
        'is_active',
        'supports_recurring',
        'supports_split_payment',
        'supported_methods',
        'min_amount',
        'max_amount',
        'admin_fee_rate',
        'fixed_admin_fee',
        'priority',
    ];

    protected $casts = [
        'config' => 'array',
        'supported_methods' => 'array',
        'is_active' => 'boolean',
        'supports_recurring' => 'boolean',
        'supports_split_payment' => 'boolean',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'admin_fee_rate' => 'decimal:4',
        'fixed_admin_fee' => 'decimal:2',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function paymentTokens(): HasMany
    {
        return $this->hasMany(PaymentToken::class);
    }

    public function webhookLogs(): HasMany
    {
        return $this->hasMany(WebhookLog::class);
    }

    public function isConfigured(): bool
    {
        return !empty($this->config) && 
               isset($this->config['server_key']) && 
               isset($this->config['client_key']);
    }

    public function supportsMethod(string $method): bool
    {
        return in_array($method, $this->supported_methods ?? []);
    }

    public function calculateAdminFee(float $amount): float
    {
        $percentageFee = $amount * ($this->admin_fee_rate / 100);
        return $percentageFee + $this->fixed_admin_fee;
    }

    public function getTotalAdminFee(float $amount): float
    {
        return $this->calculateAdminFee($amount);
    }
}
