<?php

namespace App\Core\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * Trait HasAuditLog
 * 
 * Provides automatic activity logging functionality.
 * Use this trait in models that need to track changes.
 * 
 * Requires: activity_logs table
 * 
 * Migration:
 * Schema::create('activity_logs', function (Blueprint $table) {
 *     $table->id();
 *     $table->uuid('uuid')->unique();
 *     $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
 *     $table->string('module');
 *     $table->string('action');
 *     $table->string('reference_type')->nullable();
 *     $table->unsignedBigInteger('reference_id')->nullable();
 *     $table->json('old_values')->nullable();
 *     $table->json('new_values')->nullable();
 *     $table->string('ip_address', 45)->nullable();
 *     $table->text('user_agent')->nullable();
 *     $table->timestamp('created_at')->useCurrent();
 * 
 *     $table->index(['reference_type', 'reference_id']);
 *     $table->index('user_id');
 * });
 */
trait HasAuditLog
{
    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootHasAuditLog()
    {
        // Log on create
        static::created(function ($model) {
            $model->logActivity('create', null, $model->getAttributes());
        });

        // Log on update
        static::updated(function ($model) {
            if ($model->wasChanged()) {
                $model->logActivity('update', $model->getOriginal(), $model->getChanges());
            }
        });

        // Log on delete
        static::deleted(function ($model) {
            $model->logActivity('delete', $model->getAttributes(), null);
        });
    }

    /**
     * Get the activity logs for this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'reference');
    }

    /**
     * Log an activity.
     *
     * @param string $action
     * @param array|null $oldValues
     * @param array|null $newValues
     * @return ActivityLog|null
     */
    public function logActivity(string $action, ?array $oldValues = null, ?array $newValues = null): ?ActivityLog
    {
        // Skip if we're in console or no user
        if (app()->runningInConsole() || !Auth::check()) {
            return null;
        }

        // Skip if no meaningful changes (for updates)
        if ($action === 'update' && empty($oldValues) && empty($newValues)) {
            return null;
        }

        $user = Auth::user();
        $request = request();

        return ActivityLog::create([
            'uuid' => \Illuminate\Support\Str::uuid()->toString(),
            'user_id' => $user->id ?? null,
            'module' => $this->getAuditModule(),
            'action' => $action,
            'reference_type' => get_class($this),
            'reference_id' => $this->id,
            'old_values' => $this->sanitizeForAudit($oldValues),
            'new_values' => $this->sanitizeForAudit($newValues),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    /**
     * Get the module name for audit log.
     *
     * @return string
     */
    protected function getAuditModule(): string
    {
        // Default to class name without namespace
        $class = class_basename($this);
        
        // Convert to snake_case for module name
        return strtolower(\Illuminate\Support\Str::snake($class));
    }

    /**
     * Sanitize values for audit log (remove sensitive data).
     *
     * @param array|null $values
     * @return array|null
     */
    protected function sanitizeForAudit(?array $values): ?array
    {
        if (empty($values)) {
            return null;
        }

        // Fields to exclude from audit log
        $excludedFields = [
            'password',
            'password_confirmation',
            'remember_token',
            'api_token',
            'secret',
            'token',
        ];

        foreach ($excludedFields as $field) {
            if (isset($values[$field])) {
                $values[$field] = '[REDACTED]';
            }
        }

        return $values;
    }

    /**
     * Get activity logs with specific action.
     *
     * @param string $action
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActivityLogsByAction(string $action)
    {
        return $this->activityLogs()->where('action', $action)->get();
    }

    /**
     * Get recent activity logs.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentActivityLogs(int $limit = 10)
    {
        return $this->activityLogs()->latest()->limit($limit)->get();
    }

    /**
     * Get the user who created this record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Get the user who updated this record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    /**
     * Set the created_by user automatically.
     *
     * @return void
     */
    public static function bootCreatedBy()
    {
        static::creating(function ($model) {
            if (Auth::check() && !$model->created_by) {
                $model->created_by = Auth::id();
            }
        });
    }

    /**
     * Set the updated_by user automatically.
     *
     * @return void
     */
    public static function bootUpdatedBy()
    {
        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }
}
