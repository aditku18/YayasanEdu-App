<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Fixing Tenant Logo Access ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "✓ Tenant found: " . $tenant->id . "\n";
    
    // Get tenant storage path
    $tenantStoragePath = storage_path('app/public');
    $publicStoragePath = public_path('storage');
    
    echo "Tenant storage path: " . $tenantStoragePath . "\n";
    echo "Public storage path: " . $publicStoragePath . "\n";
    
    // Check if public/storage is a symlink
    if (is_link($publicStoragePath)) {
        echo "✓ public/storage is a symlink\n";
        echo "  Target: " . readlink($publicStoragePath) . "\n";
        
        // Check if the target points to the right place
        $target = readlink($publicStoragePath);
        $expectedTarget = '../storage/app/public';
        
        if ($target === $expectedTarget) {
            echo "✓ Symlink points to correct location\n";
        } else {
            echo "✗ Symlink points to wrong location: $target (expected: $expectedTarget)\n";
        }
    } else {
        echo "✗ public/storage is not a symlink\n";
        
        // Remove and recreate
        if (is_dir($publicStoragePath)) {
            echo "Removing existing directory...\n";
            rmdir($publicStoragePath);
        }
        
        echo "Creating symbolic link...\n";
        $result = symlink('../storage/app/public', $publicStoragePath);
        echo "Symlink creation: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
    }
    
    // Run within tenant context to check logo
    $tenant->run(function() {
        echo "\n=== Checking Logo in Tenant Context ===\n";
        
        if (class_exists('App\Models\Yayasan')) {
            $yayasan = \App\Models\Yayasan::first();
            if ($yayasan && $yayasan->logo) {
                echo "Logo path: " . $yayasan->logo . "\n";
                
                // Check if file exists in tenant storage
                $tenantStorageFile = storage_path('app/public/' . $yayasan->logo);
                echo "Tenant storage file: " . $tenantStorageFile . "\n";
                echo "File exists: " . (file_exists($tenantStorageFile) ? 'YES' : 'NO') . "\n";
                
                // Check if file exists via public symlink
                $publicFile = public_path('storage/' . $yayasan->logo);
                echo "Public file: " . $publicFile . "\n";
                echo "Public file exists: " . (file_exists($publicFile) ? 'YES' : 'NO') . "\n";
                
                // Test URL generation
                $url = Storage::url($yayasan->logo);
                echo "Generated URL: " . $url . "\n";
                
                // Create a test file to verify access
                $testContent = "Logo access test - " . date('Y-m-d H:i:s');
                $testFile = 'logos/access-test.txt';
                
                Storage::disk('public')->put($testFile, $testContent);
                echo "Test file created: $testFile\n";
                echo "Test URL: " . Storage::url($testFile) . "\n";
                
                // Check if test file is accessible via public path
                $publicTestFile = public_path('storage/' . $testFile);
                echo "Public test file: " . $publicTestFile . "\n";
                echo "Public test file exists: " . (file_exists($publicTestFile) ? 'YES' : 'NO') . "\n";
                
                // Clean up
                Storage::disk('public')->delete($testFile);
                echo "Test file cleaned up\n";
            }
        }
    });
    
    echo "\n=== Testing Web Access ===\n";
    
    // Test with curl
    $testUrls = [
        'http://yayasan-kemala-bhayangkari.localhost:8000/storage/',
        'http://yayasan-kemala-bhayangkari.localhost:8000/storage/logos/',
    ];
    
    if (function_exists('curl_init')) {
        foreach ($testUrls as $url) {
            echo "Testing: $url\n";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            echo "  HTTP Code: $httpCode " . ($httpCode == 200 ? '✓' : '✗') . "\n";
        }
    }
    
} else {
    echo "✗ Tenant not found\n";
}

echo "\n=== Manual Instructions ===\n";
echo "If logo still doesn't show:\n";
echo "1. Clear browser cache\n";
echo "2. Restart Apache/XAMPP\n";
echo "3. Check .htaccess in public/ directory\n";
echo "4. Verify AllowOverride All in Apache config\n";
