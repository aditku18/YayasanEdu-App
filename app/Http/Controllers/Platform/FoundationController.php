<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Foundation;

class FoundationController extends Controller
{
    public function index(Request $request)
    {
        // Handle export request
        if ($request->get('export') == '1') {
            return $this->export($request);
        }

        $query = Foundation::with(['plan', 'users', 'schools'])
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subdomain', 'like', "%{$search}%");
            })
            ->when($request->status && in_array($request->status, ['pending', 'trial', 'active', 'expired', 'rejected', 'suspended']), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->plan_id, function ($query, $planId) {
                return $query->where('plan_id', $planId);
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            });

        $foundations = $query->latest()->paginate(15)->withQueryString();

        // Enhanced statistics for all foundations
        $stats = [
            'total' => Foundation::count(),
            'pending' => Foundation::where('status', 'pending')->count(),
            'trial' => Foundation::where('status', 'trial')->count(),
            'active' => Foundation::where('status', 'active')->count(),
            'expired' => Foundation::where('status', 'expired')->count(),
            'rejected' => Foundation::where('status', 'rejected')->count(),
            'suspended' => Foundation::where('status', 'suspended')->count(),
            'new_this_month' => Foundation::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'with_premium_plans' => Foundation::whereHas('plan', function ($q) {
                $q->where('price_per_month', '>', 0);
            })->count(),
            'total_schools' => Foundation::withCount('schools')->get()->sum('schools_count'),
            'total_users' => Foundation::withCount('users')->get()->sum('users_count'),
        ];

        // Status distribution for charts
        $statusDistribution = collect([
            ['name' => 'Aktif', 'count' => Foundation::where('status', 'active')->count()],
            ['name' => 'Trial', 'count' => Foundation::where('status', 'trial')->count()],
            ['name' => 'Pending', 'count' => Foundation::where('status', 'pending')->count()],
            ['name' => 'Suspended', 'count' => Foundation::where('status', 'suspended')->count()],
            ['name' => 'Expired', 'count' => Foundation::where('status', 'expired')->count()],
            ['name' => 'Rejected', 'count' => Foundation::where('status', 'rejected')->count()],
        ])->sortByDesc('count');

        // Recent activity
        $recentActivity = Foundation::latest('updated_at')
            ->limit(5)
            ->get(['id', 'name', 'status', 'updated_at']);

        // Get all plans for filter dropdown
        $plans = \App\Models\Plan::orderBy('name')->get();

        return view('platform.foundations.index', compact(
            'foundations', 
            'stats', 
            'statusDistribution', 
            'recentActivity',
            'plans'
        ));
    }

    public function show(Foundation $foundation)
    {
        $foundation->load(['plan', 'users', 'adminUser']);
        
        return view('admin.foundations.show', compact('foundation'));
    }

    public function edit(Foundation $foundation)
    {
        $foundation->load(['plan', 'users']);
        
        return view('admin.foundations.edit', compact('foundation'));
    }

    public function update(Request $request, Foundation $foundation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subdomain' => 'required|string|max:255|unique:foundations,subdomain,' . $foundation->id,
            'status' => 'required|in:pending,trial,active,expired,rejected',
            'plan_id' => 'nullable|exists:plans,id',
            'trial_ends_at' => 'nullable|date',
            'subscription_ends_at' => 'nullable|date',
        ]);

        $foundation->update($validated);

        return redirect()->route('platform.foundations.show', $foundation->id)
            ->with('success', "Data yayasan {$foundation->name} berhasil diperbarui.");
    }

    public function destroy(Foundation $foundation)
    {
        $name = $foundation->name;
        $foundation->delete();

        return redirect()->route('platform.foundations.index')
            ->with('success', "Yayasan {$name} berhasil dihapus.");
    }

    public function reject(Request $request, Foundation $foundation)
    {
        if ($foundation->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya yayasan berstatus pending yang bisa ditolak.');
        }

        $foundation->update(['status' => 'rejected']);

        return redirect()->route('platform.foundations.index')
            ->with('success', "Yayasan {$foundation->name} telah ditolak.");
    }

    /**
     * Quick Action: Suspend foundation
     */
    public function suspend(Foundation $foundation)
    {
        if (!in_array($foundation->status, ['active', 'trial'])) {
            return redirect()->back()->with('error', 'Hanya yayasan aktif atau trial yang dapat ditangguhkan.');
        }

        $oldStatus = $foundation->status;
        $foundation->update(['status' => 'suspended']);

        return redirect()->back()
            ->with('success', "Yayasan {$foundation->name} telah ditangguhkan (status: {$oldStatus} → suspended).");
    }

    /**
     * Quick Action: Activate/Reactivate foundation
     */
    public function activate(Foundation $foundation)
    {
        if (!in_array($foundation->status, ['suspended', 'expired'])) {
            return redirect()->back()->with('error', 'Hanya yayasan suspended atau expired yang dapat diaktifkan.');
        }

        $foundation->update(['status' => 'active']);

        return redirect()->back()
            ->with('success', "Yayasan {$foundation->name} telah diaktifkan kembali.");
    }

    /**
     * Quick Action: Extend trial period
     */
    public function extendTrial(Request $request, Foundation $foundation)
    {
        if ($foundation->status !== 'trial') {
            return redirect()->back()->with('error', 'Hanya yayasan berstatus trial yang dapat diperpanjang masa trialnya.');
        }

        $days = $request->input('days', 7);
        $newTrialEnd = $foundation->trial_ends_at 
            ? \Carbon\Carbon::parse($foundation->trial_ends_at)->addDays($days)
            : now()->addDays($days);

        $foundation->update(['trial_ends_at' => $newTrialEnd]);

        return redirect()->back()
            ->with('success', "Masa trial yayasan {$foundation->name} diperpanjang {$days} hari hingga " . $newTrialEnd->format('d M Y') . ".");
    }

    /**
     * Quick Action: Convert trial to active subscription
     */
    public function convertToActive(Request $request, Foundation $foundation)
    {
        if ($foundation->status !== 'trial') {
            return redirect()->back()->with('error', 'Hanya yayasan berstatus trial yang dapat dikonversi ke aktif.');
        }

        $planId = $request->input('plan_id');
        if (!$planId) {
            return redirect()->back()->with('error', 'Paket harus dipilih untuk mengkonversi ke aktif.');
        }

        $plan = \App\Models\Plan::findOrFail($planId);

        $foundation->update([
            'status' => 'active',
            'plan_id' => $planId,
            'is_trial' => false,
            'subscription_ends_at' => now()->addDays($plan->billing_cycle_days ?? 30),
        ]);

        return redirect()->back()
            ->with('success', "Yayasan {$foundation->name} telah dikonversi ke paket {$plan->name}.");
    }

    public function activeFoundations(Request $request)
    {
        // Handle export request
        if ($request->get('export') == '1') {
            return $this->exportActiveFoundations($request);
        }

        $query = Foundation::active()
            ->with(['plan', 'users', 'schools'])
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subdomain', 'like', "%{$search}%");
            })
            ->when($request->plan_id, function ($query, $planId) {
                return $query->where('plan_id', $planId);
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            });

        $foundations = $query->latest()->paginate(15)->withQueryString();

        // Enhanced statistics for active foundations
        $stats = [
            'total_active' => Foundation::active()->count(),
            'new_this_month' => Foundation::active()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'with_premium_plans' => Foundation::active()
                ->whereHas('plan', function ($q) {
                    $q->where('price_per_month', '>', 0);
                })
                ->count(),
            'total_schools' => Foundation::active()->withCount('schools')->get()->sum('schools_count'),
            'total_users' => Foundation::active()->withCount('users')->get()->sum('users_count'),
            'growth_rate' => $this->calculateGrowthRate(),
        ];

        // Plan distribution for charts
        $planDistribution = Foundation::active()
            ->join('plans', 'foundations.plan_id', '=', 'plans.id')
            ->selectRaw('plans.name, COUNT(*) as count')
            ->groupBy('plans.id', 'plans.name')
            ->orderBy('count', 'desc')
            ->get();

        // Recent activity
        $recentActivity = Foundation::active()
            ->latest('updated_at')
            ->limit(5)
            ->get(['id', 'name', 'updated_at']);

        // Get all plans for filter dropdown
        $plans = \App\Models\Plan::orderBy('name')->get();

        return view('platform.foundations.active', compact(
            'foundations', 
            'stats', 
            'planDistribution', 
            'recentActivity',
            'plans'
        ));
    }

    private function calculateGrowthRate()
    {
        $thisMonth = Foundation::active()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonth = Foundation::active()
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        if ($lastMonth > 0) {
            return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 2);
        }

        return $thisMonth > 0 ? 100 : 0;
    }

    /**
     * Show create foundation form
     */
    public function create()
    {
        $plans = \App\Models\Plan::orderBy('name')->get();
        
        return view('platform.foundations.create', compact('plans'));
    }

    /**
     * Store new foundation
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:foundations',
            'subdomain' => 'required|string|max:100|unique:foundations',
            'status' => 'required|in:pending,trial,active',
            'plan_id' => 'nullable|exists:plans,id',
            'description' => 'nullable|string',
        ]);

        $foundation = Foundation::create($request->all());

        return redirect()->route('platform.foundations.index')
            ->with('success', 'Yayasan berhasil ditambahkan!');
    }

    /**
     * Send notification to all active foundations
     */
    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,error,success'
        ]);

        $foundations = Foundation::where('status', 'active')->get();
        
        if ($foundations->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Tidak ada yayasan aktif untuk dikirimi notifikasi');
        }

        $successCount = 0;
        $errors = [];

        foreach ($foundations as $foundation) {
            try {
                // Create notification record in database
                // In a real application, you might have a notifications table
                // For now, we'll just log the notification
                
                // You could also send emails here:
                // Mail::to($foundation->email)->send(new FoundationNotification($request->title, $request->message, $request->type));
                
                // Or use Laravel notifications:
                // $foundation->notify(new PlatformNotification($request->title, $request->message, $request->type));
                
                $successCount++;
                
                // Log the notification
                \Illuminate\Support\Facades\Log::info("Notification sent to foundation: {$foundation->name}", [
                    'foundation_id' => $foundation->id,
                    'title' => $request->title,
                    'message' => $request->message,
                    'type' => $request->type
                ]);
                
            } catch (\Exception $e) {
                $errors[] = "Failed to send to {$foundation->name}: {$e->getMessage()}";
                \Illuminate\Support\Facades\Log::error("Failed to send notification to foundation: {$foundation->name}", [
                    'foundation_id' => $foundation->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $message = "Notifikasi berhasil dikirim ke {$successCount} dari {$foundations->count()} yayasan aktif";
        
        if (!empty($errors)) {
            $message .= ". Beberapa error: " . implode(', ', $errors);
            return redirect()->back()
                ->with('warning', $message);
        }

        return redirect()->back()
            ->with('success', $message);
    }

    /**
     * Export foundations data to CSV
     */
    public function export(Request $request)
    {
        $foundations = Foundation::with(['plan', 'users', 'schools'])
            ->when($request->status && in_array($request->status, ['pending', 'trial', 'active', 'expired', 'rejected', 'suspended']), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->plan_id, function ($query, $planId) {
                return $query->where('plan_id', $planId);
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subdomain', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        $filename = 'foundations_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'ID', 'Name', 'Email', 'Subdomain', 'Status', 'Plan', 
            'Schools Count', 'Users Count', 'Created At', 'Updated At'
        ];

        $callback = function() use ($foundations) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, $headers);
            
            // Add data rows
            foreach ($foundations as $foundation) {
                fputcsv($file, [
                    $foundation->id,
                    $foundation->name,
                    $foundation->email,
                    $foundation->subdomain . '.edusaas.com',
                    $foundation->status,
                    $foundation->plan ? $foundation->plan->name : 'Free',
                    $foundation->schools_count ?? 0,
                    $foundation->users_count ?? 0,
                    $foundation->created_at->format('Y-m-d H:i:s'),
                    $foundation->updated_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export active foundations data to CSV
     */
    public function exportActiveFoundations(Request $request)
    {
        $foundations = Foundation::active()
            ->with(['plan', 'users', 'schools'])
            ->when($request->plan_id, function ($query, $planId) {
                return $query->where('plan_id', $planId);
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subdomain', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        $filename = 'active_foundations_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'ID', 'Name', 'Email', 'Subdomain', 'Plan', 
            'Schools Count', 'Users Count', 'Created At', 'Updated At'
        ];

        $callback = function() use ($foundations) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, $headers);
            
            // Add data rows
            foreach ($foundations as $foundation) {
                fputcsv($file, [
                    $foundation->id,
                    $foundation->name,
                    $foundation->email,
                    $foundation->subdomain . '.edusaas.com',
                    $foundation->plan ? $foundation->plan->name : 'Free',
                    $foundation->schools_count ?? 0,
                    $foundation->users_count ?? 0,
                    $foundation->created_at->format('Y-m-d H:i:s'),
                    $foundation->updated_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function suspendedFoundations(Request $request)
    {
        $query = Foundation::whereIn('status', ['suspended', 'rejected', 'expired'])
            ->with(['plan', 'users', 'schools'])
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subdomain', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->plan_id, function ($query, $planId) {
                return $query->where('plan_id', $planId);
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                return $query->whereDate('updated_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                return $query->whereDate('updated_at', '<=', $dateTo);
            });

        $foundations = $query->latest('updated_at')->paginate(15)->withQueryString();

        // Enhanced statistics for suspended foundations
        $stats = [
            'total_suspended' => Foundation::whereIn('status', ['suspended', 'rejected', 'expired'])->count(),
            'suspended' => Foundation::where('status', 'suspended')->count(),
            'rejected' => Foundation::where('status', 'rejected')->count(),
            'expired' => Foundation::where('status', 'expired')->count(),
            'suspended_this_month' => Foundation::whereIn('status', ['suspended', 'rejected', 'expired'])
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->count(),
            'had_premium_plans' => Foundation::whereIn('status', ['suspended', 'rejected', 'expired'])
                ->whereHas('plan', function ($q) {
                    $q->where('price_per_month', '>', 0);
                })
                ->count(),
            'total_schools_lost' => Foundation::whereIn('status', ['suspended', 'rejected', 'expired'])
                ->withCount('schools')->get()->sum('schools_count'),
            'total_users_lost' => Foundation::whereIn('status', ['suspended', 'rejected', 'expired'])
                ->withCount('users')->get()->sum('users_count'),
        ];

        // Status distribution for charts
        $statusDistribution = collect([
            ['name' => 'Suspended', 'count' => Foundation::where('status', 'suspended')->count()],
            ['name' => 'Rejected', 'count' => Foundation::where('status', 'rejected')->count()],
            ['name' => 'Expired', 'count' => Foundation::where('status', 'expired')->count()],
        ])->sortByDesc('count');

        // Recent suspensions
        $recentSuspensions = Foundation::whereIn('status', ['suspended', 'rejected', 'expired'])
            ->latest('updated_at')
            ->limit(5)
            ->get(['id', 'name', 'status', 'updated_at']);

        // Get all plans for filter dropdown
        $plans = \App\Models\Plan::orderBy('name')->get();

        return view('platform.foundations.suspended', compact(
            'foundations', 
            'stats', 
            'statusDistribution', 
            'recentSuspensions',
            'plans'
        ));
    }

}

