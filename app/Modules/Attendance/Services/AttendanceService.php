<?php

namespace App\Modules\Attendance\Services;

use App\Modules\Attendance\Models\AttendanceRecord;
use App\Modules\Attendance\Models\AttendanceSession;
use App\Modules\Attendance\Models\AttendanceAuditLog;
use App\Modules\Attendance\Models\AttendanceQrCode;
use App\Models\User;
use App\Models\Foundation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Main Attendance Service
 * Handles core attendance operations and coordinates all attendance methods
 */
class AttendanceService
{
    /**
     * Clock in a user
     */
    public function clockIn(
        int $userId,
        string $method,
        ?int $sessionId = null,
        ?int $deviceId = null,
        ?array $location = null,
        ?string $notes = null,
        ?int $foundationId = null
    ): AttendanceRecord {
        $foundationId = $foundationId ?? $this->getFoundationId($userId);
        
        // Check if already clocked in today
        $existingRecord = $this->getTodayRecord($userId, $foundationId);
        
        if ($existingRecord && $existingRecord->check_in_time && !$existingRecord->check_out_time) {
            throw new \Exception('User already clocked in today');
        }

        // Get active session if not provided
        if (!$sessionId) {
            $session = $this->getActiveSession($foundationId);
            $sessionId = $session?->id;
        }

        // Determine status based on session
        $status = AttendanceRecord::STATUS_PRESENT;
        $checkInTime = now();

        if ($sessionId) {
            $session = AttendanceSession::find($sessionId);
            if ($session && $session->isLate($checkInTime)) {
                $status = AttendanceRecord::STATUS_LATE;
            }
        }

        // Create attendance record
        $record = AttendanceRecord::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'device_id' => $deviceId,
            'check_in_time' => $checkInTime,
            'method' => $method,
            'status' => $status,
            'location_lat' => $location['lat'] ?? null,
            'location_long' => $location['long'] ?? null,
            'notes' => $notes,
            'foundation_id' => $foundationId,
        ]);

        // Log the attendance
        AttendanceAuditLog::logSuccess(
            $userId,
            AttendanceAuditLog::ACTION_CLOCK_IN,
            $method,
            $record->id,
            ['status' => $status],
            request()->ip(),
            $foundationId
        );

        return $record;
    }

    /**
     * Clock out a user
     */
    public function clockOut(
        int $userId,
        string $method,
        ?int $deviceId = null,
        ?array $location = null,
        ?string $notes = null,
        ?int $foundationId = null
    ): AttendanceRecord {
        $foundationId = $foundationId ?? $this->getFoundationId($userId);
        
        // Find today's record without check out
        $record = AttendanceRecord::where('user_id', $userId)
            ->where('foundation_id', $foundationId)
            ->whereDate('check_in_time', now()->toDateString())
            ->whereNull('check_out_time')
            ->first();

        if (!$record) {
            throw new \Exception('No active attendance record found');
        }

        $checkOutTime = now();
        
        // Update the record
        $record->update([
            'check_out_time' => $checkOutTime,
            'device_id' => $deviceId ?? $record->device_id,
            'location_long' => $location['long'] ?? $record->location_long,
            'notes' => $notes ?? $record->notes,
        ]);

        // Check for early departure
        if ($record->session && $record->session->isEarlyDeparture($checkOutTime)) {
            // Could update status or create a separate flag
            Log::info("User {$userId} left early from session {$record->session_id}");
        }

        // Log the attendance
        AttendanceAuditLog::logSuccess(
            $userId,
            AttendanceAuditLog::ACTION_CLOCK_OUT,
            $method,
            $record->id,
            [],
            request()->ip(),
            $foundationId
        );

        return $record->fresh();
    }

    /**
     * Get today's attendance record for a user
     */
    public function getTodayRecord(int $userId, ?int $foundationId = null): ?AttendanceRecord
    {
        $query = AttendanceRecord::where('user_id', $userId)
            ->whereDate('check_in_time', now()->toDateString());

        if ($foundationId) {
            $query->where('foundation_id', $foundationId);
        }

        return $query->first();
    }

    /**
     * Get active attendance session for a foundation
     */
    public function getActiveSession(int $foundationId): ?AttendanceSession
    {
        $currentTime = now()->format('H:i:s');
        
        return AttendanceSession::where('foundation_id', $foundationId)
            ->where('is_active', true)
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>=', $currentTime)
            ->first();
    }

    /**
     * Get attendance history for a user
     */
    public function getUserAttendance(
        int $userId,
        ?Carbon $startDate = null,
        ?Carbon $endDate = null,
        ?int $foundationId = null
    ) {
        $startDate = $startDate ?? now()->startOfMonth();
        $endDate = $endDate ?? now()->endOfMonth();

        $query = AttendanceRecord::where('user_id', $userId)
            ->whereBetween('check_in_time', [$startDate, $endDate]);

        if ($foundationId) {
            $query->where('foundation_id', $foundationId);
        }

        return $query->orderBy('check_in_time', 'desc')->get();
    }

    /**
     * Get attendance statistics for a foundation
     */
    public function getStatistics(
        int $foundationId,
        ?Carbon $startDate = null,
        ?Carbon $endDate = null
    ): array {
        $startDate = $startDate ?? now()->startOfMonth();
        $endDate = $endDate ?? now()->endOfMonth();

        $records = AttendanceRecord::where('foundation_id', $foundationId)
            ->whereBetween('check_in_time', [$startDate, $endDate])
            ->get();

        return [
            'total_records' => $records->count(),
            'present' => $records->where('status', AttendanceRecord::STATUS_PRESENT)->count(),
            'late' => $records->where('status', AttendanceRecord::STATUS_LATE)->count(),
            'absent' => $records->where('status', AttendanceRecord::STATUS_ABSENT)->count(),
            'excused' => $records->where('status', AttendanceRecord::STATUS_EXCUSED)->count(),
            'on_leave' => $records->where('status', AttendanceRecord::STATUS_ON_LEAVE)->count(),
            'by_method' => $records->groupBy('method')->map->count(),
            'average_hours' => $records->avg(function ($record) {
                return $record->getTotalHours();
            }),
        ];
    }

    /**
     * Mark user as absent for a session
     */
    public function markAbsent(int $userId, int $sessionId, ?int $foundationId = null): AttendanceRecord
    {
        $foundationId = $foundationId ?? $this->getFoundationId($userId);

        return AttendanceRecord::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'status' => AttendanceRecord::STATUS_ABSENT,
            'method' => AttendanceRecord::METHOD_MANUAL,
            'notes' => 'Marked as absent by administrator',
            'foundation_id' => $foundationId,
        ]);
    }

    /**
     * Mark user as excused
     */
    public function markExcused(
        int $userId,
        int $sessionId,
        string $reason,
        ?int $foundationId = null
    ): AttendanceRecord {
        $foundationId = $foundationId ?? $this->getFoundationId($userId);

        return AttendanceRecord::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'status' => AttendanceRecord::STATUS_EXCUSED,
            'method' => AttendanceRecord::METHOD_MANUAL,
            'notes' => $reason,
            'foundation_id' => $foundationId,
        ]);
    }

    /**
     * Get foundation ID from user
     */
    protected function getFoundationId(int $userId): int
    {
        $user = User::find($userId);
        
        if (!$user) {
            throw new \Exception('User not found');
        }

        // Assuming user belongs to foundation through some relationship
        // This would need to be adjusted based on your user model
        return $user->foundation_id ?? throw new \Exception('User has no foundation');
    }

    /**
     * Calculate overtime hours
     */
    public function calculateOvertime(AttendanceRecord $record): float
    {
        return $record->getOvertimeHours();
    }

    /**
     * Get late arrivals
     */
    public function getLateArrivals(
        int $foundationId,
        ?Carbon $startDate = null,
        ?Carbon $endDate = null
    ) {
        $startDate = $startDate ?? now()->startOfMonth();
        $endDate = $endDate ?? now()->endOfMonth();

        return AttendanceRecord::where('foundation_id', $foundationId)
            ->where('status', AttendanceRecord::STATUS_LATE)
            ->whereBetween('check_in_time', [$startDate, $endDate])
            ->orderBy('check_in_time', 'desc')
            ->get();
    }

    /**
     * Get early departures
     */
    public function getEarlyDepartures(
        int $foundationId,
        ?Carbon $startDate = null,
        ?Carbon $endDate = null
    ) {
        $startDate = $startDate ?? now()->startOfMonth();
        $endDate = $endDate ?? now()->endOfMonth();

        $records = AttendanceRecord::where('foundation_id', $foundationId)
            ->whereNotNull('check_out_time')
            ->whereBetween('check_in_time', [$startDate, $endDate])
            ->get();

        return $records->filter(function ($record) {
            return $record->isEarlyDeparture();
        });
    }
}
