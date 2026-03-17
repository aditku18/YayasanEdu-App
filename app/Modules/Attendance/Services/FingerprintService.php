<?php

namespace App\Modules\Attendance\Services;

use App\Modules\Attendance\Models\AttendanceFingerprint;
use App\Modules\Attendance\Models\AttendanceAuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Fingerprint Attendance Service
 * Handles biometric fingerprint enrollment and verification
 */
class FingerprintService
{
    /**
     * Enroll a fingerprint for a user
     */
    public function enroll(
        int $userId,
        string $templateData,
        string $fingerPosition,
        ?int $deviceId = null,
        ?int $enrolledBy = null,
        ?int $foundationId = null,
        bool $livenessEnabled = true
    ): AttendanceFingerprint {
        // Validate finger position
        if (!$this->isValidFingerPosition($fingerPosition)) {
            throw new \InvalidArgumentException('Invalid finger position');
        }

        // Check if user already has this finger enrolled
        $existing = AttendanceFingerprint::where('user_id', $userId)
            ->where('finger_position', $fingerPosition)
            ->where('is_active', true)
            ->first();

        if ($existing) {
            throw new \Exception('Fingerprint already enrolled for this finger');
        }

        // Check max fingers per user
        $maxFingers = config('attendance.fingerprint.max_fingers_per_user', 10);
        $currentCount = AttendanceFingerprint::where('user_id', $userId)
            ->where('is_active', true)
            ->count();

        if ($currentCount >= $maxFingers) {
            throw new \Exception("Maximum number of fingerprints ({$maxFingers}) reached");
        }

        // Create fingerprint record
        $fingerprint = AttendanceFingerprint::create([
            'user_id' => $userId,
            'device_id' => $deviceId,
            'template_data' => $templateData,
            'finger_position' => $fingerPosition,
            'is_active' => true,
            'liveness_enabled' => $livenessEnabled,
            'enrolled_by' => $enrolledBy,
            'enrolled_at' => now(),
            'foundation_id' => $foundationId,
        ]);

        Log::info("Enrolled fingerprint for user {$userId}, finger: {$fingerPosition}");

        // Log enrollment
        AttendanceAuditLog::logSuccess(
            $userId,
            AttendanceAuditLog::ACTION_ENROLL,
            'fingerprint',
            null,
            ['finger_position' => $fingerPosition, 'device_id' => $deviceId],
            request()->ip(),
            $foundationId
        );

        return $fingerprint;
    }

    /**
     * Verify a fingerprint
     */
    public function verify(
        string $scannedTemplate,
        int $foundationId,
        ?int $deviceId = null,
        bool $requireLiveness = true
    ): array {
        // Get all active fingerprints for the foundation
        $fingerprints = AttendanceFingerprint::where('foundation_id', $foundationId)
            ->where('is_active', true)
            ->with('user')
            ->get();

        if ($fingerprints->isEmpty()) {
            AttendanceAuditLog::logFailure(
                null,
                'fingerprint',
                'No fingerprints enrolled',
                [],
                request()->ip(),
                $foundationId
            );

            return [
                'verified' => false,
                'error' => 'No fingerprints enrolled in the system',
            ];
        }

        // Perform matching
        foreach ($fingerprints as $fingerprint) {
            // In a real implementation, you would use a biometric SDK
            // Here we simulate the matching process
            $similarity = $this->calculateSimilarity($scannedTemplate, $fingerprint->template_data);
            
            $threshold = config('attendance.fingerprint.liveness_threshold', 0.7);
            
            if ($similarity >= $threshold) {
                // Check liveness if required
                if ($requireLiveness && $fingerprint->liveness_enabled) {
                    // In production, verify liveness detection result
                    $livenessResult = $this->verifyLiveness();
                    
                    if (!$livenessResult['valid']) {
                        AttendanceAuditLog::logFailure(
                            $fingerprint->user_id,
                            'fingerprint',
                            'Liveness check failed',
                            ['similarity' => $similarity],
                            request()->ip(),
                            $foundationId
                        );

                        return [
                            'verified' => false,
                            'error' => 'Liveness check failed. Please use a live finger.',
                        ];
                    }
                }

                // Update last verified
                $fingerprint->updateLastVerified();

                // Log successful verification
                AttendanceAuditLog::logSuccess(
                    $fingerprint->user_id,
                    AttendanceAuditLog::ACTION_VERIFY,
                    'fingerprint',
                    null,
                    ['similarity' => $similarity, 'device_id' => $deviceId],
                    request()->ip(),
                    $foundationId
                );

                return [
                    'verified' => true,
                    'user_id' => $fingerprint->user_id,
                    'user' => $fingerprint->user,
                    'finger_position' => $fingerprint->finger_position,
                    'confidence' => $similarity,
                ];
            }
        }

        // No match found
        AttendanceAuditLog::logFailure(
            null,
            'fingerprint',
            'No matching fingerprint found',
            [],
            request()->ip(),
            $foundationId
        );

        return [
            'verified' => false,
            'error' => 'Fingerprint not recognized',
        ];
    }

    /**
     * Verify liveness (anti-spoofing)
     * In production, this would use actual liveness detection
     */
    protected function verifyLiveness(): array
    {
        // Get liveness data from request (in production, this would be from the device)
        $livenessData = request()->input('liveness_data');
        
        if (!$livenessData) {
            // If no liveness data provided, assume it's from a trusted device
            return ['valid' => true];
        }

        // In production, verify liveness using:
        // - Challenge-response (random patterns)
        // - Pulse detection
        // - Texture analysis
        // - Motion analysis

        // Simulate liveness check
        $threshold = config('attendance.fingerprint.liveness_threshold', 0.7);
        
        // For demo purposes, always return valid
        // In production, implement actual liveness detection
        return ['valid' => true, 'confidence' => $threshold];
    }

    /**
     * Calculate similarity between two templates
     * In production, use proper biometric matching algorithm
     */
    protected function calculateSimilarity(string $template1, string $template2): float
    {
        // In production, use SDK-specific comparison
        // Here we simulate with a simple comparison
        
        if (empty($template1) || empty($template2)) {
            return 0.0;
        }

        // For demo: return random high value if templates exist
        // In production, implement proper template matching
        return 0.85; // Simulated high confidence
    }

    /**
     * Check if finger position is valid
     */
    protected function isValidFingerPosition(string $position): bool
    {
        $validPositions = [
            AttendanceFingerprint::THUMB,
            AttendanceFingerprint::INDEX,
            AttendanceFingerprint::MIDDLE,
            AttendanceFingerprint::RING,
            AttendanceFingerprint::LITTLE,
            AttendanceFingerprint::LEFT_THUMB,
            AttendanceFingerprint::LEFT_INDEX,
            AttendanceFingerprint::LEFT_MIDDLE,
            AttendanceFingerprint::LEFT_RING,
            'left_little',
        ];

        return in_array($position, $validPositions);
    }

    /**
     * Get user's enrolled fingerprints
     */
    public function getUserFingerprints(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return AttendanceFingerprint::where('user_id', $userId)
            ->where('is_active', true)
            ->orderBy('enrolled_at', 'desc')
            ->get();
    }

    /**
     * Delete a fingerprint
     */
    public function delete(int $fingerprintId, ?int $foundationId = null): bool
    {
        $query = AttendanceFingerprint::where('id', $fingerprintId);
        
        if ($foundationId) {
            $query->where('foundation_id', $foundationId);
        }

        $fingerprint = $query->first();
        
        if (!$fingerprint) {
            return false;
        }

        $fingerprint->deactivate();
        
        Log::info("Deactivated fingerprint {$fingerprintId} for user {$fingerprint->user_id}");

        return true;
    }

    /**
     * Get fingerprint statistics
     */
    public function getStatistics(int $foundationId): array
    {
        $fingerprints = AttendanceFingerprint::where('foundation_id', $foundationId);
        
        return [
            'total_enrolled' => $fingerprints->count(),
            'active' => $fingerprints->where('is_active', true)->count(),
            'with_liveness' => $fingerprints->where('liveness_enabled', true)->count(),
            'by_finger' => $fingerprints->where('is_active', true)
                ->groupBy('finger_position')
                ->map->count(),
        ];
    }

    /**
     * Enable/disable liveness detection for a fingerprint
     */
    public function setLiveness(int $fingerprintId, bool $enabled, ?int $foundationId = null): bool
    {
        $query = AttendanceFingerprint::where('id', $fingerprintId);
        
        if ($foundationId) {
            $query->where('foundation_id', $foundationId);
        }

        return $query->update(['liveness_enabled' => $enabled]);
    }
}
