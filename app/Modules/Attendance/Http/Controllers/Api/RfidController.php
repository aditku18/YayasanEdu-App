<?php

namespace App\Modules\Attendance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Attendance\Services\RfidService;
use App\Modules\Attendance\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RfidController extends Controller
{
    protected RfidService $rfidService;
    protected AttendanceService $attendanceService;

    public function __construct(
        RfidService $rfidService,
        AttendanceService $attendanceService
    ) {
        $this->rfidService = $rfidService;
        $this->attendanceService = $attendanceService;
    }

    /**
     * Enroll an RFID card
     */
    public function enroll(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'card_number' => 'required|string',
            'card_data' => 'nullable|string',
            'card_type' => 'nullable|string',
            'device_id' => 'nullable|exists:attendance_devices,id',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        try {
            $rfid = $this->rfidService->enroll(
                $request->user_id,
                $request->card_number,
                $request->card_data,
                $request->card_type,
                $request->device_id,
                $request->user()->id ?? null,
                $foundationId
            );

            return response()->json([
                'success' => true,
                'message' => 'RFID card enrolled successfully',
                'data' => [
                    'id' => $rfid->id,
                    'card_number' => $rfid->card_number,
                    'card_type' => $rfid->card_type,
                    'enrolled_at' => $rfid->enrolled_at->toIso8601String(),
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
     * Verify an RFID card
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'card_number' => 'required|string',
            'device_id' => 'nullable|exists:attendance_devices,id',
            'clock_in' => 'nullable|boolean',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        // Verify RFID card
        $result = $this->rfidService->verify(
            $request->card_number,
            $foundationId,
            $request->device_id
        );

        if (!$result['verified']) {
            // Check if auto-enrollment is requested
            if (isset($result['action']) && $result['action'] === 'enroll') {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                    'action' => 'enroll',
                ], 404);
            }

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
                    'rfid',
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
            'message' => 'RFID card verified successfully',
            'data' => [
                'user_id' => $result['user_id'],
                'user' => $result['user'],
                'card_type' => $result['card_type'],
                'attendance_record' => $result['attendance_record'] ?? null,
            ],
        ]);
    }

    /**
     * Get user's RFID cards
     */
    public function getUserCards(int $userId): JsonResponse
    {
        $cards = $this->rfidService->getUserCards($userId);

        return response()->json([
            'success' => true,
            'data' => $cards->map(function ($card) {
                return [
                    'id' => $card->id,
                    'card_number' => $card->card_number,
                    'card_type' => $card->card_type,
                    'is_active' => $card->is_active,
                    'is_blacklisted' => $card->is_blacklisted,
                    'enrolled_at' => $card->enrolled_at->toIso8601String(),
                    'last_used_at' => $card->last_used_at?->toIso8601String(),
                ];
            }),
        ]);
    }

    /**
     * Delete an RFID card
     */
    public function delete(int $rfidId): JsonResponse
    {
        $foundationId = request()->user()->foundation_id ?? 1;

        $success = $this->rfidService->deactivate($rfidId, $foundationId);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'RFID card not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'RFID card deactivated successfully',
        ]);
    }

    /**
     * Blacklist an RFID card
     */
    public function blacklist(int $rfidId): JsonResponse
    {
        $foundationId = request()->user()->foundation_id ?? 1;

        $success = $this->rfidService->blacklist($rfidId, $foundationId);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'RFID card not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'RFID card blacklisted successfully',
        ]);
    }

    /**
     * Unblacklist an RFID card
     */
    public function unblacklist(int $rfidId): JsonResponse
    {
        $foundationId = request()->user()->foundation_id ?? 1;

        $success = $this->rfidService->unblacklist($rfidId, $foundationId);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'RFID card not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'RFID card unblacklisted successfully',
        ]);
    }
}
