<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Plan extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_per_month',
        'price_per_year',
        'max_schools',
        'max_users',
        'max_students',
        'max_teachers',
        'max_parents',
        'features',
        'is_active',
        'is_featured',
        'featured_label',
        'sort_order',
        'has_cbt',
        'has_online_course',
        'has_digital_wallet',
        'has_canteen',
        'has_custom_domain',
        'has_api_access',
        'storage_gb',
        'has_email_support',
        'has_priority_support',
        'has_sms_notification',
        'highlight_features',
        'duration_days',
        'included_plugins',
        'plugin_slots',
        'plugin_categories',
        'bundle_savings',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price_per_month' => 'decimal:2',
        'price_per_year' => 'decimal:2',
        'has_cbt' => 'boolean',
        'has_online_course' => 'boolean',
        'has_digital_wallet' => 'boolean',
        'has_canteen' => 'boolean',
        'has_custom_domain' => 'boolean',
        'has_api_access' => 'boolean',
        'has_email_support' => 'boolean',
        'has_priority_support' => 'boolean',
        'has_sms_notification' => 'boolean',
        'storage_gb' => 'integer',
        'max_schools' => 'integer',
        'max_users' => 'integer',
        'max_students' => 'integer',
        'max_teachers' => 'integer',
        'max_parents' => 'integer',
        'duration_days' => 'integer',
        'included_plugins' => 'array',
        'plugin_slots' => 'integer',
        'plugin_categories' => 'array',
        'bundle_savings' => 'decimal:2',
    ];

    /**
     * Get featured label or default
     */
    public function getFeaturedLabelAttribute(): string
    {
        return $this->attributes['featured_label'] ?? ($this->is_featured ? 'REKOMENDASI' : '');
    }

    public function foundations()
    {
        return $this->hasMany(Foundation::class);
    }

    public function includedPlugins()
    {
        return $this->belongsToMany(
            Plugin::class ,
            'plan_plugin',
            'plan_id',
            'plugin_id'
        );
    }

    public function pluginInstallations()
    {
        // This relationship is not directly applicable - plugin installations
        // are tracked per foundation/tenant, not directly per plan
        return $this->hasMany(PluginInstallation::class);
    }

    public static function booted()
    {
        static::creating(function ($plan) {
            if (empty($plan->slug)) {
                $plan->slug = Str::slug($plan->name);
            }
        });
    }

    /**
     * Get the plan by slug
     */
    public static function findBySlug(string $slug)
    {
        return static::where('slug', $slug)->first();
    }

    /**
     * Get formatted price
     */
    public function getFormattedPrice(): string
    {
        return 'Rp ' . number_format($this->price_per_month, 0, ',', '.');
    }

    /**
     * Get formatted yearly price
     */
    public function getFormattedYearlyPrice(): string
    {
        return 'Rp ' . number_format($this->price_per_year, 0, ',', '.');
    }

    /**
     * Get features as array
     */
    public function getFeaturesArray(): array
    {
        return is_array($this->features) ? $this->features : [];
    }

    /**
     * Get duration in readable format
     */
    public function getDurationText(): string
    {
        $days = $this->duration_days ?? 30;
        if ($days >= 30) {
            $months = $days / 30;
            return $months == 1 ? '1 Bulan' : $months . ' Bulan';
        }
        return $days . ' Hari';
    }

    /**
     * Calculate savings percentage for yearly billing
     */
    public function getYearlySavingsPercent(): int
    {
        if ($this->price_per_month > 0 && $this->price_per_year > 0) {
            $monthlyTotal = $this->price_per_month * 12;
            $savings = (($monthlyTotal - $this->price_per_year) / $monthlyTotal) * 100;
            return round($savings);
        }
        return 0;
    }

    /**
     * Check if plan is free
     */
    public function isFree(): bool
    {
        return $this->price_per_month == 0;
    }

    /**
     * Get highlight features as array
     */
    public function getHighlightFeaturesArray(): array
    {
        if (empty($this->highlight_features)) {
            return [];
        }
        return array_filter(array_map('trim', explode("\n", $this->highlight_features)));
    }

    /**
     * Get total plugins (included + available slots)
     */
    public function getTotalPluginsAttribute(): int
    {
        $included = count($this->included_plugins ?? []);
        $slots = $this->plugin_slots ?? 0;
        return $included + $slots;
    }

    /**
     * Get formatted plugin slots display
     */
    public function getFormattedPluginSlotsAttribute(): string
    {
        $included = count($this->included_plugins ?? []);
        $total = $this->plugin_slots ?? 0;
        $remaining = max(0, $total - $included);

        return "{$included}/{$total} ({$remaining} tersedia)";
    }

    /**
     * Get included plugin models
     */
    public function getIncludedPluginModels(): \Illuminate\Database\Eloquent\Collection
    {
        if (empty($this->included_plugins)) {
            return collect([]);
        }

        return Plugin::whereIn('slug', $this->included_plugins)->get();
    }

    /**
     * Calculate bundle savings
     */
    public function calculateBundleSavings(): float
    {
        $includedPlugins = $this->getIncludedPluginModels();
        $individualPrice = $includedPlugins->sum('price');

        $this->bundle_savings = max(0, $individualPrice);
        $this->save();

        return $this->bundle_savings;
    }

    /**
     * Check if plugin is included in plan
     */
    public function hasPlugin(string $pluginSlug): bool
    {
        return in_array($pluginSlug, $this->included_plugins ?? []);
    }

    /**
     * Get available plugin slots
     */
    public function getAvailablePluginSlots(): int
    {
        $included = count($this->included_plugins ?? []);
        $total = $this->plugin_slots ?? 0;
        return max(0, $total - $included);
    }
}
