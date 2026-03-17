<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ActivityLog Model - For tracking user activities
 * 
 * This model is used by the HasAuditLog trait to track
 * all create, update, and delete actions on models.
 */
class ActivityLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'foundation_id',
        'module',
        'action',
        'reference_type',
        'reference_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that performed the action.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the foundation if applicable.
     *
     * @return BelongsTo
     */
    public function foundation(): BelongsTo
    {
        return $this->belongsTo(Foundation::class, 'foundation_id');
    }

    /**
     * Get the reference model (polymorphic).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reference(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('reference', 'reference_type', 'reference_id');
    }

    /**
     * Get the changes between old and new values.
     *
     * @return array|null
     */
    public function getChanges(): ?array
    {
        if (!$this->old_values || !$this->new_values) {
            return null;
        }

        $changes = [];
        
        foreach ($this->new_values as $key => $newValue) {
            $oldValue = $this->old_values[$key] ?? null;
            if ($oldValue !== $newValue) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return empty($changes) ? null : $changes;
    }

    /**
     * Scope to filter by module.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $module
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope to filter by action.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $action
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by date range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $from
     * @param string $to
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateRange($query, string $from, string $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Scope to get recent logs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecent($query, int $limit = 50)
    {
        return $query->latest()->limit($limit);
    }

    /**
     * Get human-readable action description.
     *
     * @return string
     */
    public function getActionDescription(): string
    {
        return match($this->action) {
            'create' => 'membuat',
            'update' => 'mengubah',
            'delete' => 'menghapus',
            'restore' => 'memulihkan',
            default => $this->action,
        };
    }

    /**
     * Get the display name for the module.
     *
     * @return string
     */
    public function getModuleDisplayName(): string
    {
        return match($this->module) {
            'student' => 'Siswa',
            'teacher' => 'Guru',
            'user' => 'Pengguna',
            'class_room' => 'Kelas',
            'subject' => 'Mata Pelajaran',
            'invoice' => 'Tagihan',
            'payment' => 'Pembayaran',
            'expense' => 'Pengeluaran',
            'ppdb_applicant' => 'Calon Siswa',
            'grade' => 'Nilai',
            'attendance' => 'Absensi',
            default => ucwords(str_replace('_', ' ', $this->module)),
        };
    }
}
