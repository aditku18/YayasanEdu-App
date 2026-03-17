<?php

namespace App\Modules\Attendance\Services;

use App\Modules\Attendance\Models\AttendanceRecord;
use App\Modules\Attendance\Models\AttendanceSession;
use App\Modules\Attendance\Models\AttendanceQrCode;
use App\Modules\Attendance\Models\AttendanceFingerprint;
use App\Modules\Attendance\Models\AttendanceFace;
use App\Modules\Attendance\Models\AttendanceRfid;
use App\Modules\Attendance\Models\AttendanceGeofence;
use App\Modules\Attendance\Models\AttendanceAuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Attendance Backup Service
 * Handles data backup and recovery for attendance system
 */
class BackupService
{
    /**
     * Create a full backup of attendance data
     */
    public function createBackup(int $foundationId, ?string $name = null): string
    {
        $name = $name ?? 'attendance_backup_' . now()->format('Y_m_d_H_i_s');
        $backupPath = 'backups/' . $foundationId . '/' . $name;

        // Ensure directory exists
        Storage::makeDirectory($backupPath);

        $tables = [
            'attendance_records',
            'attendance_sessions',
            'attendance_devices',
            'attendance_qr_codes',
            'attendance_fingerprints',
            'attendance_faces',
            'attendance_rfids',
            'attendance_geofences',
            'attendance_audit_logs',
            'attendance_reports',
        ];

        $backupData = [];

        foreach ($tables as $table) {
            $data = DB::table($table)
                ->where('foundation_id', $foundationId)
                ->get()
                ->toArray();

            $backupData[$table] = $data;
            
            Log::info("Backed up " . count($data) . " records from {$table}");
        }

        // Save backup file
        $filePath = $backupPath . '/data.json';
        Storage::put($filePath, json_encode($backupData, JSON_PRETTY_PRINT));

        // Save metadata
        $metadata = [
            'foundation_id' => $foundationId,
            'name' => $name,
            'created_at' => now()->toIso8601String(),
            'tables' => array_keys($backupData),
            'record_counts' => array_map('count', $backupData),
        ];

        Storage::put($backupPath . '/metadata.json', json_encode($metadata, JSON_PRETTY_PRINT));

        Log::info("Created backup {$name} for foundation {$foundationId}");

        return $backupPath;
    }

    /**
     * Restore from a backup
     */
    public function restore(string $backupPath, int $foundationId): bool
    {
        try {
            // Read backup data
            $dataPath = $backupPath . '/data.json';
            
            if (!Storage::exists($dataPath)) {
                throw new \Exception('Backup file not found');
            }

            $backupData = json_decode(Storage::get($dataPath), true);

            DB::beginTransaction();

            // Restore each table
            foreach ($backupData as $table => $records) {
                // Clear existing data for this foundation
                DB::table($table)->where('foundation_id', $foundationId)->delete();

                // Insert backup records
                foreach ($records as $record) {
                    unset($record->id); // Remove old ID to let auto-increment handle it
                    DB::table($table)->insert((array) $record);
                }

                Log::info("Restored " . count($records) . " records to {$table}");
            }

            DB::commit();

            Log::info("Restored backup {$backupPath} for foundation {$foundationId}");

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to restore backup: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get list of available backups
     */
    public function getBackups(int $foundationId): array
    {
        $backupPath = 'backups/' . $foundationId;
        
        if (!Storage::exists($backupPath)) {
            return [];
        }

        $backups = [];
        
        foreach (Storage::directories($backupPath) as $backupDir) {
            $metadataPath = $backupDir . '/metadata.json';
            
            if (Storage::exists($metadataPath)) {
                $metadata = json_decode(Storage::get($metadataPath), true);
                $backups[] = [
                    'path' => $backupDir,
                    'name' => $metadata['name'] ?? basename($backupDir),
                    'created_at' => $metadata['created_at'] ?? null,
                    'record_counts' => $metadata['record_counts'] ?? [],
                ];
            }
        }

        // Sort by creation date (newest first)
        usort($backups, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $backups;
    }

    /**
     * Delete a backup
     */
    public function deleteBackup(string $backupPath): bool
    {
        try {
            Storage::deleteDirectory($backupPath);
            Log::info("Deleted backup {$backupPath}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to delete backup: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Export attendance records to a specific date range
     */
    public function exportRecords(
        int $foundationId,
        Carbon $dateFrom,
        Carbon $dateTo,
        string $format = 'json'
    ): string {
        $records = AttendanceRecord::where('foundation_id', $foundationId)
            ->whereBetween('check_in_time', [$dateFrom, $dateTo])
            ->with(['user', 'session', 'device'])
            ->get();

        $filename = 'exports/attendance_' . $foundationId . '_' . $dateFrom->format('Y_m_d') . '_' . $dateTo->format('Y_m_d');

        return match ($format) {
            'csv' => $this->exportToCsv($records, $filename),
            'json' => $this->exportToJson($records, $filename),
            default => $this->exportToJson($records, $filename),
        };
    }

    /**
     * Export to CSV
     */
    protected function exportToCsv($records, string $filename): string
    {
        $path = $filename . '.csv';
        $fullPath = storage_path('app/' . $path);

        // Ensure directory exists
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $handle = fopen($fullPath, 'w');

        // Header
        fputcsv($handle, [
            'ID', 'User', 'Session', 'Check In', 'Check Out',
            'Method', 'Status', 'Location', 'Notes', 'Created At'
        ]);

        // Data
        foreach ($records as $record) {
            fputcsv($handle, [
                $record->id,
                $record->user->name ?? 'N/A',
                $record->session->name ?? 'N/A',
                $record->check_in_time?->format('Y-m-d H:i:s'),
                $record->check_out_time?->format('Y-m-d H:i:s'),
                $record->method,
                $record->status,
                $record->location_lat && $record->location_long 
                    ? "{$record->location_lat},{$record->location_long}" 
                    : 'N/A',
                $record->notes,
                $record->created_at?->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($handle);

        return $path;
    }

    /**
     * Export to JSON
     */
    protected function exportToJson($records, string $filename): string
    {
        $path = $filename . '.json';
        $fullPath = storage_path('app/' . $path);

        // Ensure directory exists
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $data = $records->map(function ($record) {
            return [
                'id' => $record->id,
                'user_id' => $record->user_id,
                'user_name' => $record->user->name ?? null,
                'session_id' => $record->session_id,
                'session_name' => $record->session->name ?? null,
                'check_in_time' => $record->check_in_time?->toIso8601String(),
                'check_out_time' => $record->check_out_time?->toIso8601String(),
                'method' => $record->method,
                'status' => $record->status,
                'location' => $record->location_lat && $record->location_long 
                    ? ['lat' => $record->location_lat, 'long' => $record->location_long] 
                    : null,
                'notes' => $record->notes,
                'created_at' => $record->created_at?->toIso8601String(),
            ];
        });

        file_put_contents($fullPath, json_encode($data, JSON_PRETTY_PRINT));

        return $path;
    }

    /**
     * Clean up old data (retention policy)
     */
    public function cleanup(int $foundationId, int $retentionDays = 365): int
    {
        $cutoffDate = now()->subDays($retentionDays);
        
        $tables = [
            'attendance_records',
            'attendance_audit_logs',
            'attendance_qr_codes',
        ];

        $totalDeleted = 0;

        foreach ($tables as $table) {
            $deleted = DB::table($table)
                ->where('foundation_id', $foundationId)
                ->where('created_at', '<', $cutoffDate)
                ->delete();

            $totalDeleted += $deleted;
            
            Log::info("Cleaned up {$deleted} records from {$table}");
        }

        Log::info("Total cleanup: {$totalDeleted} records for foundation {$foundationId}");

        return $totalDeleted;
    }

    /**
     * Archive old records to cold storage
     */
    public function archive(int $foundationId, int $monthsToArchive = 12): int
    {
        $cutoffDate = now()->subMonths($monthsToArchive);
        
        // Get records to archive
        $records = AttendanceRecord::where('foundation_id', $foundationId)
            ->where('check_in_time', '<', $cutoffDate)
            ->with(['user', 'session', 'device'])
            ->get();

        if ($records->isEmpty()) {
            return 0;
        }

        // Create archive file
        $archivePath = 'archives/' . $foundationId . '/';
        $filename = 'archive_' . $cutoffDate->format('Y_m_d') . '.json';
        
        Storage::makeDirectory($archivePath);
        
        $data = $records->map(function ($record) {
            return [
                'id' => $record->id,
                'user_id' => $record->user_id,
                'user_name' => $record->user->name ?? null,
                'session_id' => $record->session_id,
                'session_name' => $record->session->name ?? null,
                'check_in_time' => $record->check_in_time?->toIso8601String(),
                'check_out_time' => $record->check_out_time?->toIso8601String(),
                'method' => $record->method,
                'status' => $record->status,
                'notes' => $record->notes,
            ];
        });

        Storage::put($archivePath . $filename, json_encode($data, JSON_PRETTY_PRINT));

        // Delete archived records from main table
        AttendanceRecord::where('foundation_id', $foundationId)
            ->where('check_in_time', '<', $cutoffDate)
            ->delete();

        Log::info("Archived {$records->count()} records for foundation {$foundationId}");

        return $records->count();
    }

    /**
     * Get backup statistics
     */
    public function getStatistics(int $foundationId): array
    {
        return [
            'total_records' => AttendanceRecord::where('foundation_id', $foundationId)->count(),
            'total_sessions' => AttendanceSession::where('foundation_id', $foundationId)->count(),
            'total_devices' => \App\Modules\Attendance\Models\AttendanceDevice::where('foundation_id', $foundationId)->count(),
            'total_qr_codes' => AttendanceQrCode::where('foundation_id', $foundationId)->count(),
            'total_fingerprints' => AttendanceFingerprint::where('foundation_id', $foundationId)->count(),
            'total_faces' => AttendanceFace::where('foundation_id', $foundationId)->count(),
            'total_rfids' => AttendanceRfid::where('foundation_id', $foundationId)->count(),
            'total_geofences' => AttendanceGeofence::where('foundation_id', $foundationId)->count(),
            'total_audit_logs' => AttendanceAuditLog::where('foundation_id', $foundationId)->count(),
            'backups' => count($this->getBackups($foundationId)),
        ];
    }
}
