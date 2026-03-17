<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Force Logo Sync ===\n";

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

// Force sync all logo files
$tenantLogosDir = storage_path('tenanttenant-yayasan-hidayattul-amin/app/public/logos');
$publicLogosDir = storage_path('app/public/logos');

echo "Syncing logos from:\n";
echo "  Source: " . $tenantLogosDir . "\n";
echo "  Target: " . $publicLogosDir . "\n";

if (is_dir($tenantLogosDir)) {
    // Create target directory
    if (!is_dir($publicLogosDir)) {
        mkdir($publicLogosDir, 0755, true);
        echo "Created target directory\n";
    }
    
    // Copy all files
    $files = scandir($tenantLogosDir);
    $copied = 0;
    
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $sourceFile = $tenantLogosDir . '/' . $file;
            $targetFile = $publicLogosDir . '/' . $file;
            
            if (is_file($sourceFile)) {
                if (copy($sourceFile, $targetFile)) {
                    echo "✓ Copied: " . $file . "\n";
                    $copied++;
                } else {
                    echo "✗ Failed to copy: " . $file . "\n";
                }
            }
        }
    }
    
    echo "\nCopied " . $copied . " files\n";
} else {
    echo "Source directory not found\n";
}

// Verify current logo
if ($yayasan->logo) {
    $publicPath = public_path('storage/' . $yayasan->logo);
    echo "\nCurrent logo verification:\n";
    echo "  Public path: " . $publicPath . "\n";
    echo "  Exists: " . (file_exists($publicPath) ? 'YES' : 'NO') . "\n";
    
    if (file_exists($publicPath)) {
        echo "  Size: " . filesize($publicPath) . " bytes\n";
        echo "  URL: " . Storage::url($yayasan->logo) . "\n";
        
        echo "\n🎉 LOGO SYNC COMPLETE!\n";
        echo "========================\n";
        echo "✅ Logo files synced to public storage\n";
        echo "✅ Current logo accessible\n";
        echo "✅ Ready for web display\n";
        
        echo "\n📋 Final Steps:\n";
        echo "1. Clear browser cache (Ctrl+F5)\n";
        echo "2. Visit: http://yayasan-hidayattul-amin.localhost:8000/yayasan/profil\n";
        echo "3. Logo should now be visible\n";
        echo "4. If still not visible, check browser console (F12) for errors\n";
    }
}

echo "\n=== Done ===\n";
