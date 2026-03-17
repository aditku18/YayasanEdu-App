<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Foundation;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\PlatformPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->period ?? 'month';
        
        // Overall Stats
        $stats = [
            'total_foundations' => Foundation::count(),
            'active_foundations' => Foundation::where('status', 'active')->count(),
            'trial_foundations' => Foundation::where('status', 'trial')->count(),
            'total_subscriptions' => Subscription::count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_revenue' => Transaction::where('status', 'success')->sum('amount'),
            'monthly_revenue' => Transaction::where('status', 'success')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
        ];

        // Growth Data
        $growthData = $this->getGrowthData($period);
        
        // Revenue Data
        $revenueData = $this->getRevenueData($period);
        
        // Foundation Status Distribution
        $foundationStatusData = [
            'active' => Foundation::where('status', 'active')->count(),
            'trial' => Foundation::where('status', 'trial')->count(),
            'suspended' => Foundation::where('status', 'suspended')->count(),
            'pending' => Foundation::where('status', 'pending')->count(),
        ];

        // Subscription Plan Distribution
        $planDistribution = Subscription::with('plan')
            ->where('status', 'active')
            ->get()
            ->groupBy('plan.name')
            ->map->count();

        return view('platform.statistics.index', compact(
            'stats', 'growthData', 'revenueData', 
            'foundationStatusData', 'planDistribution', 'period'
        ));
    }

    public function foundations(Request $request)
    {
        $period = $request->period ?? 'month';
        
        $foundations = Foundation::with(['plan', 'schools'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        // Foundation growth over time
        $growthData = Foundation::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->when($period === 'week', function ($query) {
                return $query->where('created_at', '>=', now()->subWeek());
            })
            ->when($period === 'month', function ($query) {
                return $query->where('created_at', '>=', now()->subMonth());
            })
            ->when($period === 'year', function ($query) {
                return $query->where('created_at', '>=', now()->subYear());
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('platform.statistics.foundations', compact('foundations', 'growthData', 'period'));
    }

    public function revenue(Request $request)
    {
        $period = $request->period ?? 'month';
        
        // Revenue data over time
        $revenueData = Transaction::where('status', 'success')
            ->selectRaw('DATE(created_at) as date, SUM(amount) as revenue')
            ->when($period === 'week', function ($query) {
                return $query->where('created_at', '>=', now()->subWeek());
            })
            ->when($period === 'month', function ($query) {
                return $query->where('created_at', '>=', now()->subMonth());
            })
            ->when($period === 'year', function ($query) {
                return $query->where('created_at', '>=', now()->subYear());
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Revenue by plan
        $revenueByPlan = Transaction::with('plan')
            ->where('status', 'success')
            ->get()
            ->groupBy('plan.name')
            ->map(function ($transactions) {
                return $transactions->sum('amount');
            });

        // Monthly recurring revenue (MRR)
        $mrr = Subscription::where('status', 'active')
            ->with('plan')
            ->get()
            ->sum(function ($subscription) {
                return $subscription->plan->price_per_month ?? 0;
            });

        return view('platform.statistics.revenue', compact('revenueData', 'revenueByPlan', 'mrr', 'period'));
    }

    public function growth(Request $request)
    {
        $period = $request->period ?? 'month';
        
        // New foundations per period
        $newFoundations = Foundation::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->when($period === 'week', function ($query) {
                return $query->where('created_at', '>=', now()->subWeek());
            })
            ->when($period === 'month', function ($query) {
                return $query->where('created_at', '>=', now()->subMonth());
            })
            ->when($period === 'year', function ($query) {
                return $query->where('created_at', '>=', now()->subYear());
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Churn rate
        $churnedFoundations = Foundation::where('status', 'suspended')
            ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
            ->when($period === 'week', function ($query) {
                return $query->where('updated_at', '>=', now()->subWeek());
            })
            ->when($period === 'month', function ($query) {
                return $query->where('updated_at', '>=', now()->subMonth());
            })
            ->when($period === 'year', function ($query) {
                return $query->where('updated_at', '>=', now()->subYear());
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Conversion rate (trial to paid)
        $conversionData = [
            'trial_conversions' => Foundation::where('status', '!=', 'trial')
                ->whereNotNull('trial_ends_at')
                ->count(),
            'total_trials' => Foundation::whereNotNull('trial_ends_at')->count(),
        ];

        return view('platform.statistics.growth', compact(
            'newFoundations', 'churnedFoundations', 'conversionData', 'period'
        ));
    }

    private function getGrowthData($period)
    {
        return Foundation::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->when($period === 'week', function ($query) {
                return $query->where('created_at', '>=', now()->subWeek());
            })
            ->when($period === 'month', function ($query) {
                return $query->where('created_at', '>=', now()->subMonth());
            })
            ->when($period === 'year', function ($query) {
                return $query->where('created_at', '>=', now()->subYear());
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getRevenueData($period)
    {
        return Transaction::where('status', 'success')
            ->selectRaw('DATE(created_at) as date, SUM(amount) as revenue')
            ->when($period === 'week', function ($query) {
                return $query->where('created_at', '>=', now()->subWeek());
            })
            ->when($period === 'month', function ($query) {
                return $query->where('created_at', '>=', now()->subMonth());
            })
            ->when($period === 'year', function ($query) {
                return $query->where('created_at', '>=', now()->subYear());
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
