<?php

namespace App\Modules\Attendance\Services;

use App\Modules\Attendance\Models\AttendanceQrCode;
use App\Modules\Attendance\Models\AttendanceSession;
use App\Modules\Attendance\Models\AttendanceAuditLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * QR Code Attendance Service
 * Handles QR code generation, validation, and verification
 */
class QrCodeService
{
    /**
     * Generate a new QR code for attendance
     */
    public function generateQrCode(
        ?int $sessionId = null,
        ?int $userId = null,
        ?int $deviceId = null,
        ?int $foundationId = null,
        ?int $validitySeconds = null
    ): AttendanceQrCode {
        $validitySeconds = $validitySeconds ?? config('attendance.qr_code.validity_seconds', 300);
        
        // Generate unique code
        $code = $this->generateUniqueCode();
        
        // Create QR code record
        $qrCode = AttendanceQrCode::create([
            'code' => $code,
            'session_id' => $sessionId,
            'user_id' => $userId,
            'device_id' => $deviceId,
            'expires_at' => now()->addSeconds($validitySeconds),
            'is_used' => false,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'foundation_id' => $foundationId,
        ]);

        Log::info("Generated QR code for foundation {$foundationId}, expires at {$qrCode->expires_at}");

        return $qrCode;
    }

    /**
     * Generate unique cryptographic code
     */
    protected function generateUniqueCode(): string
    {
        $length = config('attendance.qr_code.code_length', 32);
        $algorithm = config('attendance.qr_code.algorithm', 'sha256');
        
        // Generate random bytes
        $random = random_bytes(32);
        
        // Add timestamp to ensure uniqueness
        $data = $random . time() . Str::random(16);
        
        // Hash the data
        return hash($algorithm, $data);
    }

    /**
     * Validate and verify a QR code
     */
    public function validateQrCode(
        string $code,
        ?int $sessionId = null,
        ?int $foundationId = null
    ): array {
        // Find the QR code
        $qrCode = AttendanceQrCode::where('code', $code)->first();

        if (!$qrCode) {
            return [
                'valid' => false,
                'error' => 'Invalid QR code',
            ];
        }

        // Check if already used
        if ($qrCode->is_used) {
            AttendanceAuditLog::logFailure(
                $qrCode->user_id,
                'qr_code',
                'QR code already used',
                ['code_id' => $qrCode->id],
                request()->ip(),
                $qrCode->foundation_id
            );

            return [
                'valid' => false,
                'error' => 'QR code has already been used',
            ];
        }

        // Check if expired
        if ($qrCode->expires_at->isPast()) {
            AttendanceAuditLog::logFailure(
                $qrCode->user_id,
                'qr_code',
                'QR code expired',
                ['code_id' => $qrCode->id, 'expires_at' => $qrCode->expires_at],
                request()->ip(),
                $qrCode->foundation_id
            );

            return [
                'valid' => false,
                'error' => 'QR code has expired',
            ];
        }

        // Check foundation
        if ($foundationId && $qrCode->foundation_id !== $foundationId) {
            return [
                'valid' => false,
                'error' => 'QR code is not valid for this organization',
            ];
        }

        // Check session if provided
        if ($sessionId && $qrCode->session_id !== $sessionId) {
            return [
                'valid' => false,
                'error' => 'QR code is not valid for this session',
            ];
        }

        // Rate limiting check
        $rateLimit = config('attendance.qr_code.rate_limit', 10);
        $recentAttempts = AttendanceQrCode::where('code', $code)
            ->where('created_at', '>', now()->subMinutes(1))
            ->count();

        if ($recentAttempts >= $rateLimit) {
            return [
                'valid' => false,
                'error' => 'Too many attempts. Please try again later',
            ];
        }

        return [
            'valid' => true,
            'qr_code' => $qrCode,
        ];
    }

    /**
     * Mark QR code as used
     */
    public function markAsUsed(AttendanceQrCode $qrCode, ?int $userId = null): void
    {
        $qrCode->markAsUsed($userId, request()->ip());
    }

    /**
     * Generate QR code for a specific user
     */
    public function generateForUser(
        int $userId,
        ?int $sessionId = null,
        ?int $foundationId = null
    ): AttendanceQrCode {
        return $this->generateQrCode($sessionId, $userId, null, $foundationId);
    }

    /**
     * Generate QR code for a session
     */
    public function generateForSession(
        int $sessionId,
        ?int $foundationId = null
    ): AttendanceQrCode {
        return $this->generateQrCode($sessionId, null, null, $foundationId);
    }

    /**
     * Get active QR codes for a session
     */
    public function getActiveCodesForSession(int $sessionId): \Illuminate\Database\Eloquent\Collection
    {
        return AttendanceQrCode::where('session_id', $sessionId)
            ->valid()
            ->get();
    }

    /**
     * Get QR code statistics
     */
    public function getStatistics(int $foundationId, ?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? now()->startOfDay();
        $endDate = $endDate ?? now()->endOfDay();

        $codes = AttendanceQrCode::where('foundation_id', $foundationId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        return [
            'total_generated' => $codes->count(),
            'total_used' => $codes->where('is_used', true)->count(),
            'total_expired' => $codes->where('is_used', false)
                ->where('expires_at', '<', now())->count(),
            'total_active' => $codes->valid()->count(),
        ];
    }

    /**
     * Clean up expired QR codes
     */
    public function cleanupExpiredCodes(): int
    {
        return AttendanceQrCode::where('is_used', false)
            ->where('expires_at', '<', now())
            ->delete();
    }

    /**
     * Generate QR code data URL for display
     */
    public function generateQrCodeDataUrl(AttendanceQrCode $qrCode): string
    {
        // Use a simple QR code generator
        // In production, you would use a library like bacon/bacon-qr-code
        $data = $qrCode->code;
        
        // Generate QR code as base64 PNG using Google Charts API (or local library)
        $url = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . urlencode($data) . '&choe=UTF-8';
        
        return $url;
    }

    /**
     * Invalidate all QR codes for a session
     */
    public function invalidateSessionCodes(int $sessionId): int
    {
        return AttendanceQrCode::where('session_id', $sessionId)
            ->where('is_used', false)
            ->update([
                'is_used' => true,
                'used_at' => now(),
            ]);
    }
}
