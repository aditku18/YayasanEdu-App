<?php

namespace App\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Foundation;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class AttendanceFace extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_id',
        'face_encoding',
        'face_image',
        'is_active',
        'liveness_enabled',
        'confidence_threshold',
        'enrolled_by',
        'enrolled_at',
        'last_verified_at',
        'foundation_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'liveness_enabled' => 'boolean',
        'confidence_threshold' => 'decimal:2',
        'enrolled_at' => 'datetime',
        'last_verified_at' => 'datetime',
    ];

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
     * Get decrypted face encoding
     */
    public function getFaceEncodingAttribute($value): ?string
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
     * Set encrypted face encoding
     */
    public function setFaceEncodingAttribute($value): void
    {
        $this->attributes['face_encoding'] = Crypt::encryptString($value);
    }

    /**
     * Get decrypted face image (thumbnail)
     */
    public function getFaceImageAttribute($value): ?string
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
     * Set encrypted face image
     */
    public function setFaceImageAttribute($value): void
    {
        if ($value) {
            $this->attributes['face_image'] = Crypt::encryptString($value);
        }
    }

    /**
     * Update last verified timestamp
     */
    public function updateLastVerified(): void
    {
        $this->update(['last_verified_at' => now()]);
    }

    /**
     * Deactivate this face record
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Scope for active faces
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
}
