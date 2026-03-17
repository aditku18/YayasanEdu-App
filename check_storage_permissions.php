<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHECK STORAGE PERMISSIONS AND DIRECTORIES ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "✓ Tenant found: " . $tenant->id . "\n";
    
    echo "\n=== CHECKING STORAGE STRUCTURE ===\n";
    
    // Check main storage paths
    $paths = [
        'Main Storage' => storage_path(),
        'App Storage' => storage_path('app'),
        'App Public' => storage_path('app/public'),
        'Public Storage' => public_path('storage'),
    ];
    
    foreach ($paths as $name => $path) {
        echo "\n$name: $path\n";
        echo "  Exists: " . (is_dir($path) ? 'YES' : 'NO') . "\n";
        if (is_dir($path)) {
            echo "  Writable: " . (is_writable($path) ? 'YES' : 'NO') . "\n";
            echo "  Permissions: " . substr(sprintf('%o', fileperms($path)), -4) . "\n";
        }
    }
    
    echo "\n=== CHECKING TENANT STORAGE ===\n";
    
    $tenantStorageBase = storage_path('tenant' . $tenant->id);
    echo "Tenant Storage Base: $tenantStorageBase\n";
    echo "  Exists: " . (is_dir($tenantStorageBase) ? 'YES' : 'NO') . "\n";
    
    if (is_dir($tenantStorageBase)) {
        echo "  Writable: " . (is_writable($tenantStorageBase) ? 'YES' : 'NO') . "\n";
        echo "  Permissions: " . substr(sprintf('%o', fileperms($tenantStorageBase)), -4) . "\n";
        
        // Check tenant app/public
        $tenantAppPublic = $tenantStorageBase . '/app/public';
        echo "\nTenant App/Public: $tenantAppPublic\n";
        echo "  Exists: " . (is_dir($tenantAppPublic) ? 'YES' : 'NO') . "\n";
        
        if (!is_dir($tenantAppPublic)) {
            echo "  Creating tenant app/public directory...\n";
            $mkdirResult = mkdir($tenantAppPublic, 0755, true);
            echo "  Create result: " . ($mkdirResult ? 'SUCCESS' : 'FAILED') . "\n";
        }
        
        if (is_dir($tenantAppPublic)) {
            echo "  Writable: " . (is_writable($tenantAppPublic) ? 'YES' : 'NO') . "\n";
            echo "  Permissions: " . substr(sprintf('%o', fileperms($tenantAppPublic)), -4) . "\n";
            
            // Test creating a file
            $testFile = $tenantAppPublic . '/test-permissions.txt';
            echo "  Testing file creation...\n";
            $testResult = file_put_contents($testFile, 'test content');
            echo "  File creation: " . ($testResult !== false ? 'SUCCESS' : 'FAILED') . "\n";
            
            if ($testResult !== false) {
                echo "  File exists: " . (file_exists($testFile) ? 'YES' : 'NO') . "\n";
                unlink($testFile);
                echo "  File cleanup: SUCCESS\n";
            }
        }
    }
    
    echo "\n=== CHECKING UPLOAD DIRECTORIES ===\n";
    
    $uploadDirs = [
        'temp' => 'temp',
        'temp_uploads' => 'temp_uploads',
        'uploads' => 'uploads',
        'uploads/foundations' => 'uploads/foundations',
        'uploads/foundations/1' => 'uploads/foundations/1',
    ];
    
    $tenant->run(function() use ($uploadDirs) {
        foreach ($uploadDirs as $name => $relativePath) {
            $fullPath = storage_path('app/public/' . $relativePath);
            echo "\n$name: $relativePath\n";
            echo "  Full path: $fullPath\n";
            echo "  Exists: " . (is_dir($fullPath) ? 'YES' : 'NO') . "\n";
            
            if (!is_dir($fullPath)) {
                echo "  Creating directory...\n";
                $mkdirResult = mkdir($fullPath, 0755, true);
                echo "  Create result: " . ($mkdirResult ? 'SUCCESS' : 'FAILED') . "\n";
            }
            
            if (is_dir($fullPath)) {
                echo "  Writable: " . (is_writable($fullPath) ? 'YES' : 'NO') . "\n";
                echo "  Permissions: " . substr(sprintf('%o', fileperms($fullPath)), -4) . "\n";
                
                // List files if directory exists
                $files = scandir($fullPath);
                $fileList = array_diff($files, ['.', '..']);
                if (!empty($fileList)) {
                    echo "  Files: " . implode(', ', array_slice($fileList, 0, 5)) . (count($fileList) > 5 ? '...' : '') . "\n";
                }
            }
        }
    });
    
    echo "\n=== CHECKING CURRENT LOGO STATUS ===\n";
    
    $tenant->run(function() {
        if (class_exists('App\Models\Foundation')) {
            $foundation = \App\Models\Foundation::find(1);
            if ($foundation) {
                echo "Foundation: " . $foundation->name . "\n";
                echo "Logo path in DB: " . ($foundation->logo_path ?? 'NULL') . "\n";
                
                if ($foundation->logo_path) {
                    // Check in different locations
                    $locations = [
                        'Tenant Storage' => storage_path('app/public/' . $foundation->logo_path),
                        'Public Storage' => public_path('storage/' . $foundation->logo_path),
                    ];
                    
                    foreach ($locations as $name => $path) {
                        echo "\n$name: $path\n";
                        echo "  Exists: " . (file_exists($path) ? 'YES' : 'NO') . "\n";
                        if (file_exists($path)) {
                            echo "  Size: " . filesize($path) . " bytes\n";
                            echo "  Writable: " . (is_writable($path) ? 'YES' : 'NO') . "\n";
                        }
                    }
                }
            }
        }
    });
    
} else {
    echo "❌ Tenant not found\n";
}

echo "\n=== PHP SETTINGS ===\n";
echo "Upload max file size: " . ini_get('upload_max_filesize') . "\n";
echo "Post max size: " . ini_get('post_max_size') . "\n";
echo "Max input time: " . ini_get('max_input_time') . "\n";
echo "Memory limit: " . ini_get('memory_limit') . "\n";
echo "File uploads: " . (ini_get('file_uploads') ? 'Enabled' : 'Disabled') . "\n";

echo "\n=== RECOMMENDATIONS ===\n";
echo "1. Ensure all storage directories are writable (755 permissions)\n";
echo "2. Check that tenant storage directories exist\n";
echo "3. Verify upload directories are created in tenant context\n";
echo "4. Test actual upload form for specific error messages\n";
echo "5. Check Laravel logs for upload errors\n";
