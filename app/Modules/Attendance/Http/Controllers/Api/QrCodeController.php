<?php

namespace App\Modules\Attendance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Attendance\Services\QrCodeService;
use App\Modules\Attendance\Services\AttendanceService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    protected QrCodeService $qrCodeService;
    protected AttendanceService $attendanceService;

    public function __construct(
        QrCodeService $qrCodeService,
        AttendanceService $attendanceService
    ) {
        $this->qrCodeService = $qrCodeService;
        $this->attendanceService = $attendanceService;
    }

    /**
     * Generate a new QR code
     */
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'session_id' => 'nullable|exists:attendance_sessions,id',
            'user_id' => 'nullable|exists:users,id',
            'validity_seconds' => 'nullable|integer|min:60|max:3600',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        $qrCode = $this->qrCodeService->generateQrCode(
            $request->session_id,
            $request->user_id,
            null,
            $foundationId,
            $request->validity_seconds
        );

        return response()->json([
            'success' => true,
            'data' => [
                'qr_code' => $qrCode->code,
                'expires_at' => $qrCode->expires_at->toIso8601String(),
                'qr_image_url' => $this->qrCodeService->generateQrCodeDataUrl($qrCode),
            ],
        ]);
    }

    /**
     * Validate a QR code
     */
    public function validateQrCode(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'session_id' => 'nullable|exists:attendance_sessions,id',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        $result = $this->qrCodeService->validateQrCode(
            $request->code,
            $request->session_id,
            $foundationId
        );

        if (!$result['valid']) {
            return response()->json([
                'success' => false,
                'message' => $result['error'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'QR code is valid',
            'data' => [
                'qr_code' => $result['qr_code'],
            ],
        ]);
    }

    /**
     * Clock in with QR code
     */
    public function clockInWithQr(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'session_id' => 'nullable|exists:attendance_sessions,id',
            'device_id' => 'nullable|exists:attendance_devices,id',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        // Validate the QR code
        $validation = $this->qrCodeService->validateQrCode(
            $request->code,
            $request->session_id,
            $foundationId
        );

        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $validation['error'],
            ], 400);
        }

        $qrCode = $validation['qr_code'];

        try {
            // Clock in the user
            $userId = $qrCode->user_id ?? $request->user()->id;
            
            $record = $this->attendanceService->clockIn(
                $userId,
                'qr_code',
                $qrCode->session_id ?? $request->session_id,
                $request->device_id,
                null,
                null,
                $foundationId
            );

            // Mark QR code as used
            $this->qrCodeService->markAsUsed($qrCode, $userId);

            return response()->json([
                'success' => true,
                'message' => 'Clock in successful',
                'data' => [
                    'record' => $record,
                    'status' => $record->status,
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
     * Clock out with QR code
     */
    public function clockOutWithQr(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'device_id' => 'nullable|exists:attendance_devices,id',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        // Validate the QR code
        $validation = $this->qrCodeService->validateQrCode(
            $request->code,
            null,
            $foundationId
        );

        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $validation['error'],
            ], 400);
        }

        $qrCode = $validation['qr_code'];

        try {
            // Clock out the user
            $userId = $qrCode->user_id ?? $request->user()->id;
            
            $record = $this->attendanceService->clockOut(
                $userId,
                'qr_code',
                $request->device_id,
                null,
                null,
                $foundationId
            );

            // Mark QR code as used
            $this->qrCodeService->markAsUsed($qrCode, $userId);

            return response()->json([
                'success' => true,
                'message' => 'Clock out successful',
                'data' => [
                    'record' => $record,
                    'total_hours' => $record->getTotalHours(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
