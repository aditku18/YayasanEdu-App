<?php

namespace App\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Foundation;
use App\Models\User;

class AttendanceGeofence extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'center_lat',
        'center_long',
        'radius_meters',
        'is_active',
        'alert_outside_zone',
        'session_id',
        'foundation_id',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'alert_outside_zone' => 'boolean',
        'center_lat' => 'decimal:8',
        'center_long' => 'decimal:8',
        'radius_meters' => 'integer',
    ];

    public function foundation()
    {
        return $this->belongsTo(Foundation::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function session()
    {
        return $this->belongsTo(AttendanceSession::class, 'session_id');
    }

    /**
     * Check if a given location is within this geofence
     */
    public function isWithinZone(float $lat, float $long): bool
    {
        $distance = $this->calculateDistance($lat, $long, $this->center_lat, $this->center_long);
        return $distance <= $this->radius_meters;
    }

    /**
     * Calculate distance between two points using Haversine formula
     */
    public function calculateDistance(float $lat1, float $long1, float $lat2, float $long2): float
    {
        $earthRadius = 6371000; // Earth's radius in meters

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
     * Get distance from center to given location
     */
    public function getDistanceFromCenter(float $lat, float $long): float
    {
        return $this->calculateDistance($lat, $long, $this->center_lat, $this->center_long);
    }

    /**
     * Get the boundary coordinates for this geofence
     */
    public function getBoundaryCoordinates(int $points = 360): array
    {
        $coordinates = [];
        $earthRadius = 6371000;
        $radiusKm = $this->radius_meters / 1000;

        for ($i = 0; $i < $points; $i++) {
            $angle = ($i / $points) * 360;
            $angleRad = deg2rad($angle);

            $lat = asin(
                sin(deg2rad($this->center_lat)) * cos($radiusKm / $earthRadius) +
                cos(deg2rad($this->center_lat)) * sin($radiusKm / $earthRadius) * cos($angleRad)
            );

            $lon = deg2rad($this->center_long) + atan2(
                sin($angleRad) * sin($radiusKm / $earthRadius) * cos(deg2rad($this->center_lat)),
                cos($radiusKm / $earthRadius) - sin(deg2rad($this->center_lat)) * sin($lat)
            );

            $coordinates[] = [
                'lat' => rad2deg($lat),
                'long' => rad2deg($lon),
            ];
        }

        return $coordinates;
    }

    /**
     * Scope for active geofences
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific foundation
     */
    public function scopeForFoundation($query, $foundationId)
    {
        return $query->where('foundation_id', $foundationId);
    }

    /**
     * Scope for geofences with alerts enabled
     */
    public function scopeWithAlerts($query)
    {
        return $query->where('alert_outside_zone', true);
    }
}
