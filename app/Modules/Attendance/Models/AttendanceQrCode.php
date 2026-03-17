<?php

namespace App\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Foundation;
use App\Models\User;

class AttendanceQrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'session_id',
        'user_id',
        'device_id',
        'expires_at',
        'is_used',
        'used_at',
        'ip_address',
        'user_agent',
        'foundation_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'is_used' => 'boolean',
    ];

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

    /**
     * Check if the QR code is valid
     */
    public function isValid(): bool
    {
        return !$this->is_used && $this->expires_at->isFuture();
    }

    /**
     * Mark the QR code as used
     */
    public function markAsUsed(?int $userId = null, ?string $ipAddress = null): void
    {
        $this->update([
            'is_used' => true,
            'used_at' => now(),
            'user_id' => $userId ?? $this->user_id,
            'ip_address' => $ipAddress ?? $this->ip_address,
        ]);
    }

    /**
     * Scope for unused codes
     */
    public function scopeUnused($query)
    {
        return $query->where('is_used', false);
    }

    /**
     * Scope for valid codes
     */
    public function scopeValid($query)
    {
        return $query->unused()->where('expires_at', '>', now());
    }

    /**
     * Scope for specific session
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Generate a unique QR code
     */
    public static function generateCode(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }
}
