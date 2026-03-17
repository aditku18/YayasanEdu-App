<?php

namespace App\Services\Monitoring;

use App\Models\ActivityLog;
use App\Models\LoginLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * System Monitor Service
 * 
 * Provides system monitoring and analytics for the platform.
 * Tracks user activity, login attempts, and system health.
 */
class SystemMonitor
{
    /**
     * Get dashboard statistics.
     *
     * @param int $days
     * @return array
     */
    public function getDashboardStats(int $days = 30): array
    {
        return Cache::remember("system_stats_{$days}", 300, function () use ($days) {
            return [
                'activity' => $this->getActivityStats($days),
                'logins' => $this->getLoginStats($days),
                'database' => $this->getDatabaseStats(),
                'cache' => $this->getCacheStats(),
            ];
        });
    }

    /**
     * Get activity statistics.
     *
     * @param int $days
     * @return array
     */
    public function getActivityStats(int $days = 30): array
    {
        $from = now()->subDays($days);
        
        // Total activities
        $total = ActivityLog::where('created_at', '>=', $from)->count();
        
        // Activities by module
        $byModule = ActivityLog::select('module')
            ->selectRaw('COUNT(*) as count')
            ->where('created_at', '>=', $from)
            ->groupBy('module')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        // Activities by action
        $byAction = ActivityLog::select('action')
            ->selectRaw('COUNT(*) as count')
            ->where('created_at', '>=', $from)
            ->groupBy('action')
            ->get();
        
        // Daily activity trend
        $dailyTrend = ActivityLog::select(DB::raw('DATE(created_at) as date'))
            ->selectRaw('COUNT(*) as count')
            ->where('created_at', '>=', $from)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        
        // Top active users
        $topUsers = ActivityLog::select('user_id')
            ->selectRaw('COUNT(*) as count')
            ->where('created_at', '>=', $from)
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        return [
            'total' => $total,
            'by_module' => $byModule,
            'by_action' => $byAction,
            'daily_trend' => $dailyTrend,
            'top_users' => $topUsers,
        ];
    }

    /**
     * Get login statistics.
     *
     * @param int $days
     * @return array
     */
    public function getLoginStats(int $days = 30): array
    {
        $from = now()->subDays($days);
        
        // Total logins
        $totalLogins = LoginLog::where('created_at', '>=', $from)
            ->where('status', 'success')
            ->count();
        
        // Failed attempts
        $failedAttempts = LoginLog::where('created_at', '>=', $from)
            ->where('status', 'failed')
            ->count();
        
        // Unique users
        $uniqueUsers = LoginLog::where('created_at', '>=', $from)
            ->where('status', 'success')
            ->distinct('user_id')
            ->count('user_id');
        
        // By device type
        $byDevice = LoginLog::select('device_type')
            ->selectRaw('COUNT(*) as count')
            ->where('created_at', '>=', $from)
            ->where('status', 'success')
            ->groupBy('device_type')
            ->get();
        
        // Daily login trend
        $dailyTrend = LoginLog::select(DB::raw('DATE(login_at) as date'))
            ->selectRaw('COUNT(*) as count')
            ->where('login_at', '>=', $from)
            ->where('status', 'success')
            ->groupBy(DB::raw('DATE(login_at)'))
            ->orderBy('date')
            ->get();
        
        // Top IPs (for security)
        $topIps = LoginLog::select('ip_address')
            ->selectRaw('COUNT(*) as count')
            ->where('created_at', '>=', $from)
            ->groupBy('ip_address')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        return [
            'total_logins' => $totalLogins,
            'failed_attempts' => $failedAttempts,
            'unique_users' => $uniqueUsers,
            'by_device' => $byDevice,
            'daily_trend' => $dailyTrend,
            'top_ips' => $topIps,
            'success_rate' => $totalLogins > 0 
                ? round(($totalLogins / ($totalLogins + $failedAttempts)) * 100, 2) 
                : 100,
        ];
    }

    /**
     * Get database statistics.
     *
     * @return array
     */
    public function getDatabaseStats(): array
    {
        try {
            // Get table sizes
            $tables = DB::select('
                SELECT 
                    table_name,
                    ROUND(data_length / 1024 / 1024, 2) as size_mb,
                    table_rows as rows
                FROM information_schema.tables
                WHERE table_schema = DATABASE()
                ORDER BY data_length DESC
                LIMIT 10
            ');
            
            return [
                'tables' => $tables,
                'connection_status' => 'connected',
            ];
        } catch (\Exception $e) {
            return [
                'tables' => [],
                'connection_status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get cache statistics.
     *
     * @return array
     */
    public function getCacheStats(): array
    {
        try {
            $driver = config('cache.default');
            
            return [
                'driver' => $driver,
                'prefix' => config('cache.prefix'),
                'status' => 'operational',
            ];
        } catch (\Exception $e) {
            return [
                'driver' => config('cache.default'),
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get recent activities.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentActivities(int $limit = 20)
    {
        return ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent logins.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentLogins(int $limit = 20)
    {
        return LoginLog::with('user')
            ->orderBy('login_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get failed login attempts (security).
     *
     * @param int $hours
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFailedLogins(int $hours = 24)
    {
        return LoginLog::with('user')
            ->where('created_at', '>=', now()->subHours($hours))
            ->where('status', 'failed')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get server resource usage.
     *
     * @return array
     */
    public function getServerStats(): array
    {
        try {
            // CPU Load (simulated - in production would use sys_getloadavg or similar)
            $cpuLoad = function_exists('sys_getloadavg') ? sys_getloadavg() : [0, 0, 0];
            $cpuPercent = $cpuLoad[0] * 100 / 4; // Assuming 4 cores
            $cpuPercent = min(100, round($cpuPercent, 1));
            
            // Memory Usage
            $free = shell_exec('free -m 2>/dev/null');
            if ($free) {
                preg_match('/Mem:\s+(\d+)\s+(\d+)\s+(\d+)/', $free, $matches);
                $total = isset($matches[1]) ? (int)$matches[1] : 0;
                $used = isset($matches[2]) ? (int)$matches[2] : 0;
                $freeMem = isset($matches[3]) ? (int)$matches[3] : 0;
                $memoryPercent = $total > 0 ? round(($used / $total) * 100, 1) : 0;
            } else {
                // Windows fallback - use PHP memory
                $total = round(memory_get_usage(true) / 1024 / 1024, 2);
                $used = $total;
                $memoryPercent = 30; // Default simulation
            }
            
            // Disk Usage
            $diskTotal = disk_total_space(base_path());
            $diskFree = disk_free_space(base_path());
            $diskUsed = $diskTotal - $diskFree;
            $diskPercent = $diskTotal > 0 ? round(($diskUsed / $diskTotal) * 100, 1) : 0;
            
            return [
                'cpu' => [
                    'percent' => $cpuPercent,
                    'load' => $cpuLoad,
                    'status' => $cpuPercent > 80 ? 'high' : ($cpuPercent > 50 ? 'medium' : 'normal'),
                ],
                'memory' => [
                    'percent' => $memoryPercent,
                    'total_mb' => $total ?? 0,
                    'used_mb' => $used ?? 0,
                    'free_mb' => $freeMem ?? 0,
                    'status' => $memoryPercent > 80 ? 'high' : ($memoryPercent > 50 ? 'medium' : 'normal'),
                ],
                'disk' => [
                    'percent' => $diskPercent,
                    'total_gb' => round($diskTotal / 1024 / 1024 / 1024, 2),
                    'used_gb' => round($diskUsed / 1024 / 1024 / 1024, 2),
                    'free_gb' => round($diskFree / 1024 / 1024 / 1024, 2),
                    'status' => $diskPercent > 90 ? 'high' : ($diskPercent > 70 ? 'medium' : 'normal'),
                ],
                'uptime' => $this->getUptime(),
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'cpu' => ['percent' => 0, 'status' => 'unknown'],
                'memory' => ['percent' => 0, 'status' => 'unknown'],
                'disk' => ['percent' => 0, 'status' => 'unknown'],
            ];
        }
    }

    /**
     * Get system uptime.
     *
     * @return string
     */
    protected function getUptime(): string
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return 'N/A';
        }
        
        $uptime = @shell_exec('uptime -p 2>/dev/null');
        return $uptime ? trim($uptime) : 'Unknown';
    }

    /**
     * Get foundation registration trend.
     *
     * @param int $months
     * @return array
     */
    public function getFoundationRegistrationTrend(int $months = 12): array
    {
        $data = [];
        $labels = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = \App\Models\Foundation::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $labels[] = $date->format('M');
            $data[] = $count;
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'total' => array_sum($data),
            'average' => round(array_sum($data) / count($data), 1),
        ];
    }

    /**
     * Get pending foundations for approval.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingFoundations(int $limit = 5)
    {
        return \App\Models\Foundation::where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Check for suspicious activity.
     *
     * @return array
     */
    public function checkSuspiciousActivity(): array
    {
        $alerts = [];
        
        // Check for multiple failed logins from same IP
        $failedFromIp = LoginLog::select('ip_address')
            ->selectRaw('COUNT(*) as count')
            ->where('created_at', '>=', now()->subHours(1))
            ->where('status', 'failed')
            ->groupBy('ip_address')
            ->having('count', '>', 5)
            ->get();
        
        if ($failedFromIp->isNotEmpty()) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Multiple failed login attempts',
                'message' => "Found {$failedFromIp->sum('count')} failed attempts from " . 
                    $failedFromIp->count() . " different IPs in the last hour",
                'data' => $failedFromIp,
            ];
        }
        
        // Check for unusual activity volume
        $todayCount = ActivityLog::whereDate('created_at', today())->count();
        $yesterdayCount = ActivityLog::whereDate('created_at', today()->subDay())->count();
        
        if ($yesterdayCount > 0 && $todayCount > ($yesterdayCount * 3)) {
            $alerts[] = [
                'type' => 'info',
                'title' => 'Unusual activity spike',
                'message' => "Today's activity is " . round($todayCount / $yesterdayCount, 1) . 
                    "x higher than yesterday",
            ];
        }
        
        return $alerts;
    }

    /**
     * Get user activity timeline.
     *
     * @param int $userId
     * @param int $days
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserActivityTimeline(int $userId, int $days = 7)
    {
        return ActivityLog::where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays($days))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get module usage report.
     *
     * @param int $days
     * @return array
     */
    public function getModuleUsage(int $days = 30): array
    {
        $from = now()->subDays($days);
        
        $modules = ActivityLog::select('module')
            ->selectRaw('COUNT(*) as total_actions')
            ->selectRaw('COUNT(DISTINCT user_id) as unique_users')
            ->where('created_at', '>=', $from)
            ->groupBy('module')
            ->orderByDesc('total_actions')
            ->get();
        
        $totalActions = $modules->sum('total_actions');
        
        return [
            'period_days' => $days,
            'total_actions' => $totalActions,
            'modules' => $modules->map(function ($module) use ($totalActions) {
                return [
                    'module' => $module->module,
                    'actions' => $module->total_actions,
                    'unique_users' => $module->unique_users,
                    'percentage' => $totalActions > 0 
                        ? round(($module->total_actions / $totalActions) * 100, 2) 
                        : 0,
                ];
            }),
        ];
    }

    /**
     * Clear stats cache.
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget('system_stats_7');
        Cache::forget('system_stats_30');
        Cache::forget('system_stats_90');
    }
}
