<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * LoginLog Model
 * 
 * Tracks user login attempts and sessions for security monitoring.
 */
class LoginLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'login_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'email',
        'ip_address',
        'user_agent',
        'login_at',
        'logout_at',
        'status',
        'failure_reason',
        'device_type',
        'browser',
        'platform',
        'country',
        'city',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
    ];

    /**
     * The user that logged in.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope to get successful logins.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope to get failed login attempts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope to get active sessions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereNull('logout_at');
    }

    /**
     * Scope to get logins for a specific user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get recent logins.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecent($query, int $limit = 50)
    {
        return $query->orderBy('login_at', 'desc')->limit($limit);
    }

    /**
     * Scope to get logins from a specific IP.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $ip
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFromIp($query, string $ip)
    {
        return $query->where('ip_address', $ip);
    }

    /**
     * Get login duration in minutes.
     *
     * @return int|null
     */
    public function getDurationAttribute(): ?int
    {
        if (!$this->logout_at) {
            return null;
        }

        return $this->login_at->diffInMinutes($this->logout_at);
    }

    /**
     * Check if this is a new device.
     *
     * @return bool
     */
    public function isNewDevice(): bool
    {
        return $this->device_type === 'desktop' || $this->device_type === 'mobile';
    }

    /**
     * Log a successful login.
     *
     * @param User $user
     * @param string $ip
     * @param string $userAgent
     * @return self
     */
    public static function logSuccess(User $user, string $ip, string $userAgent): self
    {
        $parsed = self::parseUserAgent($userAgent);

        return self::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'login_at' => now(),
            'status' => 'success',
            'device_type' => $parsed['device_type'],
            'browser' => $parsed['browser'],
            'platform' => $parsed['platform'],
        ]);
    }

    /**
     * Log a failed login attempt.
     *
     * @param string $email
     * @param string $ip
     * @param string $userAgent
     * @param string|null $reason
     * @return self
     */
    public static function logFailed(string $email, string $ip, string $userAgent, ?string $reason = null): self
    {
        $parsed = self::parseUserAgent($userAgent);

        return self::create([
            'email' => $email,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'login_at' => now(),
            'status' => 'failed',
            'failure_reason' => $reason,
            'device_type' => $parsed['device_type'],
            'browser' => $parsed['browser'],
            'platform' => $parsed['platform'],
        ]);
    }

    /**
     * Parse user agent string to get device info.
     *
     * @param string $userAgent
     * @return array
     */
    protected static function parseUserAgent(string $userAgent): array
    {
        $result = [
            'device_type' => 'unknown',
            'browser' => 'unknown',
            'platform' => 'unknown',
        ];

        // Device type detection
        if (preg_match('/mobile|android|iphone|ipad|phone/i', $userAgent)) {
            $result['device_type'] = 'mobile';
        } elseif (preg_match('/tablet|ipad/i', $userAgent)) {
            $result['device_type'] = 'tablet';
        } else {
            $result['device_type'] = 'desktop';
        }

        // Browser detection
        if (preg_match('/chrome/i', $userAgent)) {
            $result['browser'] = 'Chrome';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            $result['browser'] = 'Firefox';
        } elseif (preg_match('/safari/i', $userAgent)) {
            $result['browser'] = 'Safari';
        } elseif (preg_match('/edge/i', $userAgent)) {
            $result['browser'] = 'Edge';
        } elseif (preg_match('/opera|opr/i', $userAgent)) {
            $result['browser'] = 'Opera';
        }

        // Platform detection
        if (preg_match('/windows/i', $userAgent)) {
            $result['platform'] = 'Windows';
        } elseif (preg_match('/mac|os x/i', $userAgent)) {
            $result['platform'] = 'macOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $result['platform'] = 'Linux';
        } elseif (preg_match('/android/i', $userAgent)) {
            $result['platform'] = 'Android';
        } elseif (preg_match('/ios|iphone|ipad/i', $userAgent)) {
            $result['platform'] = 'iOS';
        }

        return $result;
    }

    /**
     * Mark session as logged out.
     *
     * @return void
     */
    public function markAsLoggedOut(): void
    {
        $this->update(['logout_at' => now()]);
    }
}
