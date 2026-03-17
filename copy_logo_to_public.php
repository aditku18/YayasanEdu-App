<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Copying Logo to Public Storage ===\n";

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

// Correct tenant storage path (without duplicate tenant id)
$tenantStoragePath = storage_path('tenant' . $tenant->id . '/app/public/' . $yayasan->logo);
$publicStoragePath = storage_path('app/public/' . $yayasan->logo);

echo "Looking for file at: " . $tenantStoragePath . "\n";

// Check if file exists in tenant storage
if (file_exists($tenantStoragePath)) {
    echo "✓ Found file in tenant storage\n";
    
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
    
    // Try alternative path
    $altPath = storage_path('tenanttenant-yayasan-hidayattul-amin/app/public/' . $yayasan->logo);
    echo "Trying alternative path: " . $altPath . "\n";
    
    if (file_exists($altPath)) {
        echo "✓ Found file at alternative path\n";
        
        // Create directory in public storage
        $directory = dirname($publicStoragePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
            echo "✓ Created directory: " . $directory . "\n";
        }
        
        // Copy file to public storage
        if (copy($altPath, $publicStoragePath)) {
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
                }
            } else {
                echo "✗ File copy verification failed\n";
            }
        } else {
            echo "✗ Failed to copy file\n";
        }
    } else {
        echo "✗ File not found at alternative path either\n";
    }
}

echo "\n=== Testing Complete ===\n";
echo "1. Clear browser cache (Ctrl+F5)\n";
echo "2. Visit: http://yayasan-hidayattul-amin.localhost:8000/yayasan/profil\n";
echo "3. Logo should now be visible\n";
