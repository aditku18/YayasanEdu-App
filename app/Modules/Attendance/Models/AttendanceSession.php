<?php

namespace App\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Foundation;
use App\Models\User;
use App\Modules\Attendance\Models\AttendanceRecord;
use App\Modules\Attendance\Models\AttendanceQrCode;
use App\Modules\Attendance\Models\AttendanceGeofence;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'grace_period',
        'required_method',
        'is_active',
        'foundation_id',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'is_active' => 'boolean',
        'grace_period' => 'integer',
    ];

    public function foundation()
    {
        return $this->belongsTo(Foundation::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function records()
    {
        return $this->hasMany(AttendanceRecord::class, 'session_id');
    }

    public function qrCodes()
    {
        return $this->hasMany(AttendanceQrCode::class, 'session_id');
    }

    public function geofences()
    {
        return $this->hasMany(AttendanceGeofence::class, 'session_id');
    }

    /**
     * Check if a user is late for this session
     */
    public function isLate($checkInTime): bool
    {
        $sessionStartTime = \Carbon\Carbon::parse($this->start_time);
        $graceEndTime = $sessionStartTime->copy()->addMinutes($this->grace_period);
        
        return $checkInTime->greaterThan($graceEndTime);
    }

    /**
     * Check if a user left early for this session
     */
    public function isEarlyDeparture($checkOutTime): bool
    {
        $sessionEndTime = \Carbon\Carbon::parse($this->end_time);
        
        return $checkOutTime->lessThan($sessionEndTime);
    }

    /**
     * Get attendance for a specific date
     */
    public function getAttendanceForDate($date)
    {
        return $this->records()->whereDate('check_in_time', $date)->get();
    }
}
