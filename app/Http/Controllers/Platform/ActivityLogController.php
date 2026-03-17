<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with(['user', 'foundation'])
            ->when($request->action, function ($query, $action) {
                return $query->where('action', $action);
            })
            ->when($request->user_id, function ($query, $userId) {
                return $query->where('user_id', $userId);
            })
            ->when($request->foundation_id, function ($query, $foundationId) {
                return $query->where('foundation_id', $foundationId);
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->latest()
            ->paginate(50);

        $users = \App\Models\User::pluck('name', 'id');
        $foundations = \App\Models\Foundation::pluck('name', 'id');
        $actions = ActivityLog::distinct()->pluck('action');

        $stats = [
            'total_logs' => ActivityLog::count(),
            'today_logs' => ActivityLog::whereDate('created_at', today())->count(),
            'error_logs' => ActivityLog::where('action', 'like', '%error%')->count(),
            'login_logs' => ActivityLog::where('action', 'login')->count(),
        ];
        
        return view('platform.activity-logs.index', compact(
            'logs', 'users', 'foundations', 'actions', 'stats'
        ));
    }

    public function show(ActivityLog $log)
    {
        $log->load(['user', 'foundation']);
        return view('platform.activity-logs.show', compact('log'));
    }

    public function cleanup(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:7|max:365'
        ]);

        $cutoffDate = now()->subDays($request->days);
        $deletedCount = ActivityLog::where('created_at', '<', $cutoffDate)->delete();

        return redirect()->route('platform.activity-logs.index')
            ->with('success', "Berhasil menghapus {$deletedCount} log activity yang lebih lama dari {$request->days} hari.");
    }

    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:csv,xlsx',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from'
        ]);

        $logs = ActivityLog::with(['user', 'foundation'])
            ->whereDate('created_at', '>=', $request->date_from)
            ->whereDate('created_at', '<=', $request->date_to)
            ->when($request->action, function ($query, $action) {
                return $query->where('action', $action);
            })
            ->orderBy('created_at')
            ->get();

        $filename = "activity-logs-{$request->date_from}-to-{$request->date_to}.{$request->format}";

        if ($request->format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\""
            ];

            $callback = function () use ($logs) {
                $file = fopen('php://output', 'w');
                
                // CSV Header
                fputcsv($file, [
                    'Timestamp', 'User', 'Foundation', 'Action', 
                    'Description', 'IP Address', 'User Agent'
                ]);

                // CSV Data
                foreach ($logs as $log) {
                    fputcsv($file, [
                        $log->created_at->format('Y-m-d H:i:s'),
                        $log->user ? $log->user->name : 'System',
                        $log->foundation ? $log->foundation->name : 'N/A',
                        $log->action,
                        $log->description,
                        $log->ip_address,
                        $log->user_agent
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // For Excel format, you would typically use a library like Laravel Excel
        // For now, redirect with message
        return redirect()->back()->with('info', 'Export Excel akan segera tersedia.');
    }
}
