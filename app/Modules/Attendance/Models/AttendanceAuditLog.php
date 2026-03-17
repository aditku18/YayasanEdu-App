<?php

namespace App\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Foundation;
use App\Models\User;

class AttendanceAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'record_id',
        'action',
        'method',
        'details',
        'ip_address',
        'user_agent',
        'device_info',
        'is_successful',
        'failure_reason',
        'foundation_id',
    ];

    protected $casts = [
        'details' => 'array',
        'is_successful' => 'boolean',
    ];

    // Action constants
    const ACTION_CLOCK_IN = 'clock_in';
    const ACTION_CLOCK_OUT = 'clock_out';
    const ACTION_FAILED_ATTEMPT = 'failed_attempt';
    const ACTION_DUPLICATE = 'duplicate';
    const ACTION_MANUAL_ENTRY = 'manual_entry';
    const ACTION_ENROLL = 'enroll';
    const ACTION_VERIFY = 'verify';
    const ACTION_DEVICE_SYNC = 'device_sync';

    public function foundation()
    {
        return $this->belongsTo(Foundation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function record()
    {
        return $this->belongsTo(AttendanceRecord::class, 'record_id');
    }

    /**
     * Scope for successful attempts
     */
    public function scopeSuccessful($query)
    {
        return $query->where('is_successful', true);
    }

    /**
     * Scope for failed attempts
     */
    public function scopeFailed($query)
    {
        return $query->where('is_successful', false);
    }

    /**
     * Scope for specific action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for specific method
     */
    public function scopeByMethod($query, $method)
    {
        return $query->where('method', $method);
    }

    /**
     * Scope for specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for specific date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for today
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', now()->toDateString());
    }

    /**
     * Log a successful attendance event
     */
    public static function logSuccess(
        ?int $userId,
        string $action,
        string $method,
        ?int $recordId = null,
        array $details = [],
        ?string $ipAddress = null,
        ?int $foundationId = null
    ): self {
        return static::create([
            'user_id' => $userId,
            'record_id' => $recordId,
            'action' => $action,
            'method' => $method,
            'details' => $details,
            'ip_address' => $ipAddress,
            'user_agent' => request()->userAgent() ?? null,
            'is_successful' => true,
            'foundation_id' => $foundationId,
        ]);
    }

    /**
     * Log a failed attendance attempt
     */
    public static function logFailure(
        ?int $userId,
        string $method,
        string $failureReason,
        array $details = [],
        ?string $ipAddress = null,
        ?int $foundationId = null
    ): self {
        return static::create([
            'user_id' => $userId,
            'action' => self::ACTION_FAILED_ATTEMPT,
            'method' => $method,
            'details' => $details,
            'ip_address' => $ipAddress,
            'user_agent' => request()->userAgent() ?? null,
            'is_successful' => false,
            'failure_reason' => $failureReason,
            'foundation_id' => $foundationId,
        ]);
    }
}
