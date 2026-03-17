<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FINAL VERIFICATION ===\n";

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

echo "Current logo in database: " . ($yayasan->logo ?? 'NULL') . "\n";

if ($yayasan->logo) {
    // Check all file locations
    $tenantPath = storage_path('tenanttenant-yayasan-hidayattul-amin/app/public/' . $yayasan->logo);
    $centralPath = storage_path('app/public/' . $yayasan->logo);
    $webPath = public_path('storage/' . $yayasan->logo);
    
    echo "\n=== File Status ===\n";
    echo "Tenant storage: " . (file_exists($tenantPath) ? '✅ EXISTS' : '❌ NOT FOUND') . "\n";
    echo "Central storage: " . (file_exists($centralPath) ? '✅ EXISTS' : '❌ NOT FOUND') . "\n";
    echo "Web accessible: " . (file_exists($webPath) ? '✅ EXISTS' : '❌ NOT FOUND') . "\n";
    
    if (file_exists($webPath)) {
        echo "\n🎉 SUCCESS! Logo is now web accessible!\n";
        echo "=====================================\n";
        
        $url = Storage::url($yayasan->logo);
        echo "Storage URL: " . $url . "\n";
        echo "Asset URL: " . asset('storage/' . $yayasan->logo) . "\n";
        
        echo "\n📋 Instructions:\n";
        echo "1. Clear browser cache (Ctrl+F5)\n";
        echo "2. Visit: http://yayasan-hidayattul-amin.localhost:8000/yayasan/profil\n";
        echo "3. Logo should now be visible\n";
        echo "4. Test uploading a new logo via web interface\n";
        
        echo "\n🔧 Controller Fix Applied:\n";
        echo "- Logo upload saves to tenant storage\n";
        echo "- File automatically copied to public storage\n";
        echo "- Web can access the logo via /storage/ path\n";
        
    } else {
        echo "\n❌ Logo still not accessible\n";
        echo "Expected web path: " . $webPath . "\n";
    }
    
    // Test URL accessibility
    echo "\n=== URL Test ===\n";
    $testUrl = 'http://localhost' . Storage::url($yayasan->logo);
    echo "Testing: " . $testUrl . "\n";
    
    $headers = @get_headers($testUrl);
    if ($headers && strpos($headers[0], '200') !== false) {
        echo "✅ URL accessible (200 OK)\n";
    } else {
        echo "❌ URL not accessible\n";
        if ($headers) {
            echo "Status: " . $headers[0] . "\n";
        }
    }
}

echo "\n=== Verification Complete ===\n";
