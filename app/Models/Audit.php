<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Audit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_role',
        'action',
        'module',
        'table_name',
        'record_id',
        'url',
        'method',
        'ip_address',
        'user_agent',
        'session_id',
        'old_values',
        'new_values',
        'status',
        'description',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include successful audits
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope a query to only include failed audits
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope a query to filter by action type
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope a query to filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get the action icon based on action type
     */
    public function getActionIconAttribute()
    {
        return match($this->action) {
            'create' => 'heroicon-o-plus-circle',
            'update' => 'heroicon-o-pencil',
            'delete' => 'heroicon-o-trash',
            'login' => 'heroicon-o-arrow-right-on-rectangle',
            'logout' => 'heroicon-o-arrow-left-on-rectangle',
            'view' => 'heroicon-o-eye',
            'download' => 'heroicon-o-arrow-down-tray',
            'upload' => 'heroicon-o-arrow-up-tray',
            default => 'heroicon-o-document-text',
        };
    }

    /**
     * Get the action color based on action type
     */
    public function getActionColorAttribute()
    {
        return match($this->action) {
            'create' => 'green',
            'update' => 'blue',
            'delete' => 'red',
            'login' => 'purple',
            'logout' => 'gray',
            'view' => 'indigo',
            'download' => 'emerald',
            'upload' => 'orange',
            default => 'slate',
        };
    }

    /**
     * Get the status icon based on status
     */
    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'success' => 'heroicon-o-check-circle',
            'failed' => 'heroicon-o-x-circle',
            'pending' => 'heroicon-o-clock',
            default => 'heroicon-o-question-mark-circle',
        };
    }

    /**
     * Get the status color based on status
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'success' => 'emerald',
            'failed' => 'red',
            'pending' => 'yellow',
            default => 'slate',
        };
    }

    /**
     * Get formatted description
     */
    public function getFormattedDescriptionAttribute()
    {
        if ($this->description) {
            return $this->description;
        }

        $userName = $this->user_name ?? 'System';
        $action = ucfirst($this->action ?? 'performed action');
        $module = $this->module ?? 'system';

        return "{$userName} {$action} on {$module}";
    }

    /**
     * Create an audit log entry
     */
    public static function log(array $data)
    {
        $user = auth()->user();
        
        return self::create(array_merge([
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_email' => $user?->email,
            'user_role' => $user?->roles->first()?->name ?? 'user',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'status' => 'success',
        ], $data));
    }

    /**
     * Log a successful action
     */
    public static function logSuccess(array $data)
    {
        return self::log(array_merge($data, ['status' => 'success']));
    }

    /**
     * Log a failed action
     */
    public static function logFailure(array $data)
    {
        return self::log(array_merge($data, ['status' => 'failed']));
    }

    /**
     * Log a login attempt
     */
    public static function logLogin($user = null, $status = 'success')
    {
        return self::log([
            'action' => 'login',
            'module' => 'auth',
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_email' => $user?->email,
            'status' => $status,
            'description' => $status === 'success' 
                ? 'User logged in successfully' 
                : 'Failed login attempt',
        ]);
    }

    /**
     * Log a logout action
     */
    public static function logLogout($user = null)
    {
        return self::logSuccess([
            'action' => 'logout',
            'module' => 'auth',
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_email' => $user?->email,
            'description' => 'User logged out',
        ]);
    }

    /**
     * Log a create action
     */
    public static function logCreate($model, $description = null)
    {
        return self::logSuccess([
            'action' => 'create',
            'module' => class_basename($model),
            'table_name' => $model->getTable(),
            'record_id' => $model->id,
            'new_values' => $model->toArray(),
            'description' => $description ?? "Created new " . class_basename($model),
        ]);
    }

    /**
     * Log an update action
     */
    public static function logUpdate($model, $oldValues = null, $description = null)
    {
        return self::logSuccess([
            'action' => 'update',
            'module' => class_basename($model),
            'table_name' => $model->getTable(),
            'record_id' => $model->id,
            'old_values' => $oldValues,
            'new_values' => $model->toArray(),
            'description' => $description ?? "Updated " . class_basename($model),
        ]);
    }

    /**
     * Log a delete action
     */
    public static function logDelete($model, $description = null)
    {
        return self::logSuccess([
            'action' => 'delete',
            'module' => class_basename($model),
            'table_name' => $model->getTable(),
            'record_id' => $model->id,
            'old_values' => $model->toArray(),
            'description' => $description ?? "Deleted " . class_basename($model),
        ]);
    }
}
