<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PluginInstallation extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $fillable = [
        'plugin_id',
        'foundation_id',
        'is_active',
        'installed_at',
        'installed_by',
        'settings',
        'last_updated_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'installed_at' => 'datetime',
        'last_updated_at' => 'datetime',
        'settings' => 'array'
    ];

    /**
     * Get the plugin that was installed
     */
    public function plugin()
    {
        return $this->belongsTo(Plugin::class);
    }

    /**
     * Get the foundation that installed this plugin
     */
    public function foundation()
    {
        return $this->belongsTo(Foundation::class);
    }

    /**
     * Get the user who installed this plugin
     */
    public function installer()
    {
        return $this->belongsTo(User::class, 'installed_by');
    }

    /**
     * Check if installation is active
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Scope to get active installations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get installations by foundation
     */
    public function scopeByFoundation($query, $foundationId)
    {
        return $query->where('foundation_id', $foundationId);
    }

    /**
     * Scope to get installations by tenant (alias for foundation)
     */
    public function scopeByTenant($query, $tenantId)
    {
        return $query->where('foundation_id', $tenantId);
    }
}
