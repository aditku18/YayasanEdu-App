<?php

namespace App\Modules\CBT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CbtCertificateIssued extends Model
{
    use SoftDeletes;

    protected $table = 'cbt_certificate_issued';
    
    protected $fillable = [
        'certificate_id',
        'user_id',
        'course_id',
        'certificate_number',
        'issued_at',
        'expires_at',
        'download_url',
        'verification_code'
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    /**
     * Get the certificate template.
     */
    public function certificate(): BelongsTo
    {
        return $this->belongsTo(CbtCertificate::class, 'certificate_id');
    }

    /**
     * Get the user that owns the certificate.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Get the course.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(CbtCourse::class, 'course_id');
    }

    /**
     * Generate unique certificate number.
     */
    public static function generateCertificateNumber(): string
    {
        $prefix = 'CERT';
        $timestamp = now()->format('Ymd');
        $random = strtoupper(\Str::random(6));
        
        return "{$prefix}-{$timestamp}-{$random}";
    }

    /**
     * Generate unique verification code.
     */
    public static function generateVerificationCode(): string
    {
        return hash('sha256', \Str::random(64));
    }

    /**
     * Check if certificate is expired.
     */
    public function isExpired(): bool
    {
        if ($this->expires_at === null) {
            return false;
        }
        
        return now()->greaterThan($this->expires_at);
    }

    /**
     * Check if certificate is valid.
     */
    public function isValid(): bool
    {
        return !$this->isExpired();
    }

    /**
     * Verify certificate by verification code.
     */
    public static function verify(string $verificationCode): ?self
    {
        return self::where('verification_code', $verificationCode)->first();
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to filter by course.
     */
    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    /**
     * Scope a query to get valid certificates.
     */
    public function scopeValid($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope a query to get expired certificates.
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '<=', now());
    }
}
