<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonPurchase extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $fillable = [
        'addon_id',
        'tenant_id',
        'user_id',
        'purchase_date',
        'amount',
        'currency',
        'payment_method',
        'payment_status',
        'transaction_id',
        'license_key',
        'expiry_date',
        'is_recurring',
        'billing_cycle',
        'next_billing_date'
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
        'expiry_date' => 'datetime',
        'next_billing_date' => 'datetime',
        'is_recurring' => 'boolean',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the addon that was purchased
     */
    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }

    /**
     * Get the tenant that made the purchase
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the user who made the purchase
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if purchase is active
     */
    public function isActive(): bool
    {
        return $this->payment_status === 'completed' && 
               (!$this->expiry_date || $this->expiry_date->isFuture());
    }

    /**
     * Check if purchase is expired
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmount(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Scope to get completed purchases
     */
    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'completed');
    }

    /**
     * Scope to get active purchases
     */
    public function scopeActive($query)
    {
        return $query->where('payment_status', 'completed')
                    ->where(function ($q) {
                        $q->whereNull('expiry_date')
                          ->orWhere('expiry_date', '>', now());
                    });
    }
}
