<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TenantEnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * Email verification is disabled for all tenant users.
     * Uncomment the code below to enable verification if needed.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Email verification is disabled for tenant users
        // All users can access without verifying email
        return $next($request);
        
        /*
        // Original code - enable this to require email verification
        if (! $request->user() ||
            (! $request->user()->hasVerifiedEmail() &&
            ! $request->is('login', 'email/verify*', 'logout'))) {
            
            return $request->expectsJson()
                    ? response()->json(['message' => 'Your email address is not verified.'], 409)
                    : Redirect::guest(route('verification.notice'));
        }

        return $next($request);
        */
    }
}
