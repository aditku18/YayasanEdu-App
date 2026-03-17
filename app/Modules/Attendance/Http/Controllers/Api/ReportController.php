<?php

namespace App\Modules\Attendance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Attendance\Services\ReportService;
use App\Modules\Attendance\Services\BackupService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    protected ReportService $reportService;
    protected BackupService $backupService;

    public function __construct(
        ReportService $reportService,
        BackupService $backupService
    ) {
        $this->reportService = $reportService;
        $this->backupService = $backupService;
    }

    /**
     * Generate a report
     */
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:daily,weekly,monthly,custom,summary,late_arrivals,early_departures,overtime,absences',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'filters' => 'nullable|array',
            'filters.user_ids' => 'nullable|array',
            'filters.status' => 'nullable|array',
            'filters.method' => 'nullable|array',
            'format' => 'nullable|in:pdf,excel,csv,json',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        try {
            $report = $this->reportService->generateReport(
                $request->type,
                Carbon::parse($request->date_from),
                Carbon::parse($request->date_to),
                $foundationId,
                $request->user()->id ?? null,
                $request->filters ?? [],
                $request->format ?? 'pdf'
            );

            return response()->json([
                'success' => true,
                'message' => 'Report generated successfully',
                'data' => [
                    'id' => $report->id,
                    'name' => $report->name,
                    'type' => $report->type,
                    'download_url' => route('api.attendance.reports.download', ['id' => $report->id]),
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
     * Get all reports
     */
    public function index(Request $request): JsonResponse
    {
        $foundationId = $request->user()->foundation_id ?? 1;

        $reports = $this->reportService->getRecentReports($foundationId, 20);

        return response()->json([
            'success' => true,
            'data' => $reports->map(function ($report) {
                return [
                    'id' => $report->id,
                    'name' => $report->name,
                    'type' => $report->type,
                    'type_label' => $report->type_label,
                    'date_from' => $report->date_from,
                    'date_to' => $report->date_to,
                    'is_completed' => $report->is_completed,
                    'file_format' => $report->file_format,
                    'created_at' => $report->created_at->toIso8601String(),
                ];
            }),
        ]);
    }

    /**
     * Get a specific report
     */
    public function show(int $id): JsonResponse
    {
        $foundationId = request()->user()->foundation_id ?? 1;

        $report = \App\Modules\Attendance\Models\AttendanceReport::where('id', $id)
            ->where('foundation_id', $foundationId)
            ->first();

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $report->id,
                'name' => $report->name,
                'type' => $report->type,
                'type_label' => $report->type_label,
                'date_from' => $report->date_from,
                'date_to' => $report->date_to,
                'filters' => $report->filters,
                'is_completed' => $report->is_completed,
                'file_format' => $report->file_format,
                'file_path' => $report->file_path,
                'created_at' => $report->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Download a report
     */
    public function download(int $id): Response
    {
        $foundationId = request()->user()->foundation_id ?? 1;

        $report = \App\Modules\Attendance\Models\AttendanceReport::where('id', $id)
            ->where('foundation_id', $foundationId)
            ->first();

        if (!$report || !$report->fileExists()) {
            abort(404, 'Report file not found');
        }

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $report->name . '.' . $report->file_format . '"',
        ];

        return response()->download($report->full_file_path, $report->name . '.' . $report->file_format, $headers);
    }

    /**
     * Delete a report
     */
    public function destroy(int $id): JsonResponse
    {
        $foundationId = request()->user()->foundation_id ?? 1;

        $report = \App\Modules\Attendance\Models\AttendanceReport::where('id', $id)
            ->where('foundation_id', $foundationId)
            ->first();

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found',
            ], 404);
        }

        // Delete file if exists
        if ($report->fileExists()) {
            unlink($report->full_file_path);
        }

        $report->delete();

        return response()->json([
            'success' => true,
            'message' => 'Report deleted successfully',
        ]);
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(Request $request): JsonResponse
    {
        $foundationId = $request->user()->foundation_id ?? 1;

        $stats = $this->reportService->getDashboardStats($foundationId);

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Create a backup
     */
    public function createBackup(Request $request): JsonResponse
    {
        $foundationId = $request->user()->foundation_id ?? 1;

        try {
            $backupPath = $this->backupService->createBackup($foundationId, $request->name);

            return response()->json([
                'success' => true,
                'message' => 'Backup created successfully',
                'data' => [
                    'path' => $backupPath,
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
     * List backups
     */
    public function listBackups(Request $request): JsonResponse
    {
        $foundationId = $request->user()->foundation_id ?? 1;

        $backups = $this->backupService->getBackups($foundationId);

        return response()->json([
            'success' => true,
            'data' => $backups,
        ]);
    }

    /**
     * Restore from backup
     */
    public function restoreBackup(Request $request): JsonResponse
    {
        $request->validate([
            'backup_path' => 'required|string',
        ]);

        $foundationId = $request->user()->foundation_id ?? 1;

        try {
            $success = $this->backupService->restore($request->backup_path, $foundationId);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to restore backup',
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Backup restored successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete a backup
     */
    public function deleteBackup(Request $request): JsonResponse
    {
        $request->validate([
            'backup_path' => 'required|string',
        ]);

        $success = $this->backupService->deleteBackup($request->backup_path);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete backup',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Backup deleted successfully',
        ]);
    }
}
