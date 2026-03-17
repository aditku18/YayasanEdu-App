<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Fixing Tenant Storage Issue ===\n";

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
if (!$yayasan) {
    echo "No Yayasan found!\n";
    exit(1);
}

echo "Current logo: " . ($yayasan->logo ?? 'NULL') . "\n";

if ($yayasan->logo) {
    // Copy file from tenant storage to public storage
    $tenantStoragePath = storage_path('tenant' . $tenant->id . '/app/public/' . $yayasan->logo);
    $publicStoragePath = storage_path('app/public/' . $yayasan->logo);
    
    echo "Tenant storage path: " . $tenantStoragePath . "\n";
    echo "Public storage path: " . $publicStoragePath . "\n";
    
    if (file_exists($tenantStoragePath)) {
        echo "File exists in tenant storage\n";
        
        // Create directory if not exists
        $directory = dirname($publicStoragePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
            echo "Created directory: " . $directory . "\n";
        }
        
        // Copy file
        if (copy($tenantStoragePath, $publicStoragePath)) {
            echo "File copied successfully to public storage\n";
            
            // Verify
            if (file_exists($publicStoragePath)) {
                echo "File verified in public storage\n";
                echo "File size: " . filesize($publicStoragePath) . " bytes\n";
                
                // Test URL
                $url = asset('storage/' . $yayasan->logo);
                echo "Public URL: " . $url . "\n";
                
                // Test if accessible via web
                $publicWebPath = public_path('storage/' . $yayasan->logo);
                echo "Public web path: " . $publicWebPath . "\n";
                echo "Web file exists: " . (file_exists($publicWebPath) ? 'YES' : 'NO') . "\n";
            } else {
                echo "ERROR: File copy verification failed\n";
            }
        } else {
            echo "ERROR: Failed to copy file\n";
        }
    } else {
        echo "ERROR: File not found in tenant storage\n";
    }
}

echo "\n=== Testing Logo Display ===\n";
echo "To test logo display in browser:\n";
echo "1. Visit: http://yayasan-hidayattul-amin.localhost:8000/yayasan/profil\n";
echo "2. Check if logo appears\n";
echo "3. If not, check browser console for 404 errors\n";

echo "\n=== Manual Fix Options ===\n";
echo "If logo still doesn't appear, try these manual steps:\n";
echo "1. Clear browser cache\n";
echo "2. Run: php artisan cache:clear\n";
echo "3. Run: php artisan view:clear\n";
echo "4. Check browser developer tools Network tab for image loading\n";
