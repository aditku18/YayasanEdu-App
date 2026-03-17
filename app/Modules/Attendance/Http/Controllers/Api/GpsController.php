<?php

namespace App\Modules\Attendance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Attendance\Services\GpsAttendanceService;
use App\Modules\Attendance\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GpsController extends Controller
{
    protected GpsAttendanceService $gpsService;
    protected AttendanceService $attendanceService;

    public function __construct(
        GpsAttendanceService $gpsService,
        AttendanceService $attendanceService
    ) {
        $this->gpsService = $gpsService;
        $this->attendanceService = $attendanceService;
    }

    /**
     * Verify location
     */
    public function verifyLocation(Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'session_id' => 'nullable|exists:attendance_sessions,id',
            'alert_outside' => 'nullable|boolean',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        if ($request->alert_outside) {
            $result = $this->gpsService->verifyWithAlert(
                $request->latitude,
                $request->longitude,
                $foundationId,
                $request->session_id
            );
        } else {
            $result = $this->gpsService->verifyLocation(
                $request->latitude,
                $request->longitude,
                $foundationId,
                $request->session_id
            );
        }

        if (!$result['valid']) {
            return response()->json([
                'success' => false,
                'message' => $result['error'],
                'data' => [
                    'nearest_geofence' => $result['nearest_geofence'] ?? null,
                ],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'data' => [
                'geofence' => $result['geofence'],
                'distance_from_center' => $result['distance_from_center'] ?? null,
            ],
        ]);
    }

    /**
     * Clock in with GPS
     */
    public function clockInWithGps(Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'session_id' => 'nullable|exists:attendance_sessions,id',
            'device_id' => 'nullable|exists:attendance_devices,id',
            'alert_outside' => 'nullable|boolean',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        // First verify location
        $verifyResult = $this->gpsService->verifyWithAlert(
            $request->latitude,
            $request->longitude,
            $foundationId,
            $request->session_id
        );

        if (!$verifyResult['valid']) {
            return response()->json([
                'success' => false,
                'message' => $verifyResult['error'],
                'data' => [
                    'nearest_geofence' => $verifyResult['nearest_geofence'] ?? null,
                ],
            ], 400);
        }

        try {
            $record = $this->attendanceService->clockIn(
                $request->user()->id,
                'gps',
                $request->session_id,
                $request->device_id,
                [
                    'lat' => $request->latitude,
                    'long' => $request->longitude,
                ],
                null,
                $foundationId
            );

            return response()->json([
                'success' => true,
                'message' => 'Clock in successful',
                'data' => [
                    'record' => $record,
                    'status' => $record->status,
                    'geofence' => $verifyResult['geofence']->name ?? null,
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
     * Clock out with GPS
     */
    public function clockOutWithGps(Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'device_id' => 'nullable|exists:attendance_devices,id',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        try {
            $record = $this->attendanceService->clockOut(
                $request->user()->id,
                'gps',
                $request->device_id,
                [
                    'lat' => $request->latitude,
                    'long' => $request->longitude,
                ],
                null,
                $foundationId
            );

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

    /**
     * Get all geofences
     */
    public function getGeofences(Request $request): JsonResponse
    {
        $foundationId = $request->user()->foundation_id ?? 1;
        
        $geofences = $this->gpsService->getGeofences($foundationId, !$request->include_inactive);

        return response()->json([
            'success' => true,
            'data' => $geofences->map(function ($gf) {
                return [
                    'id' => $gf->id,
                    'name' => $gf->name,
                    'description' => $gf->description,
                    'center_lat' => $gf->center_lat,
                    'center_long' => $gf->center_long,
                    'radius_meters' => $gf->radius_meters,
                    'is_active' => $gf->is_active,
                    'alert_outside_zone' => $gf->alert_outside_zone,
                    'session_id' => $gf->session_id,
                ];
            }),
        ]);
    }

    /**
     * Create a geofence
     */
    public function createGeofence(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'center_lat' => 'required|numeric|between:-90,90',
            'center_long' => 'required|numeric|between:-180,180',
            'radius_meters' => 'required|integer|min:10|max:500',
            'session_id' => 'nullable|exists:attendance_sessions,id',
            'alert_outside_zone' => 'nullable|boolean',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        try {
            $geofence = $this->gpsService->createGeofence(
                $request->name,
                $request->center_lat,
                $request->center_long,
                $request->radius_meters,
                $foundationId,
                $request->session_id,
                $request->description,
                $request->alert_outside_zone ?? true,
                $request->user()->id ?? null
            );

            return response()->json([
                'success' => true,
                'message' => 'Geofence created successfully',
                'data' => $geofence,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update a geofence
     */
    public function updateGeofence(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'center_lat' => 'sometimes|numeric|between:-90,90',
            'center_long' => 'sometimes|numeric|between:-180,180',
            'radius_meters' => 'sometimes|integer|min:10|max:500',
            'is_active' => 'sometimes|boolean',
            'alert_outside_zone' => 'sometimes|boolean',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        try {
            $geofence = $this->gpsService->updateGeofence($id, $request->all(), $foundationId);

            return response()->json([
                'success' => true,
                'message' => 'Geofence updated successfully',
                'data' => $geofence,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete a geofence
     */
    public function deleteGeofence(int $id): JsonResponse
    {
        $foundationId = request()->user()->foundation_id ?? 1;

        $success = $this->gpsService->deleteGeofence($id, $foundationId);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Geofence not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Geofence deleted successfully',
        ]);
    }
}
