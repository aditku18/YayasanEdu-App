<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\SchoolUnit;

class Foundation extends Model
{
    use HasFactory;

    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'central';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'foundations';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'province',
        'regency',
        'npsn',
        'institution_type',
        'education_levels',
        'student_count',
        'website',
        'subdomain',
        'status',
        'plan_id',
        'admin_user_id',
        'tenant_id',
        'documents_verified_at',
        'documents_verified_by',
        'subscription_ends_at',
        'trial_ends_at',
        'is_trial',
        'rejection_reason',
        'trial_extension_reason',
        'sk_pendirian_path',
        'npsn_izin_path',
        'logo_path',
        'gedung_path',
        'ktp_path',
        'included_plugins',
        'additional_plugins',
        'plugin_slots',
        'plugins_installed_at',
    ];

    protected function casts(): array
    {
        return [
            'trial_ends_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
            'documents_verified_at' => 'datetime',
            'is_trial' => 'boolean',
            'included_plugins' => 'array',
            'additional_plugins' => 'array',
            'plugins_installed_at' => 'datetime',
        ];
    }

    // ── Relationships ──

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function adminUser()
    {
        return $this->belongsTo(User::class , 'admin_user_id');
    }

    public function documentsVerifier()
    {
        return $this->belongsTo(User::class , 'documents_verified_by');
    }

    public function users()
    {
        return $this->hasManyThrough(User::class , Tenant::class , 'id', 'tenant_id', 'tenant_id', 'id');
    }

    public function user()
    {
        // Get the first user associated with this foundation's tenant
        return $this->hasOneThrough(User::class , Tenant::class , 'id', 'tenant_id', 'tenant_id', 'id');
    }

    public function schools()
    {
        return $this->hasMany(SchoolUnit::class , 'foundation_id');
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class , SchoolUnit::class , 'foundation_id', 'school_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function plugins()
    {
        return $this->belongsToMany(Plugin::class, 'plugin_installations')
            ->withPivot(['is_active', 'installed_at', 'installed_by', 'installation_type'])
            ->withTimestamps();
    }

    public function includedPlugins()
    {
        if (empty($this->included_plugins)) {
            return collect([]);
        }
        
        return Plugin::whereIn('slug', $this->included_plugins)->get();
    }

    public function additionalPlugins()
    {
        if (empty($this->additional_plugins)) {
            return collect([]);
        }
        
        return Plugin::whereIn('id', $this->additional_plugins)->get();
    }

    public function getTotalPluginsAttribute(): int
    {
        $included = count($this->included_plugins ?? []);
        $additional = count($this->additional_plugins ?? []);
        return $included + $additional;
    }

    public function getAvailablePluginSlotsAttribute(): int
    {
        $total = $this->plugin_slots ?? 0;
        $used = $this->total_plugins;
        return max(0, $total - $used);
    }

    // ── Trial Helpers ──

    public function isTrialActive(): bool
    {
        return $this->status === 'trial' && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function isTrialExpired(): bool
    {
        return $this->status === 'trial' && $this->trial_ends_at && $this->trial_ends_at->isPast();
    }

    public function daysLeftInTrial(): int
    {
        if (!$this->trial_ends_at || $this->trial_ends_at->isPast()) {
            return 0;
        }
        return (int)now()->diffInDays($this->trial_ends_at, false);
    }

    // ── Scopes ──

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeTrial($query)
    {
        return $query->where('status', 'trial');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // ── Document Verification Helpers ──

    public function hasVerifiedDocuments(): bool
    {
        return !is_null($this->documents_verified_at);
    }

    public function hasUploadedDocuments(): bool
    {
        return !empty($this->sk_pendirian_path) || !empty($this->ktp_path);
    }

    public function getDocumentPaths(): array
    {
        return array_filter([
            'SK Pendirian' => $this->sk_pendirian_path,
            'NPSN / Izin' => $this->npsn_izin_path,
            'Logo' => $this->logo_path,
            'Foto Gedung' => $this->gedung_path,
            'KTP Penanggung Jawab' => $this->ktp_path,
        ]);
    }
}
