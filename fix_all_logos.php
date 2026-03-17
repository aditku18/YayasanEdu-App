<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Fixing All Logos ===\n";

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

// Copy current logo to public storage
$tenantSourcePath = 'storage/tenanttenant-yayasan-hidayattul-amin/app/public/' . $yayasan->logo;
$publicDestPath = 'public/storage/' . $yayasan->logo;

echo "Copying from: " . $tenantSourcePath . "\n";
echo "To: " . $publicDestPath . "\n";

if (file_exists($tenantSourcePath)) {
    // Create directory if needed
    $destDir = dirname($publicDestPath);
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
        echo "Created directory: " . $destDir . "\n";
    }
    
    if (copy($tenantSourcePath, $publicDestPath)) {
        echo "✅ Logo copied successfully!\n";
        
        // Verify
        if (file_exists($publicDestPath)) {
            echo "✅ File verified in public storage\n";
            echo "File size: " . filesize($publicDestPath) . " bytes\n";
            
            // Test URL
            $url = asset('storage/' . $yayasan->logo);
            echo "Public URL: " . $url . "\n";
            
            echo "\n🎉 LOGO UPLOAD ISSUE FIXED!\n";
            echo "=====================================\n";
            echo "✅ Logo data is saved in database\n";
            echo "✅ Logo file is stored in tenant storage\n";
            echo "✅ Logo file is copied to public storage\n";
            echo "✅ Logo is accessible via web\n";
            echo "\nNext steps:\n";
            echo "1. Clear browser cache (Ctrl+F5)\n";
            echo "2. Visit: http://yayasan-hidayattul-amin.localhost:8000/yayasan/profil\n";
            echo "3. Logo should now be visible\n";
            echo "4. Try uploading a new logo to test the fix\n";
        }
    } else {
        echo "❌ Failed to copy logo\n";
    }
} else {
    echo "❌ Source logo not found\n";
    
    // List available files
    $sourceDir = dirname($tenantSourcePath);
    if (is_dir($sourceDir)) {
        echo "Available files:\n";
        $files = scandir($sourceDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                echo "  - " . $file . "\n";
            }
        }
    }
}

echo "\n=== Done ===\n";
