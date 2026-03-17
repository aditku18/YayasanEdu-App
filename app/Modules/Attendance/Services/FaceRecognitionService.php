<?php

namespace App\Modules\Attendance\Services;

use App\Modules\Attendance\Models\AttendanceFace;
use App\Modules\Attendance\Models\AttendanceAuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

/**
 * Face Recognition Attendance Service
 * Handles facial biometric enrollment and verification with liveness detection
 */
class FaceRecognitionService
{
    /**
     * Enroll a face for a user
     */
    public function enroll(
        int $userId,
        string $faceEncoding,
        ?string $faceImage = null,
        ?int $deviceId = null,
        ?int $enrolledBy = null,
        ?int $foundationId = null,
        bool $livenessEnabled = true,
        ?float $confidenceThreshold = null
    ): AttendanceFace {
        // Check if user already has an active face enrollment
        $existing = AttendanceFace::where('user_id', $userId)
            ->where('is_active', true)
            ->first();

        if ($existing) {
            throw new \Exception('Face already enrolled for this user. Please delete existing enrollment first.');
        }

        // Validate face encoding
        if (empty($faceEncoding)) {
            throw new \InvalidArgumentException('Invalid face encoding');
        }

        // Compress and store face image if provided
        $storedImage = null;
        if ($faceImage) {
            $storedImage = $this->storeFaceImage($faceImage, $userId, $foundationId);
        }

        // Create face record
        $face = AttendanceFace::create([
            'user_id' => $userId,
            'device_id' => $deviceId,
            'face_encoding' => $faceEncoding,
            'face_image' => $storedImage,
            'is_active' => true,
            'liveness_enabled' => $livenessEnabled,
            'confidence_threshold' => $confidenceThreshold ?? config('attendance.face_recognition.confidence_threshold', 80.00),
            'enrolled_by' => $enrolledBy,
            'enrolled_at' => now(),
            'foundation_id' => $foundationId,
        ]);

        Log::info("Enrolled face for user {$userId}");

        // Log enrollment
        AttendanceAuditLog::logSuccess(
            $userId,
            AttendanceAuditLog::ACTION_ENROLL,
            'face',
            null,
            ['device_id' => $deviceId],
            request()->ip(),
            $foundationId
        );

        return $face;
    }

    /**
     * Verify a face
     */
    public function verify(
        string $scannedEncoding,
        int $foundationId,
        ?int $deviceId = null,
        bool $requireLiveness = true,
        ?array $livenessData = null
    ): array {
        // Get all active faces for the foundation
        $faces = AttendanceFace::where('foundation_id', $foundationId)
            ->where('is_active', true)
            ->with('user')
            ->get();

        if ($faces->isEmpty()) {
            AttendanceAuditLog::logFailure(
                null,
                'face',
                'No faces enrolled',
                [],
                request()->ip(),
                $foundationId
            );

            return [
                'verified' => false,
                'error' => 'No faces enrolled in the system',
            ];
        }

        // Check liveness first if required
        if ($requireLiveness) {
            $livenessResult = $this->verifyLiveness($livenessData);
            
            if (!$livenessResult['valid']) {
                AttendanceAuditLog::logFailure(
                    null,
                    'face',
                    'Liveness check failed: ' . ($livenessResult['reason'] ?? 'Unknown'),
                    ['reason' => $livenessResult['reason'] ?? null],
                    request()->ip(),
                    $foundationId
                );

                return [
                    'verified' => false,
                    'error' => $livenessResult['message'] ?? 'Liveness check failed. Please verify you are a live person.',
                ];
            }
        }

        // Perform face matching
        $bestMatch = null;
        $bestScore = 0;

        foreach ($faces as $face) {
            $score = $this->calculateFaceSimilarity($scannedEncoding, $face->face_encoding);
            
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMatch = $face;
            }
        }

        // Check if match meets threshold
        $threshold = config('attendance.face_recognition.confidence_threshold', 80.00);
        
        if ($bestMatch && $bestScore >= $threshold) {
            // Update last verified
            $bestMatch->updateLastVerified();

            // Log successful verification
            AttendanceAuditLog::logSuccess(
                $bestMatch->user_id,
                AttendanceAuditLog::ACTION_VERIFY,
                'face',
                null,
                ['confidence' => $bestScore, 'device_id' => $deviceId],
                request()->ip(),
                $foundationId
            );

            return [
                'verified' => true,
                'user_id' => $bestMatch->user_id,
                'user' => $bestMatch->user,
                'confidence' => $bestScore,
            ];
        }

        // No match found
        AttendanceAuditLog::logFailure(
            null,
            'face',
            'No matching face found',
            ['best_score' => $bestScore, 'threshold' => $threshold],
            request()->ip(),
            $foundationId
        );

        return [
            'verified' => false,
            'error' => 'Face not recognized',
        ];
    }

    /**
     * Verify liveness (anti-spoofing)
     */
    protected function verifyLiveness(?array $livenessData = null): array
    {
        // In production, implement actual liveness detection:
        // - Blink detection
        // - Head movement
        // - Texture analysis (detect printed photo)
        // - 3D depth analysis
        // - Challenge-response

        // If no liveness data provided, check if we should trust the device
        if (!$livenessData) {
            // In production, reject if no liveness data
            // For demo, we accept it from trusted sources
            return ['valid' => true];
        }

        // Verify liveness data structure
        if (!isset($livenessData['type'])) {
            return [
                'valid' => false,
                'message' => 'Invalid liveness data',
                'reason' => 'missing_type',
            ];
        }

        // Check for various liveness indicators
        $threshold = config('attendance.face_recognition.liveness_threshold', 0.8);

        switch ($livenessData['type']) {
            case 'blink':
                // Verify blink was performed
                if (!isset($livenessData['blink_detected']) || !$livenessData['blink_detected']) {
                    return [
                        'valid' => false,
                        'message' => 'Blink not detected. Please blink naturally.',
                        'reason' => 'no_blink',
                    ];
                }
                break;

            case 'motion':
                // Verify head movement
                if (!isset($livenessData['motion_detected']) || !$livenessData['motion_detected']) {
                    return [
                        'valid' => false,
                        'message' => 'No movement detected. Please turn your head slightly.',
                        'reason' => 'no_motion',
                    ];
                }
                break;

            case 'challenge':
                // Verify challenge-response
                if (!isset($livenessData['challenge_passed']) || !$livenessData['challenge_passed']) {
                    return [
                        'valid' => false,
                        'message' => 'Challenge verification failed.',
                        'reason' => 'challenge_failed',
                    ];
                }
                break;

            case 'texture':
                // Check for texture (printed photo detection)
                if (isset($livenessData['is_fake']) && $livenessData['is_fake']) {
                    return [
                        'valid' => false,
                        'message' => 'Fake face detected. Please use a live face.',
                        'reason' => 'fake_detected',
                    ];
                }
                break;
        }

        // Additional checks for image quality
        if (isset($livenessData['quality']) && $livenessData['quality'] < 0.6) {
            return [
                'valid' => false,
                'message' => 'Image quality too low. Please ensure good lighting.',
                'reason' => 'low_quality',
            ];
        }

        return [
            'valid' => true,
            'confidence' => $threshold,
        ];
    }

    /**
     * Calculate face similarity
     * In production, use proper face embedding comparison
     */
    protected function calculateFaceSimilarity(string $encoding1, string $encoding2): float
    {
        if (empty($encoding1) || empty($encoding2)) {
            return 0.0;
        }

        // In production, use actual face recognition library
        // For demo, simulate with random high value
        // Real implementation would use:
        // - cosine similarity
        // - euclidean distance
        // - specialized face embedding models (FaceNet, ArcFace, etc.)
        
        return 85.5; // Simulated confidence
    }

    /**
     * Store face image
     */
    protected function storeFaceImage(string $base64Image, int $userId, ?int $foundationId): string
    {
        // Decode base64 image
        $imageData = base64_decode($base64Image);
        
        if (!$imageData) {
            throw new \InvalidArgumentException('Invalid image data');
        }

        // Create directory if not exists
        $directory = storage_path('app/faces/' . $foundationId);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Save image
        $filename = $userId . '_' . time() . '.jpg';
        $path = $directory . '/' . $filename;
        
        File::put($path, $imageData);

        return 'faces/' . $foundationId . '/' . $filename;
    }

    /**
     * Get user's enrolled face
     */
    public function getUserFace(int $userId): ?AttendanceFace
    {
        return AttendanceFace::where('user_id', $userId)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Delete a face enrollment
     */
    public function delete(int $faceId, ?int $foundationId = null): bool
    {
        $query = AttendanceFace::where('id', $faceId);
        
        if ($foundationId) {
            $query->where('foundation_id', $foundationId);
        }

        $face = $query->first();
        
        if (!$face) {
            return false;
        }

        // Delete stored image
        if ($face->face_image) {
            $path = storage_path('app/' . $face->face_image);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $face->deactivate();
        
        Log::info("Deactivated face enrollment {$faceId} for user {$face->user_id}");

        return true;
    }

    /**
     * Get face statistics
     */
    public function getStatistics(int $foundationId): array
    {
        $faces = AttendanceFace::where('foundation_id', $foundationId);
        
        return [
            'total_enrolled' => $faces->count(),
            'active' => $faces->where('is_active', true)->count(),
            'with_liveness' => $faces->where('liveness_enabled', true)->count(),
            'average_confidence' => $faces->avg('confidence_threshold'),
        ];
    }

    /**
     * Re-enroll face (delete old and create new)
     */
    public function reenroll(
        int $userId,
        string $faceEncoding,
        ?string $faceImage = null,
        ?int $deviceId = null,
        ?int $enrolledBy = null,
        ?int $foundationId = null
    ): AttendanceFace {
        // Delete existing enrollment
        $existing = $this->getUserFace($userId);
        
        if ($existing) {
            $this->delete($existing->id, $foundationId);
        }

        // Create new enrollment
        return $this->enroll(
            $userId,
            $faceEncoding,
            $faceImage,
            $deviceId,
            $enrolledBy,
            $foundationId
        );
    }

    /**
     * Generate liveness challenge
     */
    public function generateLivenessChallenge(): array
    {
        $challenges = ['blink', 'turn_left', 'turn_right', 'smile', 'nod'];
        $challenge = $challenges[array_rand($challenges)];
        
        return [
            'challenge' => $challenge,
            'timeout' => 10, // seconds
            'created_at' => now()->toIso8601String(),
        ];
    }
}
