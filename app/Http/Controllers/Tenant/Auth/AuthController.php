<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\SchoolUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function showLoginForm()
    {
        return view('tenant.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Sinkronisasi status verifikasi dari database pusat
        if (!$user->hasVerifiedEmail()) {
            $centralUser = \App\Models\User::on(config('tenancy.database.central_connection'))
                ->where('email', $user->email)
                ->first();

            if ($centralUser && $centralUser->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }
        }

        // Validate School Status for school-related roles
        if (in_array($user->role, ['school_admin', 'teacher', 'staff'])) {
            if (!$user->school_unit_id) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Akun Anda tidak terhubung dengan unit sekolah manapun.',
                ]);
            }

            $school = $user->schoolUnit;
            if (!$school || $school->status !== 'active') {
                $message = match ($school?->status) {
                    'draft', 'setup' => 'Akun sekolah masih dalam proses setup oleh yayasan.',
                    'suspended' => 'Akun sekolah sedang dinonaktifkan oleh yayasan.',
                    'expired' => 'Masa aktif sistem sekolah telah habis.',
                    default => 'Akun sekolah saat ini tidak aktif.',
                };

                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => $message,
                ]);
            }
        }

        // Role-based redirection
        return redirect()->intended($this->redirectPath($user->role));
    }

    /**
     * Get redirect path based on role.
     */
    protected function redirectPath($role)
    {
        return match ($role) {
            'super_admin' => '/platform/dashboard',
            'foundation_admin' => '/foundation/dashboard',
            'school_admin', 'staff' => '/school/dashboard',
            'teacher' => '/teacher/dashboard',
            default => '/dashboard',
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
