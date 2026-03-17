<?php

namespace App\Modules\Attendance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Attendance\Services\FaceRecognitionService;
use App\Modules\Attendance\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaceRecognitionController extends Controller
{
    protected FaceRecognitionService $faceService;
    protected AttendanceService $attendanceService;

    public function __construct(
        FaceRecognitionService $faceService,
        AttendanceService $attendanceService
    ) {
        $this->faceService = $faceService;
        $this->attendanceService = $attendanceService;
    }

    /**
     * Enroll a face
     */
    public function enroll(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'face_encoding' => 'required|string',
            'face_image' => 'nullable|string',
            'device_id' => 'nullable|exists:attendance_devices,id',
            'liveness_enabled' => 'nullable|boolean',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        try {
            $face = $this->faceService->enroll(
                $request->user_id,
                $request->face_encoding,
                $request->face_image,
                $request->device_id,
                $request->user()->id ?? null,
                $foundationId,
                $request->liveness_enabled ?? true
            );

            return response()->json([
                'success' => true,
                'message' => 'Face enrolled successfully',
                'data' => [
                    'id' => $face->id,
                    'enrolled_at' => $face->enrolled_at->toIso8601String(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Verify a face
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'face_encoding' => 'required|string',
            'device_id' => 'nullable|exists:attendance_devices,id',
            'require_liveness' => 'nullable|boolean',
            'liveness_data' => 'nullable|array',
            'clock_in' => 'nullable|boolean',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        // Verify face
        $result = $this->faceService->verify(
            $request->face_encoding,
            $foundationId,
            $request->device_id,
            $request->require_liveness ?? true,
            $request->liveness_data
        );

        if (!$result['verified']) {
            return response()->json([
                'success' => false,
                'message' => $result['error'],
            ], 401);
        }

        // Clock in if requested
        if ($request->clock_in) {
            try {
                $record = $this->attendanceService->clockIn(
                    $result['user_id'],
                    'face',
                    null,
                    $request->device_id,
                    null,
                    null,
                    $foundationId
                );

                $result['attendance_record'] = $record;
            } catch (\Exception $e) {
                // Log error but don't fail verification
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Face verified successfully',
            'data' => [
                'user_id' => $result['user_id'],
                'user' => $result['user'],
                'confidence' => $result['confidence'] ?? null,
                'attendance_record' => $result['attendance_record'] ?? null,
            ],
        ]);
    }

    /**
     * Get user's face
     */
    public function getUserFace(int $userId): JsonResponse
    {
        $face = $this->faceService->getUserFace($userId);

        if (!$face) {
            return response()->json([
                'success' => false,
                'message' => 'No face enrolled for this user',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $face->id,
                'is_active' => $face->is_active,
                'liveness_enabled' => $face->liveness_enabled,
                'confidence_threshold' => $face->confidence_threshold,
                'enrolled_at' => $face->enrolled_at->toIso8601String(),
                'last_verified_at' => $face->last_verified_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * Delete a face enrollment
     */
    public function delete(int $faceId): JsonResponse
    {
        $foundationId = request()->user()->foundation_id ?? 1;

        $success = $this->faceService->delete($faceId, $foundationId);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Face not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Face deleted successfully',
        ]);
    }

    /**
     * Get liveness challenge
     */
    public function getLivenessChallenge(): JsonResponse
    {
        $challenge = $this->faceService->generateLivenessChallenge();

        return response()->json([
            'success' => true,
            'data' => $challenge,
        ]);
    }
}
