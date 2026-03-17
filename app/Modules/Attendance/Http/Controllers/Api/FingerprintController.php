<?php

namespace App\Modules\Attendance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Attendance\Services\FingerprintService;
use App\Modules\Attendance\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FingerprintController extends Controller
{
    protected FingerprintService $fingerprintService;
    protected AttendanceService $attendanceService;

    public function __construct(
        FingerprintService $fingerprintService,
        AttendanceService $attendanceService
    ) {
        $this->fingerprintService = $fingerprintService;
        $this->attendanceService = $attendanceService;
    }

    /**
     * Enroll a fingerprint
     */
    public function enroll(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'template_data' => 'required|string',
            'finger_position' => 'required|in:thumb,index,middle,ring,little,left_thumb,left_index,left_middle,left_ring,left_little',
            'device_id' => 'nullable|exists:attendance_devices,id',
            'liveness_enabled' => 'nullable|boolean',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        try {
            $fingerprint = $this->fingerprintService->enroll(
                $request->user_id,
                $request->template_data,
                $request->finger_position,
                $request->device_id,
                $request->user()->id ?? null,
                $foundationId,
                $request->liveness_enabled ?? true
            );

            return response()->json([
                'success' => true,
                'message' => 'Fingerprint enrolled successfully',
                'data' => [
                    'id' => $fingerprint->id,
                    'finger_position' => $fingerprint->finger_position,
                    'enrolled_at' => $fingerprint->enrolled_at->toIso8601String(),
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
     * Verify a fingerprint
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'template_data' => 'required|string',
            'device_id' => 'nullable|exists:attendance_devices,id',
            'require_liveness' => 'nullable|boolean',
            'liveness_data' => 'nullable|array',
            'clock_in' => 'nullable|boolean',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        // Verify fingerprint
        $result = $this->fingerprintService->verify(
            $request->template_data,
            $foundationId,
            $request->device_id,
            $request->require_liveness ?? true
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
                    'fingerprint',
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
            'message' => 'Fingerprint verified successfully',
            'data' => [
                'user_id' => $result['user_id'],
                'user' => $result['user'],
                'confidence' => $result['confidence'] ?? null,
                'attendance_record' => $result['attendance_record'] ?? null,
            ],
        ]);
    }

    /**
     * Get user's fingerprints
     */
    public function getUserFingerprints(int $userId): JsonResponse
    {
        $fingerprints = $this->fingerprintService->getUserFingerprints($userId);

        return response()->json([
            'success' => true,
            'data' => $fingerprints->map(function ($fp) {
                return [
                    'id' => $fp->id,
                    'finger_position' => $fp->finger_position,
                    'finger_label' => $fp->finger_label,
                    'is_active' => $fp->is_active,
                    'liveness_enabled' => $fp->liveness_enabled,
                    'enrolled_at' => $fp->enrolled_at->toIso8601String(),
                    'last_verified_at' => $fp->last_verified_at?->toIso8601String(),
                ];
            }),
        ]);
    }

    /**
     * Delete a fingerprint
     */
    public function delete(int $fingerprintId): JsonResponse
    {
        $foundationId = request()->user()->foundation_id ?? 1;

        $success = $this->fingerprintService->delete($fingerprintId, $foundationId);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Fingerprint not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Fingerprint deleted successfully',
        ]);
    }
}
