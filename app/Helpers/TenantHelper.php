<?php

use Illuminate\Support\Str;

if (!function_exists('tenant_asset')) {
    /**
     * Generate a tenant-aware URL for an asset stored on the public disk.
     *
     * When filesystem tenancy is enabled the `asset()` helper will prefix the
     * configured asset root (often `/tenancy/assets`). The public disk (which
     * is symlinked to `storage/app/public`) is typically accessed using
     * `asset('storage/…')`. However the tenancy asset route expects paths
     * relative to the `app/public` folder. Leaving the leading `storage/`
     * segment results in a 404 because the controller attempts to look for
     * `app/public/storage/…` which does not exist – the file lives in
     * `app/public/…`.
     *
     * This helper strips the `storage/` prefix if present, and then calls
     * the normal `asset()` helper so that tenant-aware routing is applied.
     *
     * @param string $path
     * @return string
     */
    function tenant_asset($path)
    {
        // Normalize input
        $path = ltrim($path, '/');

        if (Str::startsWith($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        // Build the URL using the tenancy asset route directly. This avoids
        // any surprises from the asset() helper's root configuration which
        // may include an extra "/storage" segment. By calling the route
        // ourselves we guarantee that the path passed to the controller is
        // exactly what we expect.
        return route('stancl.tenancy.asset', ['path' => $path]);
    }
}