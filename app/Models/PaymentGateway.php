<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $connection = 'central';

    protected $fillable = [
        'name',
        'code',
        'description',
        'logo',
        'is_active',
        'config',
        'supported_methods',
        'currency',
        'min_amount',
        'max_amount',
        'fee_percentage',
        'fee_fixed',
        'display_name',
        'type',
        'supports_recurring',
        'supports_split_payment',
        'admin_fee_rate',
        'fixed_admin_fee',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'supports_recurring' => 'boolean',
        'supports_split_payment' => 'boolean',
        'config' => 'array',
        'supported_methods' => 'array',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'fee_percentage' => 'decimal:2',
        'fee_fixed' => 'decimal:2',
        'admin_fee_rate' => 'decimal:4',
        'fixed_admin_fee' => 'decimal:2',
    ];

    /**
     * Get active payment gateways
     */
    public static function getActive()
    {
        return self::where('is_active', true)->get();
    }

    /**
     * Get gateway by name
     */
    public static function getByName(string $name): ?self
    {
        return self::where('name', $name)->where('is_active', true)->first();
    }

    /**
     * Check if gateway supports specific payment method
     */
    public function supportsMethod(string $method): bool
    {
        return in_array($method, $this->supported_methods ?? []);
    }

    /**
     * Calculate processing fee
     */
    public function calculateFee(float $amount): float
    {
        $fee = 0;
        
        if ($this->admin_fee_rate > 0) {
            $fee += ($amount * $this->admin_fee_rate) / 100;
        }
        
        if ($this->fixed_admin_fee > 0) {
            $fee += $this->fixed_admin_fee;
        }
        
        return $fee;
    }

    /**
     * Check if amount is within gateway limits
     */
    public function isAmountValid(float $amount): bool
    {
        if ($this->min_amount && $amount < $this->min_amount) {
            return false;
        }
        
        if ($this->max_amount && $amount > $this->max_amount) {
            return false;
        }
        
        return true;
    }

    /**
     * Get formatted config value
     */
    public function getConfigValue(string $key, $default = null)
    {
        return data_get($this->config, $key, $default);
    }

    /**
     * Set config value
     */
    public function setConfigValue(string $key, $value): void
    {
        $config = $this->config ?? [];
        data_set($config, $key, $value);
        $this->config = $config;
    }

    /**
     * Get available payment methods for this gateway
     */
    public static function getAvailableMethods(): array
    {
        return [
            'credit_card' => 'Kartu Kredit',
            'bank_transfer' => 'Transfer Bank',
            'virtual_account' => 'Virtual Account',
            'ewallet' => 'E-Wallet',
            'qris' => 'QRIS',
            'cstore' => 'Convenience Store',
            'installment' => 'Cicilan',
        ];
    }

    /**
     * Get gateway currencies
     */
    public static function getSupportedCurrencies(): array
    {
        return [
            'IDR' => 'Indonesian Rupiah',
            'USD' => 'US Dollar',
            'EUR' => 'Euro',
        ];
    }

    /**
     * Relationships
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function recurringPayments(): HasMany
    {
        return $this->hasMany(RecurringPayment::class);
    }

    /**
     * Scope for active gateways
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific currency
     */
    public function scopeForCurrency($query, string $currency)
    {
        return $query->where('currency', $currency);
    }

    /**
     * Get gateway display name with logo
     */
    public function getDisplayName(): string
    {
        return $this->logo 
            ? '<img src="' . asset('storage/' . $this->logo) . '" alt="' . $this->display_name . '" height="20"> ' . $this->display_name
            : $this->display_name;
    }

    /**
     * Check if gateway is properly configured
     */
    public function isConfigured(): bool
    {
        $requiredConfig = $this->getRequiredConfig();
        
        foreach ($requiredConfig as $key) {
            if (empty($this->getConfigValue($key))) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get required configuration keys based on gateway type
     */
    public function getRequiredConfig(): array
    {
        switch ($this->name) {
            case 'midtrans':
                return ['server_key', 'client_key', 'merchant_id'];
            case 'xendit':
                return ['secret_key', 'api_url'];
            case 'manual':
            case 'bank_transfer_bca':
            case 'bank_transfer_mandiri':
            case 'bank_transfer_bni':
            case 'bank_transfer_bri':
                return ['bank_name', 'account_number', 'account_name'];
            default:
                if (str_starts_with($this->name, 'bank_transfer_')) {
                    return ['bank_name', 'account_number', 'account_name'];
                }
                return [];
        }
    }

    /**
     * Get gateway status badge HTML
     */
    public function getStatusBadge(): string
    {
        if (!$this->is_active) {
            return '<span class="badge bg-secondary">Non-Active</span>';
        }
        
        if (!$this->isConfigured()) {
            return '<span class="badge bg-warning">Not Configured</span>';
        }
        
        return '<span class="badge bg-success">Active</span>';
    }
}
