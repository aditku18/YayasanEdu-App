<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Foundation;
use Illuminate\Auth\Notifications\VerifyEmail;

class EmailVerificationController extends Controller
{
    /**
     * Tampilkan daftar yayasan beserta status verifikasi email user-nya.
     */
    public function index(Request $request)
    {
        $query = Foundation::with(['plan', 'users'])
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subdomain', 'like', "%{$search}%");
            })
            ->when($request->verification_status, function ($query, $status) {
                if ($status === 'verified') {
                    $query->whereHas('users', function ($q) {
                        $q->whereNotNull('email_verified_at');
                    });
                } elseif ($status === 'unverified') {
                    $query->whereHas('users', function ($q) {
                        $q->whereNull('email_verified_at');
                    })->orWhereDoesntHave('users');
                }
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

        // Enhanced statistics for email verification
        $stats = [
            'total_foundations' => Foundation::count(),
            'total_users' => User::count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
            'foundations_with_verified_users' => Foundation::whereHas('users', function ($q) {
                $q->whereNotNull('email_verified_at');
            })->count(),
            'foundations_with_unverified_users' => Foundation::whereHas('users', function ($q) {
                $q->whereNull('email_verified_at');
            })->orWhereDoesntHave('users')->count(),
            'verified_this_month' => User::whereNotNull('email_verified_at')
                ->whereMonth('email_verified_at', now()->month)
                ->whereYear('email_verified_at', now()->year)
                ->count(),
            'verification_rate' => $this->calculateVerificationRate(),
        ];

        // Verification status distribution for charts
        $verificationDistribution = collect([
            ['name' => 'Terverifikasi', 'count' => $stats['verified_users']],
            ['name' => 'Belum Terverifikasi', 'count' => $stats['unverified_users']],
        ])->sortByDesc('count');

        // Recent verifications
        $recentVerifications = User::whereNotNull('email_verified_at')
            ->latest('email_verified_at')
            ->limit(5)
            ->get(['id', 'name', 'email', 'email_verified_at']);

        // Pending verifications (users who haven't verified)
        $pendingVerifications = User::whereNull('email_verified_at')
            ->latest()
            ->limit(5)
            ->get(['id', 'name', 'email', 'created_at']);

        // Get all plans for filter dropdown
        $plans = \App\Models\Plan::orderBy('name')->get();

        return view('platform.email-verifications.index', compact(
            'foundations', 
            'stats', 
            'verificationDistribution', 
            'recentVerifications',
            'pendingVerifications',
            'plans'
        ));
    }

    /**
     * Get users for a specific foundation via AJAX.
     */
    public function getUsers(Foundation $foundation)
    {
        $users = $foundation->users()->select([
            'users.id', 
            'users.name', 
            'users.email', 
            'users.email_verified_at', 
            'users.created_at'
        ])->get();
        
        // Fallback to adminUser if no users found (common for pending foundations without tenant)
        if ($users->isEmpty() && $foundation->adminUser) {
            $users = collect([$foundation->adminUser]);
        }

        $users = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_verified' => $user->hasVerifiedEmail(),
                'verified_at' => $user->email_verified_at ? $user->email_verified_at->format('d M Y H:i') : null,
                'created_at' => $user->created_at->format('d M Y H:i'),
                'verify_url' => route('platform.email-verifications.verify', $user->id),
                'resend_url' => route('platform.email-verifications.resend', $user->id),
            ];
        });

        return response()->json(['users' => $users]);
    }

    /**
     * Verifikasi email user secara manual berdasarkan alamat email.
     */
    public function verifyByEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak ditemukan di sistem.',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return redirect()->back()->with('info', "Email {$user->email} sudah terverifikasi sebelumnya.");
        }

        $user->email_verified_at = now();
        $user->save();

        return redirect()->back()->with('success', "Email {$user->email} berhasil diverifikasi secara manual.");
    }

    /**
     * Verifikasi email user secara manual oleh platform admin.
     */
    public function verify(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return redirect()->back()->with('info', 'Email user ini sudah terverifikasi sebelumnya.');
        }

        $user->email_verified_at = now();
        $user->save();

        return redirect()->back()->with('success', "Email {$user->email} berhasil diverifikasi secara manual.");
    }

    /**
     * Kirim ulang email verifikasi ke user.
     */
    public function resend(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return redirect()->back()->with('info', 'Email user ini sudah terverifikasi.');
        }

        $user->notify(new VerifyEmail);

        return redirect()->back()->with('success', "Email verifikasi berhasil dikirim ulang ke {$user->email}.");
    }

    /**
     * Calculate verification rate percentage
     */
    private function calculateVerificationRate()
    {
        $totalUsers = User::count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        
        if ($totalUsers == 0) return 0;
        
        return round(($verifiedUsers / $totalUsers) * 100, 2);
    }
}
