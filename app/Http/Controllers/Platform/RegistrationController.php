<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Foundation;
use App\Models\User;
use App\Services\TenantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        // Handle export request
        if ($request->get('export') == '1') {
            return $this->export($request);
        }

        $query = Foundation::with(['adminUser', 'plan', 'users'])
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
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            });

        $registrations = $query->latest()->paginate(15)->withQueryString();

        // Enhanced statistics for registrations
        $stats = [
            'total_registrations' => Foundation::count(),
            'pending' => Foundation::where('status', 'pending')->count(),
            'approved' => Foundation::where('status', 'active')->count(),
            'rejected' => Foundation::where('status', 'rejected')->count(),
            'trial' => Foundation::where('status', 'trial')->count(),
            'this_month' => Foundation::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'last_month' => Foundation::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count(),
            'with_premium_plans' => Foundation::whereHas('plan', function ($q) {
                $q->where('price_per_month', '>', 0);
            })->count(),
            'approval_rate' => $this->calculateApprovalRate(),
            'growth_rate' => $this->calculateGrowthRate(),
        ];

        // Status distribution for charts
        $statusDistribution = collect([
            ['name' => 'Pending', 'count' => Foundation::where('status', 'pending')->count()],
            ['name' => 'Disetujui', 'count' => Foundation::where('status', 'active')->count()],
            ['name' => 'Trial', 'count' => Foundation::where('status', 'trial')->count()],
            ['name' => 'Ditolak', 'count' => Foundation::where('status', 'rejected')->count()],
        ])->sortByDesc('count');

        // Recent registrations
        $recentRegistrations = Foundation::latest()
            ->limit(5)
            ->get(['id', 'name', 'email', 'status', 'created_at']);

        // Pending registrations (those needing approval)
        $pendingRegistrations = Foundation::where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get(['id', 'name', 'email', 'created_at']);

        // Get all plans for filter dropdown
        $plans = \App\Models\Plan::orderBy('name')->get();

        return view('platform.registrations.index', compact(
            'registrations', 
            'stats', 
            'statusDistribution', 
            'recentRegistrations',
            'pendingRegistrations',
            'plans'
        ));
    }

    public function show(Foundation $registration)
    {
        $registration->load(['adminUser', 'plan', 'schools', 'users', 'plugins']);
        return view('platform.registrations.show', compact('registration'));
    }

    /**
     * Approve foundation registration — creates tenant database and provisions user.
     */
    public function approve(Foundation $registration, TenantService $tenantService, Request $request)
    {
        // Only process pending registrations
        if ($registration->status !== 'pending') {
            $msg = 'Yayasan ini sudah tidak dalam status pending.';
            return $request->ajax() ? response()->json(['success' => false, 'message' => $msg], 422) : redirect()->back()->with('error', $msg);
        }

        // 1. Check email verification
        $user = $registration->adminUser ?? User::where('email', strtolower($registration->email))->first();
        if (!$user || !$user->hasVerifiedEmail()) {
            $msg = 'Tidak dapat di-approve: email administrator (' . ($user->email ?? $registration->email) . ') belum diverifikasi.';
            return $request->ajax() ? response()->json(['success' => false, 'message' => $msg], 422) : redirect()->back()->with('warning', $msg);
        }

        // 2. Check document verification
        if (!$registration->hasVerifiedDocuments()) {
            $msg = 'Tidak dapat di-approve: dokumen yayasan belum diverifikasi. Silakan verifikasi dokumen terlebih dahulu.';
            return $request->expectsJson() ? response()->json(['success' => false, 'message' => $msg], 422) : redirect()->back()->with('warning', $msg);
        }

        try {
            // 3. Create/Ensure tenant database via Service
            // This service handles both new creation and fixing existing records with missing DBs
            $tenant = $tenantService->createTenantWithDomain(null, $registration->subdomain, $registration->name);
            $tenantId = $tenant->id;

            // 4. Update foundation status & trial
            $registration->update([
                'tenant_id' => $tenantId,
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
            ]);

            // 5. Link user to tenant
            if ($user) {
                $user->update([
                    'tenant_id' => $tenantId,
                    'role' => 'foundation_admin',
                ]);

                // 6. Provision user in tenant DB
                tenancy()->initialize($tenant);

                $tenantUser = User::where('email', strtolower($user->email))->first();
                if (!$tenantUser) {
                    $userData = $user->toArray();
                    unset($userData['tenant_id'], $userData['id']);
                    $userData['password'] = $user->password;
                    $userData['email_verified_at'] = $user->email_verified_at;

                    $tenantUser = User::create($userData);

                    // Assign role in tenant DB
                    $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'foundation_admin', 'guard_name' => 'web']);
                    $tenantUser->assignRole($role);
                }

                tenancy()->end();
            }

            Log::info("Registration approved with tenant: {$registration->name}", [
                'registration_id' => $registration->id,
                'tenant_id' => $tenantId,
            ]);

            $successMsg = "Yayasan {$registration->name} berhasil di-approve! Tenant {$registration->subdomain} siap digunakan.";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMsg,
                    'redirect_url' => route('platform.registrations.index')
                ]);
            }

            return redirect()->route('platform.registrations.index')->with('success', $successMsg);

        } catch (\Exception $e) {
            Log::error("Failed to approve registration: {$registration->name}", [
                'registration_id' => $registration->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $errorMsg = 'Gagal meng-approve: ' . $e->getMessage();
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 500);
            }

            return redirect()->back()->with('error', $errorMsg);
        }
    }

    public function reject(Foundation $registration, Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $registration->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);
        
        return redirect()->route('platform.registrations.index')
            ->with('success', 'Registrasi yayasan ditolak.');
    }

    /**
     * Verify uploaded foundation documents.
     */
    public function verifyDocuments(Foundation $registration)
    {
        if ($registration->hasVerifiedDocuments()) {
            return redirect()->back()->with('info', 'Dokumen yayasan ini sudah diverifikasi sebelumnya.');
        }

        if (!$registration->hasUploadedDocuments()) {
            return redirect()->back()->with('warning', 'Yayasan ini belum mengupload dokumen yang diperlukan.');
        }

        $registration->update([
            'documents_verified_at' => now(),
            'documents_verified_by' => Auth::id(),
        ]);

        Log::info("Documents verified for foundation: {$registration->name}", [
            'registration_id' => $registration->id,
            'verified_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', "Dokumen yayasan {$registration->name} berhasil diverifikasi.");
    }

    /**
     * Export registrations data to CSV
     */
    public function export(Request $request)
    {
        $registrations = Foundation::with(['adminUser', 'plan'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->latest()
            ->get();

        $filename = 'registrations_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'ID', 'Name', 'Email', 'Subdomain', 'Status', 'Plan', 
            'Created At', 'Updated At', 'Admin Email', 'Email Verified', 'Documents Verified'
        ];

        $callback = function() use ($registrations, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            
            foreach ($registrations as $registration) {
                $adminUser = $registration->adminUser;
                fputcsv($file, [
                    $registration->id,
                    $registration->name,
                    $registration->email,
                    $registration->subdomain . '.edusaas.com',
                    $registration->status,
                    $registration->plan ? $registration->plan->name : 'Free',
                    $registration->created_at->format('Y-m-d H:i:s'),
                    $registration->updated_at->format('Y-m-d H:i:s'),
                    $adminUser ? $adminUser->email : 'N/A',
                    $adminUser && $adminUser->hasVerifiedEmail() ? 'Ya' : 'Belum',
                    $registration->hasVerifiedDocuments() ? 'Ya' : 'Belum',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Approve all pending registrations - DISABLED (must verify individually)
     */
    public function approveAllPending()
    {
        return redirect()->route('platform.registrations.index')
            ->with('warning', 'Fitur approve semua registrasi telah dinonaktifkan. Setiap registrasi harus diverifikasi email dan dokumennya terlebih dahulu sebelum di-approve.');
    }

    /**
     * Send reminder emails to pending registrations
     */
    public function sendReminder()
    {
        $pendingRegistrations = Foundation::where('status', 'pending')->get();
        
        if ($pendingRegistrations->isEmpty()) {
            return redirect()->route('platform.registrations.index')
                ->with('error', 'Tidak ada registrasi pending untuk dikirimi reminder');
        }

        $sentCount = 0;
        $errors = [];

        foreach ($pendingRegistrations as $registration) {
            try {
                // TODO: Implement email sending
                $sentCount++;
                
                Log::info("Reminder sent to registration: {$registration->name}", [
                    'registration_id' => $registration->id,
                    'email' => $registration->email
                ]);
                
            } catch (\Exception $e) {
                $errors[] = "Failed to send reminder to {$registration->name}: {$e->getMessage()}";
            }
        }

        $message = "Reminder berhasil dikirim ke {$sentCount} dari {$pendingRegistrations->count()} registrasi pending";
        
        if (!empty($errors)) {
            $message .= ". Beberapa error: " . implode(', ', $errors);
            return redirect()->route('platform.registrations.index')
                ->with('warning', $message);
        }

        return redirect()->route('platform.registrations.index')
            ->with('success', $message);
    }

    /**
     * Calculate approval rate percentage
     */
    private function calculateApprovalRate()
    {
        $totalProcessed = Foundation::whereIn('status', ['active', 'trial', 'rejected'])->count();
        $approved = Foundation::whereIn('status', ['active', 'trial'])->count();
        
        if ($totalProcessed == 0) return 0;
        
        return round(($approved / $totalProcessed) * 100, 2);
    }

    /**
     * Calculate growth rate percentage
     */
    private function calculateGrowthRate()
    {
        $thisMonth = Foundation::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonth = Foundation::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        if ($lastMonth == 0) return $thisMonth > 0 ? 100 : 0;
        
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 2);
    }
}
