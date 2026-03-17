<?php

namespace App\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Foundation;
use App\Models\User;
use App\Modules\Attendance\Models\AttendanceRecord;
use App\Modules\Attendance\Models\AttendanceQrCode;
use App\Modules\Attendance\Models\AttendanceFingerprint;
use App\Modules\Attendance\Models\AttendanceFace;
use App\Modules\Attendance\Models\AttendanceRfid;

class AttendanceDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'ip_address',
        'mac_address',
        'location',
        'is_active',
        'last_sync',
        'config',
        'foundation_id',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_sync' => 'datetime',
        'config' => 'array',
    ];

    // Device type constants
    const TYPE_QR_SCANNER = 'qr_scanner';
    const TYPE_FINGERPRINT = 'fingerprint';
    const TYPE_FACE_SCANNER = 'face_scanner';
    const TYPE_RFID_READER = 'rfid_reader';
    const TYPE_GPS_MOBILE = 'gps_mobile';
    const TYPE_KIOSK = 'kiosk';

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
        return $this->hasMany(AttendanceRecord::class, 'device_id');
    }

    public function qrCodes()
    {
        return $this->hasMany(AttendanceQrCode::class, 'device_id');
    }

    public function fingerprints()
    {
        return $this->hasMany(AttendanceFingerprint::class, 'device_id');
    }

    public function faces()
    {
        return $this->hasMany(AttendanceFace::class, 'device_id');
    }

    public function rfids()
    {
        return $this->hasMany(AttendanceRfid::class, 'device_id');
    }

    /**
     * Update last sync timestamp
     */
    public function updateLastSync(): void
    {
        $this->update(['last_sync' => now()]);
    }

    /**
     * Scope for active devices
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get device type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            self::TYPE_QR_SCANNER => 'QR Scanner',
            self::TYPE_FINGERPRINT => 'Fingerprint Scanner',
            self::TYPE_FACE_SCANNER => 'Face Recognition',
            self::TYPE_RFID_READER => 'RFID Reader',
            self::TYPE_GPS_MOBILE => 'Mobile GPS',
            self::TYPE_KIOSK => 'Kiosk',
            default => 'Unknown',
        };
    }
}
