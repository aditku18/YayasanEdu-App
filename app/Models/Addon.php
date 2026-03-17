<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'is_active',
        'is_recurring',
        'features',
        'tenant_id',
        'addon_code',
        'version',
        'developer',
        'documentation_url',
        'support_url',
        'requirements',
        'installation_date',
        'expiry_date',
        'max_users',
        'max_storage',
        'custom_settings'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_recurring' => 'boolean',
        'features' => 'array',
        'requirements' => 'array',
        'custom_settings' => 'array',
        'installation_date' => 'datetime',
        'expiry_date' => 'datetime',
    ];

    /**
     * Get the tenant that owns this addon
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get addon installations
     */
    public function installations()
    {
        return $this->hasMany(AddonInstallation::class);
    }

    /**
     * Get addon purchases
     */
    public function purchases()
    {
        return $this->hasMany(AddonPurchase::class);
    }

    /**
     * Check if addon is active
     */
    public function isActive(): bool
    {
        return $this->is_active && (!$this->expiry_date || $this->expiry_date->isFuture());
    }

    /**
     * Check if addon is expired
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Get formatted price
     */
    public function getFormattedPrice(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get features as array
     */
    public function getFeaturesArray(): array
    {
        return is_array($this->features) ? $this->features : [];
    }

    /**
     * Get requirements as array
     */
    public function getRequirementsArray(): array
    {
        return is_array($this->requirements) ? $this->requirements : [];
    }

    /**
     * Scope to get only active addons
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('expiry_date')
                          ->orWhere('expiry_date', '>', now());
                    });
    }

    /**
     * Scope to get addons by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to get available addons for tenant
     */
    public function scopeAvailableFor($query, $tenantId)
    {
        return $query->where(function ($q) use ($tenantId) {
            $q->whereNull('tenant_id')
              ->orWhere('tenant_id', $tenantId);
        });
    }
}
