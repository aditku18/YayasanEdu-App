<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonInstallation extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $fillable = [
        'addon_id',
        'tenant_id',
        'installed_at',
        'activated_at',
        'status',
        'version',
        'settings',
        'license_key',
        'installation_notes'
    ];

    protected $casts = [
        'installed_at' => 'datetime',
        'activated_at' => 'datetime',
        'settings' => 'array',
    ];

    /**
     * Get the addon that was installed
     */
    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }

    /**
     * Get the tenant that installed this addon
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Check if installation is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->activated_at !== null;
    }

    /**
     * Scope to get active installations
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get installations by tenant
     */
    public function scopeByTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }
}
