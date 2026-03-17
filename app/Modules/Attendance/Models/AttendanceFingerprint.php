<?php

namespace App\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Foundation;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class AttendanceFingerprint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_id',
        'template_data',
        'finger_position',
        'is_active',
        'liveness_enabled',
        'enrolled_by',
        'enrolled_at',
        'last_verified_at',
        'foundation_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'liveness_enabled' => 'boolean',
        'enrolled_at' => 'datetime',
        'last_verified_at' => 'datetime',
    ];

    // Finger position constants
    const THUMB = 'thumb';
    const INDEX = 'index';
    const MIDDLE = 'middle';
    const RING = 'ring';
    const LITTLE = 'little';
    const LEFT_THUMB = 'left_thumb';
    const LEFT_INDEX = 'left_index';
    const LEFT_MIDDLE = 'left_middle';
    const LEFT_RING = 'left_little';

    public function foundation()
    {
        return $this->belongsTo(Foundation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function device()
    {
        return $this->belongsTo(AttendanceDevice::class, 'device_id');
    }

    public function enrolledBy()
    {
        return $this->belongsTo(User::class, 'enrolled_by');
    }

    /**
     * Get decrypted template data
     */
    public function getTemplateDataAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }
        
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * Set encrypted template data
     */
    public function setTemplateDataAttribute($value): void
    {
        $this->attributes['template_data'] = Crypt::encryptString($value);
    }

    /**
     * Update last verified timestamp
     */
    public function updateLastVerified(): void
    {
        $this->update(['last_verified_at' => now()]);
    }

    /**
     * Deactivate this fingerprint
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Scope for active fingerprints
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get finger position label
     */
    public function getFingerLabelAttribute(): string
    {
        return match($this->finger_position) {
            self::THUMB => 'Right Thumb',
            self::INDEX => 'Right Index',
            self::MIDDLE => 'Right Middle',
            self::RING => 'Right Ring',
            self::LITTLE => 'Right Little',
            self::LEFT_THUMB => 'Left Thumb',
            self::LEFT_INDEX => 'Left Index',
            self::LEFT_MIDDLE => 'Left Middle',
            self::LEFT_RING => 'Left Ring',
            self::LITTLE => 'Left Little',
            default => 'Unknown',
        };
    }
}
