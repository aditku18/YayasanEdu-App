<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class LogoStorageProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Listen for tenant initialization
        if (class_exists('\Stancl\Tenancy\Tenancy')) {
            \Stancl\Tenancy\Tenancy::initialized(function ($tenant) {
                // Ensure logo files are accessible from web
                $this->ensureLogosAccessible($tenant);
            });
        }
    }

    /**
     * Ensure logo files are copied to public storage for web access
     */
    private function ensureLogosAccessible($tenant): void
    {
        try {
            // Process specific tenants
            $supportedTenants = [
                'tenant-yayasan-hidayattul-amin',
                'tenant-yayasan-kemala-bhayangkari'
            ];
            
            if (in_array($tenant->id, $supportedTenants)) {
                $tenantStoragePath = storage_path($tenant->id . '/app/public/logos');
                $publicStoragePath = storage_path('app/public/logos');
                
                if (is_dir($tenantStoragePath)) {
                    // Create public storage directory if not exists
                    if (!is_dir($publicStoragePath)) {
                        mkdir($publicStoragePath, 0755, true);
                    }
                    
                    // Copy all logo files
                    $files = glob($tenantStoragePath . '/*');
                    foreach ($files as $file) {
                        if (is_file($file)) {
                            $filename = basename($file);
                            $destFile = $publicStoragePath . '/' . $filename;
                            
                            // Copy if newer or doesn't exist
                            if (!file_exists($destFile) || filemtime($file) > filemtime($destFile)) {
                                copy($file, $destFile);
                            }
                        }
                    }
                    
                    // Also copy to public web directory for direct access
                    $publicWebPath = public_path('storage/logos');
                    if (!is_dir($publicWebPath)) {
                        mkdir($publicWebPath, 0755, true);
                    }
                    
                    foreach ($files as $file) {
                        if (is_file($file)) {
                            $filename = basename($file);
                            $destWebFile = $publicWebPath . '/' . $filename;
                            
                            // Copy if newer or doesn't exist
                            if (!file_exists($destWebFile) || filemtime($file) > filemtime($destWebFile)) {
                                copy($file, $destWebFile);
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Log error but don't break the application
            \Log::error('Logo storage sync failed: ' . $e->getMessage());
        }
    }
}
