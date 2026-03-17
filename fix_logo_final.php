<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Final Logo Fix ===\n";

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

// Correct paths
$tenantStoragePath = storage_path('tenant' . $tenant->id . '/app/public/' . $yayasan->logo);
$publicStoragePath = storage_path('app/public/' . $yayasan->logo);

echo "Tenant storage: " . $tenantStoragePath . "\n";
echo "Public storage: " . $publicStoragePath . "\n";

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
            $publicWebPath = public_path('storage/' . $yayasan->logo);
            if (file_exists($publicWebPath)) {
                echo "✓ File accessible via web\n";
                $url = asset('storage/' . $yayasan->logo);
                echo "  URL: " . $url . "\n";
            } else {
                echo "✗ File not accessible via web\n";
                echo "  Expected at: " . $publicWebPath . "\n";
            }
        } else {
            echo "✗ File copy verification failed\n";
        }
    } else {
        echo "✗ Failed to copy file\n";
    }
} else {
    echo "✗ File not found in tenant storage\n";
    
    // List actual files in tenant storage
    $tenantLogosDir = storage_path('tenant' . $tenant->id . '/app/public/logos');
    if (is_dir($tenantLogosDir)) {
        echo "Files in tenant logos directory:\n";
        $files = scandir($tenantLogosDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                echo "  - " . $file . "\n";
            }
        }
    }
}

echo "\n=== Clearing Caches ===\n";
// Clear Laravel caches
echo "Clearing application cache...\n";
exec('php artisan cache:clear 2>&1', $output1);
echo implode("\n", $output1) . "\n";

echo "Clearing view cache...\n";
exec('php artisan view:clear 2>&1', $output2);
echo implode("\n", $output2) . "\n";

echo "Clearing config cache...\n";
exec('php artisan config:clear 2>&1', $output3);
echo implode("\n", $output3) . "\n";

echo "\n=== Testing Complete ===\n";
echo "1. Clear browser cache (Ctrl+F5)\n";
echo "2. Visit: http://yayasan-hidayattul-amin.localhost:8000/yayasan/profil\n";
echo "3. Logo should now be visible\n";
echo "4. If still not visible, check browser developer tools (F12) Network tab\n";
