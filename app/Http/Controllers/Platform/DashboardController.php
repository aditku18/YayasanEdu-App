<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Foundation;
use App\Models\SchoolUnit;
use App\Models\Student;
use App\Models\User;
use App\Models\Plan;
use App\Models\LoginLog;
use App\Models\ActivityLog;
use App\Services\Monitoring\SystemMonitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $systemMonitor;

    public function __construct(SystemMonitor $systemMonitor)
    {
        $this->systemMonitor = $systemMonitor;
    }

    /**
     * Display the platform dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // 1. Platform Admin always sees central dashboard
        if ($user->hasRole('platform_admin') || $user->hasRole('super_admin')) {
            return $this->renderPlatformDashboard();
        }

        // 2. Foundation Admin logic - redirect to tenant
        if ($user->hasRole('foundation_admin')) {
            $foundation = Foundation::where('email', $user->email)->first();
            
            if (!$foundation) {
                return view('waiting-approval', ['status' => 'not_found']);
            }

            // If approved (trial/active), redirect to their tenant domain
            if (in_array($foundation->status, ['trial', 'active']) && $foundation->subdomain) {
                $domain = $foundation->subdomain;
                // Check if domain already has .localhost, if not add it
                if (!str_ends_with($domain, '.localhost')) {
                    $domain .= '.localhost';
                }
                return redirect()->away('http://' . $domain . ':8000/login');
            }

            // Otherwise show status page (pending, rejected, expired)
            return view('waiting-approval', ['status' => $foundation->status, 'foundation' => $foundation]);
        }

        // Default fallback
        return view('waiting-approval', ['status' => 'pending']);
    }

    /**
     * Render the platform admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    protected function renderPlatformDashboard()
    {
        // Get comprehensive stats
        $stats = [
            'total_foundations' => Foundation::count(),
            'active_foundations' => Foundation::whereIn('status', ['trial', 'active'])->count(),
            'trial_foundations' => Foundation::where('status', 'trial')->count(),
            'pending_foundations' => Foundation::where('status', 'pending')->count(),
            'rejected_foundations' => Foundation::where('status', 'rejected')->count(),
            'total_schools' => SchoolUnit::count(),
            'active_schools' => SchoolUnit::where('status', 'active')->count(),
            'setup_schools' => SchoolUnit::where('status', 'setup')->count(),
            'total_students' => Student::count(),
            'total_users' => User::count(),
            'admin_users' => User::whereHas('roles', function($q) {
                $q->where('name', 'platform_admin');
            })->count(),
            'tenant_users' => User::whereDoesntHave('roles', function($q) {
                $q->where('name', 'platform_admin');
            })->count(),
            'total_plans' => Plan::count(),
            'active_plans' => Plan::where('is_active', true)->count(),
            'total_invoices' => class_exists('\App\Models\Invoice') ? \App\Models\Invoice::count() : 0,
            'monthly_revenue' => class_exists('\App\Models\Invoice') ? \App\Models\Invoice::where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('amount') : 0,
            'new_foundations_today' => Foundation::whereDate('created_at', now()->toDateString())->count(),
            'payments_today' => class_exists('\App\Models\Invoice') ? \App\Models\Invoice::where('status', 'paid')
                ->whereDate('paid_at', now()->toDateString())->count() : 0,
            'new_users_today' => User::whereDate('created_at', now()->toDateString())->count(),
            'tickets_today' => class_exists('\App\Models\Ticket') ? \App\Models\Ticket::whereDate('created_at', now()->toDateString())->count() : 0,
        ];

        // Get recent activities
        $recentActivities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($activity) {
                return [
                    'id' => $activity->id,
                    'user_name' => $activity->user->name ?? 'Unknown',
                    'action' => $activity->action,
                    'description' => $activity->description,
                    'created_at' => $activity->created_at->diffForHumans(),
                ];
            });

        // Get monthly growth data for charts
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyData[] = [
                'month' => $date->format('M'),
                'foundations' => Foundation::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count(),
                'schools' => SchoolUnit::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count(),
            ];
        }

        // Get distribution of plans for doughnut chart
        $planDistribution = [];
        if (class_exists('\App\Models\Subscription')) {
            $planDistribution = \App\Models\Subscription::where('status', 'active')
                ->with('plan')
                ->get()
                ->groupBy('plan.name')
                ->map(function($group) {
                    return $group->count();
                });
        }

        return view('dashboard', compact('stats', 'recentActivities', 'monthlyData', 'planDistribution'));
    }

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function serverStats()
    {
        $stats = $this->systemMonitor->getServerStats();
        return response()->json($stats);
    }

    /**
     * Get registration trend for chart.
     *
     * @param int $months
     * @return \Illuminate\Http\JsonResponse
     */
    public function registrationTrend(int $months = 12)
    {
        $trend = $this->systemMonitor->getFoundationRegistrationTrend($months);
        return response()->json($trend);
    }
}
