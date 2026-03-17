<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSchoolStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->school_unit_id) {
            $school = $user->schoolUnit;

            if ($school && $school->status !== 'active') {
                $message = match ($school->status) {
                    'draft', 'setup' => 'Akun sekolah masih dalam proses setup oleh yayasan.',
                    'suspended' => 'Akun sekolah sedang dinonaktifkan oleh yayasan.',
                    'expired' => 'Masa aktif sistem sekolah telah habis.',
                    default => 'Akun sekolah saat ini tidak aktif.',
                };

                // Logout user if not active
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => $message,
                ]);
            }
        }

        return $next($request);
    }
}
