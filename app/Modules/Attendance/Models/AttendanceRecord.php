<?php

namespace App\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Foundation;
use App\Models\User;
use App\Modules\Attendance\Models\AttendanceSession;
use App\Modules\Attendance\Models\AttendanceDevice;
use App\Modules\Attendance\Models\AttendanceAuditLog;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'device_id',
        'check_in_time',
        'check_out_time',
        'method',
        'status',
        'location_lat',
        'location_long',
        'verification_data',
        'notes',
        'is_duplicate',
        'foundation_id',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'location_lat' => 'decimal:8',
        'location_long' => 'decimal:8',
        'is_duplicate' => 'boolean',
    ];

    // Status constants
    const STATUS_PRESENT = 'present';
    const STATUS_LATE = 'late';
    const STATUS_ABSENT = 'absent';
    const STATUS_EXCUSED = 'excused';
    const STATUS_ON_LEAVE = 'on_leave';

    // Method constants
    const METHOD_QR_CODE = 'qr_code';
    const METHOD_FINGERPRINT = 'fingerprint';
    const METHOD_FACE = 'face';
    const METHOD_RFID = 'rfid';
    const METHOD_GPS = 'gps';
    const METHOD_MANUAL = 'manual';

    public function foundation()
    {
        return $this->belongsTo(Foundation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function session()
    {
        return $this->belongsTo(AttendanceSession::class, 'session_id');
    }

    public function device()
    {
        return $this->belongsTo(AttendanceDevice::class, 'device_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AttendanceAuditLog::class, 'record_id');
    }

    /**
     * Calculate total work hours
     */
    public function getTotalHours(): float
    {
        if (!$this->check_in_time || !$this->check_out_time) {
            return 0;
        }

        return $this->check_in_time->diffInMinutes($this->check_out_time) / 60;
    }

    /**
     * Check if this is a late arrival
     */
    public function isLate(): bool
    {
        return $this->status === self::STATUS_LATE;
    }

    /**
     * Check if this is an early departure
     */
    public function isEarlyDeparture(): bool
    {
        if (!$this->session || !$this->check_out_time) {
            return false;
        }

        return $this->session->isEarlyDeparture($this->check_out_time);
    }

    /**
     * Calculate overtime hours
     */
    public function getOvertimeHours(): float
    {
        if (!$this->session || !$this->check_out_time) {
            return 0;
        }

        $sessionEnd = \Carbon\Carbon::parse($this->session->end_time);
        
        if ($this->check_out_time->greaterThan($sessionEnd)) {
            return $this->check_out_time->diffInMinutes($sessionEnd) / 60;
        }

        return 0;
    }

    /**
     * Scope for today's records
     */
    public function scopeToday($query)
    {
        return $query->whereDate('check_in_time', now()->toDateString());
    }

    /**
     * Scope for specific date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('check_in_time', [$startDate, $endDate]);
    }

    /**
     * Scope for specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for specific foundation
     */
    public function scopeForFoundation($query, $foundationId)
    {
        return $query->where('foundation_id', $foundationId);
    }

    /**
     * Scope for specific status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for specific method
     */
    public function scopeByMethod($query, $method)
    {
        return $query->where('method', $method);
    }
}
