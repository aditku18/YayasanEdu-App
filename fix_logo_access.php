<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Fixing Logo Access Issue ===\n";

// Find the tenant
$tenant = \App\Models\Tenant::find('tenant-yayasan-hidayattul-amin');
if (!$tenant) {
    echo "Tenant not found!\n";
    exit(1);
}

// Initialize tenancy
tenancy()->initialize($tenant);

echo "Tenant initialized: " . $tenant->id . "\n";

// Get current yayasan data
$yayasan = \App\Models\Yayasan::first();
if (!$yayasan || !$yayasan->logo) {
    echo "No Yayasan or logo found!\n";
    exit(1);
}

echo "Current logo: " . $yayasan->logo . "\n";

// Paths
$tenantStoragePath = storage_path('tenant' . $tenant->id . '/app/public/' . $yayasan->logo);
$publicStoragePath = storage_path('app/public/' . $yayasan->logo);
$publicWebPath = public_path('storage/' . $yayasan->logo);

echo "Tenant storage: " . $tenantStoragePath . "\n";
echo "Public storage: " . $publicStoragePath . "\n";
echo "Public web: " . $publicWebPath . "\n";

// Check if file exists in tenant storage
if (file_exists($tenantStoragePath)) {
    echo "✓ File exists in tenant storage\n";
    
    // Create directory in public storage
    $directory = dirname($publicStoragePath);
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
        echo "✓ Created directory: " . $directory . "\n";
    }
    
    // Copy file to public storage
    if (copy($tenantStoragePath, $publicStoragePath)) {
        echo "✓ File copied to public storage\n";
        
        // Verify file exists
        if (file_exists($publicStoragePath)) {
            echo "✓ File verified in public storage\n";
            echo "  Size: " . filesize($publicStoragePath) . " bytes\n";
            
            // Check if accessible via web
            if (file_exists($publicWebPath)) {
                echo "✓ File accessible via web\n";
                $url = asset('storage/' . $yayasan->logo);
                echo "  URL: " . $url . "\n";
            } else {
                echo "✗ File not accessible via web (storage link issue)\n";
            }
        } else {
            echo "✗ File copy verification failed\n";
        }
    } else {
        echo "✗ Failed to copy file\n";
    }
} else {
    echo "✗ File not found in tenant storage\n";
}

echo "\n=== Creating Tenant Storage Link ===\n";
// Create symbolic link for tenant storage to make it accessible
$tenantPublicLink = public_path('storage/tenant-' . $tenant->id);
$tenantStorageTarget = storage_path('tenant' . $tenant->id . '/app/public');

echo "Creating link from: " . $tenantPublicLink . "\n";
echo "To target: " . $tenantStorageTarget . "\n";

if (is_link($tenantPublicLink)) {
    unlink($tenantPublicLink);
    echo "Removed existing link\n";
}

if (symlink($tenantStorageTarget, $tenantPublicLink)) {
    echo "✓ Tenant storage link created\n";
    
    // Update the logo URL in database to use tenant path
    $tenantLogoPath = 'tenant-' . $tenant->id . '/' . $yayasan->logo;
    echo "Updating logo path to: " . $tenantLogoPath . "\n";
    
    $yayasan->logo = $tenantLogoPath;
    $yayasan->save();
    
    echo "✓ Logo path updated in database\n";
    echo "New URL: " . asset('storage/' . $tenantLogoPath) . "\n";
} else {
    echo "✗ Failed to create tenant storage link\n";
}

echo "\n=== Testing Complete ===\n";
echo "1. Clear browser cache\n";
echo "2. Visit: http://yayasan-hidayattul-amin.localhost:8000/yayasan/profil\n";
echo "3. Logo should now be visible\n";
