<?php

namespace App\Modules\Attendance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Modules\Attendance\Services\AttendanceService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceApiController extends Controller
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Clock in
     */
    public function clockIn(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'method' => 'required|in:qr_code,fingerprint,face,rfid,gps,manual',
            'session_id' => 'nullable|exists:attendance_sessions,id',
            'device_id' => 'nullable|exists:attendance_devices,id',
            'location' => 'nullable|array',
            'location.lat' => 'required_with:location|numeric|between:-90,90',
            'location.long' => 'required_with:location|numeric|between:-180,180',
            'notes' => 'nullable|string',
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $foundationId = $user->foundation_id;

            $record = $this->attendanceService->clockIn(
                $request->user_id,
                $request->method,
                $request->session_id,
                $request->device_id,
                $request->location,
                $request->notes,
                $foundationId
            );

            return ApiResponse::success([
                'record' => $record,
                'status' => $record->status,
            ], 'Clock in successful');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }

    /**
     * Clock out
     */
    public function clockOut(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'method' => 'required|in:qr_code,fingerprint,face,rfid,gps,manual',
            'device_id' => 'nullable|exists:attendance_devices,id',
            'location' => 'nullable|array',
            'location.lat' => 'required_with:location|numeric|between:-90,90',
            'location.long' => 'required_with:location|numeric|between:-180,180',
            'notes' => 'nullable|string',
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $foundationId = $user->foundation_id;

            $record = $this->attendanceService->clockOut(
                $request->user_id,
                $request->method,
                $request->device_id,
                $request->location,
                $request->notes,
                $foundationId
            );

            return ApiResponse::success([
                'record' => $record,
                'total_hours' => $record->getTotalHours(),
            ], 'Clock out successful');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }

    /**
     * Get current attendance status
     */
    public function getStatus(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $record = $this->attendanceService->getTodayRecord($user->id, $user->foundation_id);

        return ApiResponse::success([
            'clocked_in' => $record !== null,
            'record' => $record,
        ]);
    }

    /**
     * Get attendance history
     */
    public function getHistory(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $user = User::findOrFail($request->user_id);
        
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $records = $this->attendanceService->getUserAttendance(
            $user->id,
            $startDate,
            $endDate,
            $user->foundation_id
        );

        return ApiResponse::success([
            'records' => $records,
            'pagination' => [
                'total' => $records->count(),
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ],
        ]);
    }

    /**
     * Get today's attendance for all users
     */
    public function getTodayAttendance(Request $request): JsonResponse
    {
        $foundationId = $request->user()->foundation_id ?? 1;
        
        $records = \App\Modules\Attendance\Models\AttendanceRecord::where('foundation_id', $foundationId)
            ->whereDate('check_in_time', now()->toDateString())
            ->with(['user'])
            ->get();

        return ApiResponse::success([
            'records' => $records,
            'total' => $records->count(),
            'present' => $records->whereIn('status', ['present', 'late'])->count(),
        ]);
    }

    /**
     * Mark user as absent
     */
    public function markAbsent(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'session_id' => 'required|exists:attendance_sessions,id',
            'reason' => 'nullable|string',
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            
            $record = $this->attendanceService->markAbsent(
                $request->user_id,
                $request->session_id,
                $user->foundation_id
            );

            return ApiResponse::success($record, 'User marked as absent');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }

    /**
     * Mark user as excused
     */
    public function markExcused(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'session_id' => 'required|exists:attendance_sessions,id',
            'reason' => 'required|string',
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            
            $record = $this->attendanceService->markExcused(
                $request->user_id,
                $request->session_id,
                $request->reason,
                $user->foundation_id
            );

            return response()->json([
                'success' => true,
                'message' => 'User marked as excused',
                'data' => $record,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get attendance sessions
     */
    public function getSessions(Request $request): JsonResponse
    {
        $foundationId = $request->user()->foundation_id ?? 1;
        
        $sessions = \App\Modules\Attendance\Models\AttendanceSession::where('foundation_id', $foundationId)
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sessions,
        ]);
    }

    /**
     * Get active session
     */
    public function getActiveSession(Request $request): JsonResponse
    {
        $foundationId = $request->user()->foundation_id ?? 1;
        
        $session = $this->attendanceService->getActiveSession($foundationId);

        return response()->json([
            'success' => true,
            'data' => $session,
        ]);
    }

    /**
     * Register a device
     */
    public function registerDevice(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:qr_scanner,fingerprint,face_scanner,rfid_reader,gps_mobile,kiosk',
            'ip_address' => 'nullable|ip',
            'mac_address' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        $device = \App\Modules\Attendance\Models\AttendanceDevice::create([
            'name' => $request->name,
            'type' => $request->type,
            'ip_address' => $request->ip_address,
            'mac_address' => $request->mac_address,
            'location' => $request->location,
            'foundation_id' => $foundationId,
            'created_by' => $request->user()->id ?? null,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Device registered successfully',
            'data' => $device,
        ]);
    }

    /**
     * Device heartbeat
     */
    public function deviceHeartbeat(Request $request): JsonResponse
    {
        $request->validate([
            'device_id' => 'required|exists:attendance_devices,id',
        ]);

        $device = \App\Modules\Attendance\Models\AttendanceDevice::findOrFail($request->device_id);
        $device->updateLastSync();

        return response()->json([
            'success' => true,
            'message' => 'Heartbeat received',
        ]);
    }
}
