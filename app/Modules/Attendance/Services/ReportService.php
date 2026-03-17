<?php

namespace App\Modules\Attendance\Services;

use App\Modules\Attendance\Models\AttendanceRecord;
use App\Modules\Attendance\Models\AttendanceReport;
use App\Modules\Attendance\Models\AttendanceSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Attendance Report Service
 * Handles report generation, analytics, and data export
 */
class ReportService
{
    /**
     * Generate a report
     */
    public function generateReport(
        string $type,
        Carbon $dateFrom,
        Carbon $dateTo,
        int $foundationId,
        ?int $generatedBy = null,
        array $filters = [],
        string $format = 'pdf'
    ): AttendanceReport {
        // Create report record
        $report = AttendanceReport::create([
            'name' => AttendanceReport::generateName($type, $dateFrom, $dateTo),
            'type' => $type,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'filters' => $filters,
            'generated_by' => $generatedBy,
            'file_format' => $format,
            'is_completed' => false,
            'foundation_id' => $foundationId,
        ]);

        // Generate report based on type
        $data = $this->generateReportData($type, $dateFrom, $dateTo, $foundationId, $filters);

        // Generate file based on format
        $filePath = $this->generateFile($report, $data, $format);

        // Mark as completed
        $report->markAsCompleted($filePath, $format);

        Log::info("Generated attendance report {$report->id} - {$report->name}");

        return $report;
    }

    /**
     * Generate report data based on type
     */
    protected function generateReportData(
        string $type,
        Carbon $dateFrom,
        Carbon $dateTo,
        int $foundationId,
        array $filters
    ): array {
        $query = AttendanceRecord::where('foundation_id', $foundationId)
            ->whereBetween('check_in_time', [$dateFrom, $dateTo]);

        // Apply filters
        if (!empty($filters['user_ids'])) {
            $query->whereIn('user_id', $filters['user_ids']);
        }

        if (!empty($filters['status'])) {
            $query->whereIn('status', $filters['status']);
        }

        if (!empty($filters['method'])) {
            $query->whereIn('method', $filters['method']);
        }

        $records = $query->with(['user', 'session'])->get();

        return match ($type) {
            AttendanceReport::TYPE_DAILY => $this->generateDailyReport($records, $dateFrom),
            AttendanceReport::TYPE_WEEKLY => $this->generateWeeklyReport($records, $dateFrom, $dateTo),
            AttendanceReport::TYPE_MONTHLY => $this->generateMonthlyReport($records, $dateFrom),
            AttendanceReport::TYPE_LATE_ARRIVALS => $this->generateLateArrivalsReport($records),
            AttendanceReport::TYPE_EARLY_DEPARTURES => $this->generateEarlyDeparturesReport($records),
            AttendanceReport::TYPE_OVERTIME => $this->generateOvertimeReport($records),
            AttendanceReport::TYPE_ABSENCES => $this->generateAbsencesReport($records, $dateFrom, $dateTo, $foundationId),
            default => $this->generateSummaryReport($records),
        };
    }

    /**
     * Generate daily report
     */
    protected function generateDailyReport($records, Carbon $date): array
    {
        $recordsByHour = $records->groupBy(function ($record) {
            return $record->check_in_time ? $record->check_in_time->format('H:00') : 'N/A';
        });

        return [
            'title' => 'Daily Attendance Report',
            'date' => $date->format('Y-m-d'),
            'total_records' => $records->count(),
            'present' => $records->where('status', 'present')->count(),
            'late' => $records->where('status', 'late')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'excused' => $records->where('status', 'excused')->count(),
            'by_hour' => $recordsByHour->map->count(),
            'by_method' => $records->groupBy('method')->map->count(),
            'records' => $records,
        ];
    }

    /**
     * Generate weekly report
     */
    protected function generateWeeklyReport($records, Carbon $dateFrom, Carbon $dateTo): array
    {
        $recordsByDay = $records->groupBy(function ($record) {
            return $record->check_in_time ? $record->check_in_time->format('Y-m-d') : 'N/A';
        });

        return [
            'title' => 'Weekly Attendance Report',
            'date_from' => $dateFrom->format('Y-m-d'),
            'date_to' => $dateTo->format('Y-m-d'),
            'total_records' => $records->count(),
            'present' => $records->where('status', 'present')->count(),
            'late' => $records->where('status', 'late')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'excused' => $records->where('status', 'excused')->count(),
            'by_day' => $recordsByDay->map->count(),
            'by_method' => $records->groupBy('method')->map->count(),
            'average_attendance' => $recordsByDay->avg('count'),
        ];
    }

    /**
     * Generate monthly report
     */
    protected function generateMonthlyReport($records, Carbon $date): array
    {
        $recordsByDay = $records->groupBy(function ($record) {
            return $record->check_in_time ? $record->check_in_time->format('Y-m-d') : 'N/A';
        });

        $totalHours = $records->sum(function ($record) {
            return $record->getTotalHours();
        });

        return [
            'title' => 'Monthly Attendance Report',
            'month' => $date->format('F Y'),
            'total_records' => $records->count(),
            'present' => $records->where('status', 'present')->count(),
            'late' => $records->where('status', 'late')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'excused' => $records->where('status', 'excused')->count(),
            'total_hours' => round($totalHours, 2),
            'average_hours_per_day' => round($totalHours / max($recordsByDay->count(), 1), 2),
            'by_day' => $recordsByDay->map->count(),
            'attendance_rate' => round(($records->whereIn('status', ['present', 'late'])->count() / max($records->count(), 1)) * 100, 2),
        ];
    }

    /**
     * Generate late arrivals report
     */
    protected function generateLateArrivalsReport($records): array
    {
        $lateRecords = $records->where('status', 'late');

        $lateByUser = $lateRecords->groupBy('user_id')->map->count();

        return [
            'title' => 'Late Arrivals Report',
            'total_late' => $lateRecords->count(),
            'late_by_user' => $lateByUser->map(function ($count, $userId) use ($lateRecords) {
                $user = $lateRecords->firstWhere('user_id', $userId)->user ?? null;
                return [
                    'user_id' => $userId,
                    'name' => $user ? $user->name : 'Unknown',
                    'late_count' => $count,
                ];
            })->values(),
            'average_minutes_late' => $lateRecords->avg(function ($record) {
                if (!$record->session || !$record->check_in_time) return 0;
                return $record->check_in_time->diffInMinutes($record->session->start_time);
            }),
        ];
    }

    /**
     * Generate early departures report
     */
    protected function generateEarlyDeparturesReport($records): array
    {
        $recordsWithCheckout = $records->filter(function ($record) {
            return $record->check_out_time && $record->session;
        });

        $earlyDepartures = $recordsWithCheckout->filter(function ($record) {
            return $record->isEarlyDeparture();
        });

        return [
            'title' => 'Early Departures Report',
            'total_early' => $earlyDepartures->count(),
            'by_user' => $earlyDepartures->groupBy('user_id')->map->count(),
            'average_minutes_early' => $earlyDepartures->avg(function ($record) {
                return $record->session->end_time->diffInMinutes($record->check_out_time);
            }),
        ];
    }

    /**
     * Generate overtime report
     */
    protected function generateOvertimeReport($records): array
    {
        $recordsWithOvertime = $records->filter(function ($record) {
            return $record->getOvertimeHours() > 0;
        });

        $totalOvertime = $recordsWithOvertime->sum(function ($record) {
            return $record->getOvertimeHours();
        });

        return [
            'title' => 'Overtime Report',
            'total_overtime_hours' => round($totalOvertime, 2),
            'users_with_overtime' => $recordsWithOvertime->groupBy('user_id')->count(),
            'by_user' => $recordsWithOvertime->groupBy('user_id')->map(function ($records) {
                return [
                    'total_hours' => round($records->sum(fn($r) => $r->getOvertimeHours()), 2),
                    'days' => $records->count(),
                ];
            }),
        ];
    }

    /**
     * Generate absences report
     */
    protected function generateAbsencesReport($records, Carbon $dateFrom, Carbon $dateTo, int $foundationId): array
    {
        // Get all expected users
        $expectedUsers = User::where('foundation_id', $foundationId)->get();
        
        $presentUserIds = $records->pluck('user_id')->unique()->toArray();
        
        $absentUsers = $expectedUsers->filter(function ($user) use ($presentUserIds) {
            return !in_array($user->id, $presentUserIds);
        });

        return [
            'title' => 'Absences Report',
            'total_absent' => $absentUsers->count(),
            'absent_users' => $absentUsers->map(function ($user) use ($dateFrom, $dateTo, $records) {
                $userRecords = $records->where('user_id', $user->id);
                return [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'absent_days' => $dateFrom->diffInDays($dateTo) + 1 - $userRecords->count(),
                ];
            })->values(),
        ];
    }

    /**
     * Generate summary report
     */
    protected function generateSummaryReport($records): array
    {
        return [
            'title' => 'Attendance Summary Report',
            'total_records' => $records->count(),
            'present' => $records->where('status', 'present')->count(),
            'late' => $records->where('status', 'late')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'excused' => $records->where('status', 'excused')->count(),
            'on_leave' => $records->where('status', 'on_leave')->count(),
            'by_method' => $records->groupBy('method')->map->count(),
            'attendance_rate' => round(($records->whereIn('status', ['present', 'late'])->count() / max($records->count(), 1)) * 100, 2),
        ];
    }

    /**
     * Generate file based on format
     */
    protected function generateFile(AttendanceReport $report, array $data, string $format): string
    {
        $filename = 'reports/attendance_' . $report->id . '_' . time();

        return match ($format) {
            'excel' => $this->generateExcel($filename, $data),
            'csv' => $this->generateCsv($filename, $data),
            'json' => $this->generateJson($filename, $data),
            default => $this->generatePdf($filename, $data),
        };
    }

    /**
     * Generate PDF report
     */
    protected function generatePdf(string $filename, array $data): string
    {
        // In production, use a PDF library like dompdf or snappy
        // For now, return path for JSON
        return $this->generateJson($filename, $data);
    }

    /**
     * Generate Excel report
     */
    protected function generateExcel(string $filename, array $data): string
    {
        // In production, use PhpSpreadsheet
        return $filename . '.xlsx';
    }

    /**
     * Generate CSV report
     */
    protected function generateCsv(string $filename, array $data): string
    {
        $path = $filename . '.csv';
        $fullPath = storage_path('app/' . $path);

        // Ensure directory exists
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $handle = fopen($fullPath, 'w');

        // Add header
        if (!empty($data['records'])) {
            $firstRecord = $data['records']->first();
            if ($firstRecord) {
                fputcsv($handle, ['User', 'Date', 'Check In', 'Check Out', 'Method', 'Status', 'Hours']);
            }

            foreach ($data['records'] as $record) {
                fputcsv($handle, [
                    $record->user->name ?? 'N/A',
                    $record->check_in_time ? $record->check_in_time->format('Y-m-d') : 'N/A',
                    $record->check_in_time ? $record->check_in_time->format('H:i') : 'N/A',
                    $record->check_out_time ? $record->check_out_time->format('H:i') : 'N/A',
                    $record->method,
                    $record->status,
                    round($record->getTotalHours(), 2),
                ]);
            }
        }

        fclose($handle);

        return $path;
    }

    /**
     * Generate JSON report
     */
    protected function generateJson(string $filename, array $data): string
    {
        $path = $filename . '.json';
        $fullPath = storage_path('app/' . $path);

        // Ensure directory exists
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Remove records from data for JSON (too large)
        $jsonData = $data;
        unset($jsonData['records']);

        file_put_contents($fullPath, json_encode($jsonData, JSON_PRETTY_PRINT));

        return $path;
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(int $foundationId): array
    {
        $today = now()->toDateString();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();

        // Today's stats
        $todayRecords = AttendanceRecord::where('foundation_id', $foundationId)
            ->whereDate('check_in_time', $today)
            ->get();

        // This week's stats
        $weekRecords = AttendanceRecord::where('foundation_id', $foundationId)
            ->whereBetween('check_in_time', [$thisWeek, now()])
            ->get();

        // This month's stats
        $monthRecords = AttendanceRecord::where('foundation_id', $foundationId)
            ->whereBetween('check_in_time', [$thisMonth, now()])
            ->get();

        return [
            'today' => [
                'total' => $todayRecords->count(),
                'present' => $todayRecords->whereIn('status', ['present', 'late'])->count(),
                'absent' => $todayRecords->where('status', 'absent')->count(),
                'late' => $todayRecords->where('status', 'late')->count(),
            ],
            'week' => [
                'total' => $weekRecords->count(),
                'present' => $weekRecords->whereIn('status', ['present', 'late'])->count(),
                'absent' => $weekRecords->where('status', 'absent')->count(),
                'attendance_rate' => round(($weekRecords->whereIn('status', ['present', 'late'])->count() / max($weekRecords->count(), 1)) * 100, 2),
            ],
            'month' => [
                'total' => $monthRecords->count(),
                'present' => $monthRecords->whereIn('status', ['present', 'late'])->count(),
                'absent' => $monthRecords->where('status', 'absent')->count(),
                'attendance_rate' => round(($monthRecords->whereIn('status', ['present', 'late'])->count() / max($monthRecords->count(), 1)) * 100, 2),
            ],
        ];
    }

    /**
     * Get recent reports
     */
    public function getRecentReports(int $foundationId, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return AttendanceReport::where('foundation_id', $foundationId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
