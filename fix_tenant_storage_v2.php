<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Fixing Tenant Storage Access ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "✓ Tenant found: " . $tenant->id . "\n";
    
    // Get tenant storage path
    $tenantStoragePath = storage_path('tenant' . $tenant->id . '/app/public');
    $publicStoragePath = public_path('storage');
    
    echo "Tenant storage path: " . $tenantStoragePath . "\n";
    echo "Public storage path: " . $publicStoragePath . "\n";
    
    // Check if tenant storage exists
    if (is_dir($tenantStoragePath)) {
        echo "✓ Tenant storage exists\n";
        
        // Get logo info
        $tenant->run(function() {
            if (class_exists('App\Models\Yayasan')) {
                $yayasan = \App\Models\Yayasan::first();
                if ($yayasan && $yayasan->logo) {
                    echo "Logo in database: " . $yayasan->logo . "\n";
                    
                    // Check if file exists in tenant storage
                    $tenantFile = storage_path('tenant' . tenant()->id . '/app/public/' . $yayasan->logo);
                    echo "Tenant file path: " . $tenantFile . "\n";
                    echo "File exists in tenant storage: " . (file_exists($tenantFile) ? 'YES' : 'NO') . "\n";
                    
                    if (file_exists($tenantFile)) {
                        echo "File size: " . filesize($tenantFile) . " bytes\n";
                        
                        // Copy file to main public storage
                        $publicFile = public_path('storage/' . $yayasan->logo);
                        $publicDir = dirname($publicFile);
                        
                        echo "Public file path: " . $publicFile . "\n";
                        echo "Public directory: " . $publicDir . "\n";
                        
                        // Create directory if needed
                        if (!is_dir($publicDir)) {
                            echo "Creating public directory...\n";
                            mkdir($publicDir, 0755, true);
                        }
                        
                        // Copy file
                        echo "Copying file to public storage...\n";
                        $copyResult = copy($tenantFile, $publicFile);
                        echo "Copy result: " . ($copyResult ? 'SUCCESS' : 'FAILED') . "\n";
                        
                        if ($copyResult) {
                            echo "✓ File copied to public storage\n";
                            echo "Public file exists: " . (file_exists($publicFile) ? 'YES' : 'NO') . "\n";
                            
                            // Test URL
                            $url = '/storage/' . $yayasan->logo;
                            echo "Test URL: " . $url . "\n";
                            echo "Full URL: http://yayasan-kemala-bhayangkari.localhost:8000" . $url . "\n";
                            
                            // Test HTTP access
                            if (function_exists('curl_init')) {
                                $testUrl = 'http://yayasan-kemala-bhayangkari.localhost:8000' . $url;
                                $ch = curl_init($testUrl);
                                curl_setopt($ch, CURLOPT_NOBODY, true);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                                curl_exec($ch);
                                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                curl_close($ch);
                                echo "HTTP Code: $httpCode " . ($httpCode == 200 ? '✓' : '✗') . "\n";
                            }
                        }
                    }
                }
            }
        });
    } else {
        echo "✗ Tenant storage does not exist\n";
    }
    
} else {
    echo "✗ Tenant not found\n";
}

echo "\n=== Alternative Fix: Create Tenant Storage Link ===\n";

// Create symbolic link for tenant storage
$tenantId = 'tenant-yayasan-kemala-bhayangkari';
$tenantStorageDir = storage_path('tenant' . $tenantId . '/app/public');
$tenantPublicLink = public_path('storage-tenant-' . $tenantId);

echo "Tenant storage: " . $tenantStorageDir . "\n";
echo "Tenant public link: " . $tenantPublicLink . "\n";

if (is_dir($tenantStorageDir)) {
    // Remove existing link if any
    if (is_link($tenantPublicLink)) {
        unlink($tenantPublicLink);
        echo "Removed existing link\n";
    }
    
    // Create symbolic link
    $result = symlink($tenantStorageDir, $tenantPublicLink);
    echo "Created tenant storage link: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
    
    if ($result) {
        echo "Tenant storage accessible at: /storage-tenant-" . $tenantId . "\n";
    }
}
