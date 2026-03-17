<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PlatformAdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Allow access if user has super_admin or platform_admin role
        if (tenant() || !$request->user() || 
            (!$request->user()->hasRole('super_admin') && !$request->user()->hasRole('platform_admin'))) {
            abort(403, 'Akses ditolak. Anda bukan Administrator Platform.');
        }

        return $next($request);
    }
}
