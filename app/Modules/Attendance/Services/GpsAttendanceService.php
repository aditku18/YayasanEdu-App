<?php

namespace App\Modules\Attendance\Services;

use App\Modules\Attendance\Models\AttendanceGeofence;
use App\Modules\Attendance\Models\AttendanceSession;
use App\Modules\Attendance\Models\AttendanceAuditLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * GPS Attendance Service
 * Handles geofence-based attendance tracking
 */
class GpsAttendanceService
{
    /**
     * Create a geofence
     */
    public function createGeofence(
        string $name,
        float $centerLat,
        float $centerLong,
        int $radiusMeters,
        ?int $foundationId = null,
        ?int $sessionId = null,
        ?string $description = null,
        bool $alertOutsideZone = true,
        ?int $createdBy = null
    ): AttendanceGeofence {
        // Validate radius
        $minRadius = config('attendance.gps.min_radius', 10);
        $maxRadius = config('attendance.gps.max_radius', 500);
        
        if ($radiusMeters < $minRadius || $radiusMeters > $maxRadius) {
            throw new \InvalidArgumentException(
                "Radius must be between {$minRadius} and {$maxRadius} meters"
            );
        }

        // Validate coordinates
        if (!$this->isValidCoordinates($centerLat, $centerLong)) {
            throw new \InvalidArgumentException('Invalid coordinates');
        }

        $geofence = AttendanceGeofence::create([
            'name' => $name,
            'description' => $description,
            'center_lat' => $centerLat,
            'center_long' => $centerLong,
            'radius_meters' => $radiusMeters,
            'is_active' => true,
            'alert_outside_zone' => $alertOutsideZone,
            'session_id' => $sessionId,
            'foundation_id' => $foundationId,
            'created_by' => $createdBy,
        ]);

        Log::info("Created geofence {$geofence->id} - {$name} at ({$centerLat}, {$centerLong}) with radius {$radiusMeters}m");

        return $geofence;
    }

    /**
     * Verify if user is within a geofence
     */
    public function verifyLocation(
        float $latitude,
        float $longitude,
        int $foundationId,
        ?int $sessionId = null,
        bool $checkAllActive = true
    ): array {
        // Validate coordinates
        if (!$this->isValidCoordinates($latitude, $longitude)) {
            return [
                'valid' => false,
                'error' => 'Invalid coordinates provided',
            ];
        }

        // Get active geofences for the foundation
        $query = AttendanceGeofence::where('foundation_id', $foundationId)
            ->where('is_active', true);

        if ($sessionId) {
            $query->where(function ($q) use ($sessionId) {
                $q->where('session_id', $sessionId)
                  ->orWhereNull('session_id');
            });
        }

        $geofences = $query->get();

        if ($geofences->isEmpty()) {
            // No geofences defined - allow attendance
            return [
                'valid' => true,
                'geofence' => null,
                'message' => 'No geofence defined - location verified',
            ];
        }

        // Check each geofence
        $insideGeofence = null;
        
        foreach ($geofences as $geofence) {
            if ($geofence->isWithinZone($latitude, $longitude)) {
                $insideGeofence = $geofence;
                break;
            }
        }

        if ($insideGeofence) {
            return [
                'valid' => true,
                'geofence' => $insideGeofence,
                'distance_from_center' => $insideGeofence->getDistanceFromCenter($latitude, $longitude),
                'message' => "Within {$insideGeofence->name}",
            ];
        }

        // Not inside any geofence
        $nearestGeofence = $this->findNearestGeofence($geofences, $latitude, $longitude);

        return [
            'valid' => false,
            'error' => 'You are outside the designated attendance zone',
            'nearest_geofence' => $nearestGeofence ? [
                'name' => $nearestGeofence->name,
                'distance' => $nearestGeofence->getDistanceFromCenter($latitude, $longitude),
                'required_radius' => $nearestGeofence->radius_meters,
            ] : null,
        ];
    }

    /**
     * Verify with alert for out-of-zone attendance
     */
    public function verifyWithAlert(
        float $latitude,
        float $longitude,
        int $foundationId,
        ?int $sessionId = null
    ): array {
        $result = $this->verifyLocation($latitude, $longitude, $foundationId, $sessionId);

        if (!$result['valid'] && config('attendance.gps.alert_outside_zone', true)) {
            // Log the out-of-zone attempt
            AttendanceAuditLog::logFailure(
                null,
                'gps',
                'Out of geofence zone',
                [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'geofence' => $result['nearest_geofence'] ?? null,
                ],
                request()->ip(),
                $foundationId
            );

            // In production, send notification to admin
            // $this->sendAlertNotification($foundationId, $result);
        }

        return $result;
    }

    /**
     * Find the nearest geofence to given coordinates
     */
    protected function findNearestGeofence($geofences, float $lat, float $long): ?AttendanceGeofence
    {
        $nearest = null;
        $minDistance = PHP_FLOAT_MAX;

        foreach ($geofences as $geofence) {
            $distance = $geofence->getDistanceFromCenter($lat, $long);
            
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $nearest = $geofence;
            }
        }

        return $nearest;
    }

    /**
     * Validate coordinates
     */
    protected function isValidCoordinates(float $lat, float $long): bool
    {
        return $lat >= -90 && $lat <= 90 && $long >= -180 && $long <= 180;
    }

    /**
     * Get all geofences for a foundation
     */
    public function getGeofences(int $foundationId, ?bool $activeOnly = true): \Illuminate\Database\Eloquent\Collection
    {
        $query = AttendanceGeofence::where('foundation_id', $foundationId);

        if ($activeOnly) {
            $query->where('is_active', true);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Get geofence by ID
     */
    public function getGeofence(int $geofenceId, ?int $foundationId = null): ?AttendanceGeofence
    {
        $query = AttendanceGeofence::where('id', $geofenceId);
        
        if ($foundationId) {
            $query->where('foundation_id', $foundationId);
        }

        return $query->first();
    }

    /**
     * Update a geofence
     */
    public function updateGeofence(
        int $geofenceId,
        array $data,
        ?int $foundationId = null
    ): AttendanceGeofence {
        $query = AttendanceGeofence::where('id', $geofenceId);
        
        if ($foundationId) {
            $query->where('foundation_id', $foundationId);
        }

        $geofence = $query->firstOrFail();

        // Validate radius if provided
        if (isset($data['radius_meters'])) {
            $minRadius = config('attendance.gps.min_radius', 10);
            $maxRadius = config('attendance.gps.max_radius', 500);
            
            if ($data['radius_meters'] < $minRadius || $data['radius_meters'] > $maxRadius) {
                throw new \InvalidArgumentException(
                    "Radius must be between {$minRadius} and {$maxRadius} meters"
                );
            }
        }

        // Validate coordinates if provided
        if (isset($data['center_lat']) && isset($data['center_long'])) {
            if (!$this->isValidCoordinates($data['center_lat'], $data['center_long'])) {
                throw new \InvalidArgumentException('Invalid coordinates');
            }
        }

        $geofence->update($data);
        
        Log::info("Updated geofence {$geofenceId}");

        return $geofence;
    }

    /**
     * Delete a geofence
     */
    public function deleteGeofence(int $geofenceId, ?int $foundationId = null): bool
    {
        $query = AttendanceGeofence::where('id', $geofenceId);
        
        if ($foundationId) {
            $query->where('foundation_id', $foundationId);
        }

        $geofence = $query->first();
        
        if (!$geofence) {
            return false;
        }

        $geofence->delete();
        
        Log::info("Deleted geofence {$geofenceId}");

        return true;
    }

    /**
     * Toggle geofence active status
     */
    public function toggleStatus(int $geofenceId, ?int $foundationId = null): bool
    {
        $query = AttendanceGeofence::where('id', $geofenceId);
        
        if ($foundationId) {
            $query->where('foundation_id', $foundationId);
        }

        $geofence = $query->first();
        
        if (!$geofence) {
            return false;
        }

        $geofence->update(['is_active' => !$geofence->is_active]);
        
        return true;
    }

    /**
     * Get geofence statistics
     */
    public function getStatistics(int $foundationId): array
    {
        $geofences = AttendanceGeofence::where('foundation_id', $foundationId);
        
        return [
            'total_geofences' => $geofences->count(),
            'active_geofences' => $geofences->where('is_active', true)->count(),
            'with_alerts' => $geofences->where('alert_outside_zone', true)->where('is_active', true)->count(),
            'average_radius' => $geofences->where('is_active', true)->avg('radius_meters'),
            'by_session' => $geofences->whereNotNull('session_id')->groupBy('session_id')->map->count(),
        ];
    }

    /**
     * Calculate distance between two points
     */
    public function calculateDistance(
        float $lat1,
        float $long1,
        float $lat2,
        float $long2
    ): float {
        // Use Haversine formula
        $earthRadius = 6371000; // meters

        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLong = deg2rad($long2 - $long1);

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLong / 2) * sin($deltaLong / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Get geofence boundary coordinates for map display
     */
    public function getGeofenceBoundary(int $geofenceId, ?int $foundationId = null): ?array
    {
        $geofence = $this->getGeofence($geofenceId, $foundationId);
        
        if (!$geofence) {
            return null;
        }

        return [
            'center' => [
                'lat' => $geofence->center_lat,
                'long' => $geofence->center_long,
            ],
            'radius' => $geofence->radius_meters,
            'boundary' => $geofence->getBoundaryCoordinates(),
        ];
    }

    /**
     * Batch verify multiple users (for scheduled check-ins)
     */
    public function batchVerifyLocation(
        array $locations,
        int $foundationId,
        ?int $sessionId = null
    ): array {
        $results = [];

        foreach ($locations as $location) {
            $userId = $location['user_id'] ?? null;
            $lat = $location['latitude'] ?? null;
            $long = $location['longitude'] ?? null;

            if (!$userId || !$lat || !$long) {
                continue;
            }

            $results[$userId] = $this->verifyLocation($lat, $long, $foundationId, $sessionId);
        }

        return $results;
    }
}
