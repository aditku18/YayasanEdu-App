<?php

namespace App\Plugins\PPDB\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PluginInstallation;
use App\Models\Plugin;

class CheckPPDBInstallation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get current foundation/tenant ID
        $foundationId = $this->getCurrentFoundationId($request);
        
        if (!$foundationId) {
            abort(403, 'Foundation context not found');
        }

        // Check if PPDB plugin is installed and active
        $plugin = Plugin::where('name', 'PPDB (Penerimaan Peserta Didik Baru)')->first();
        
        if (!$plugin) {
            abort(403, 'PPDB Plugin not found in marketplace');
        }

        $installation = PluginInstallation::where('plugin_id', $plugin->id)
            ->where('foundation_id', $foundationId)
            ->where('is_active', true)
            ->first();

        if (!$installation) {
            // For public routes, show a friendly message
            if ($this->isPublicRoute($request)) {
                return response()->view('ppdb::public.plugin-not-installed', [], 403);
            }

            // For admin routes, redirect to marketplace
            if ($this->isAdminRoute($request)) {
                return redirect()->route('platform.marketplace.show', $plugin->id)
                    ->with('error', 'PPDB Plugin tidak terinstall. Silakan install plugin terlebih dahulu.');
            }

            abort(403, 'PPDB Plugin tidak terinstall atau tidak aktif');
        }

        // Check plugin status
        if ($plugin->status !== 'active') {
            abort(403, 'PPDB Plugin sedang tidak aktif');
        }

        // Add plugin context to request
        $request->merge([
            'ppdb_plugin_installation' => $installation,
            'ppdb_plugin_settings' => $installation->settings ?? [],
        ]);

        return $next($request);
    }

    /**
     * Get current foundation ID from request
     */
    private function getCurrentFoundationId(Request $request): ?int
    {
        // Try to get from authenticated user
        if (auth()->check()) {
            $user = auth()->user();
            
            // For foundation admin
            if ($user->role === 'foundation_admin' && isset($user->foundation_id)) {
                return $user->foundation_id;
            }
            
            // For school admin
            if (isset($user->school_unit_id)) {
                // Get foundation from school unit
                $school = \App\Models\SchoolUnit::find($user->school_unit_id);
                return $school?->foundation_id;
            }
        }

        // Try to get from route parameter
        if ($request->route('foundation')) {
            return $request->route('foundation');
        }

        // Try to get from subdomain
        if ($request->tenant) {
            $tenant = \App\Models\Tenant::where('domain', $request->tenant)->first();
            return $tenant?->foundation_id;
        }

        return null;
    }

    /**
     * Check if current route is public
     */
    private function isPublicRoute(Request $request): bool
    {
        $publicRoutes = [
            'ppdb.public.index',
            'ppdb.public.register',
            'ppdb.public.store',
            'ppdb.public.success',
            'ppdb.public.check-status',
            'ppdb.public.tracking',
            'ppdb.public.upload',
            'ppdb.public.store-docs',
        ];

        return in_array($request->route()?->getName(), $publicRoutes);
    }

    /**
     * Check if current route is admin
     */
    private function isAdminRoute(Request $request): bool
    {
        return str_starts_with($request->route()?->getName(), 'ppdb.admin.');
    }
}
