<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing Logo Upload Process ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "Tenant found: " . $tenant->id . "\n";
    
    // Test logo upload in tenant context
    $tenant->run(function() {
        echo "=== Testing in tenant context ===\n";
        
        // Check current logo status
        if (class_exists('App\Models\Yayasan')) {
            $yayasan = \App\Models\Yayasan::first();
            if ($yayasan) {
                echo "Current logo in database: " . ($yayasan->logo ?? 'NULL') . "\n";
                
                // Test if we can update the logo
                try {
                    echo "Testing logo update...\n";
                    
                    // Create a test logo path
                    $testLogoPath = 'logos/test-logo-' . time() . '.png';
                    
                    // Try to update the logo field
                    $yayasan->logo = $testLogoPath;
                    $result = $yayasan->save();
                    
                    echo "Logo update result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
                    echo "New logo in database: " . $yayasan->logo . "\n";
                    
                    // Check if the path exists in storage
                    echo "Test logo path exists in storage: " . (Storage::disk('public')->exists($testLogoPath) ? 'YES' : 'NO') . "\n";
                    
                    // Restore original logo
                    $yayasan->logo = 'logos/VOn2OQvz7cD4gxuQv4wjKagHS5IOnb4PTcWRWoZS.png';
                    $yayasan->save();
                    echo "Original logo restored\n";
                    
                } catch (Exception $e) {
                    echo "Error updating logo: " . $e->getMessage() . "\n";
                }
            }
        }
        
        // Check storage directory structure
        echo "\n=== Checking storage structure ===\n";
        $publicPath = storage_path('app/public');
        echo "Public storage path: " . $publicPath . "\n";
        
        if (is_dir($publicPath)) {
            echo "Public storage exists\n";
            
            // Check logos directory
            $logosDir = $publicPath . '/logos';
            if (is_dir($logosDir)) {
                echo "Logos directory exists\n";
                $files = scandir($logosDir);
                echo "Logos directory contents: " . implode(', ', array_diff($files, ['.', '..'])) . "\n";
                
                // Check permissions
                echo "Logos directory permissions: " . substr(sprintf('%o', fileperms($logosDir)), -4) . "\n";
            } else {
                echo "Logos directory does NOT exist\n";
                
                // Try to create it
                try {
                    mkdir($logosDir, 0755, true);
                    echo "Logos directory created successfully\n";
                } catch (Exception $e) {
                    echo "Failed to create logos directory: " . $e->getMessage() . "\n";
                }
            }
        }
        
        // Test file upload simulation
        echo "\n=== Testing file upload simulation ===\n";
        try {
            // Create a test file
            $testContent = "Test logo content";
            $testFileName = 'logos/upload-test-' . time() . '.txt';
            
            $result = Storage::disk('public')->put($testFileName, $testContent);
            echo "Test file upload result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
            
            if ($result) {
                echo "Test file exists in storage: " . (Storage::disk('public')->exists($testFileName) ? 'YES' : 'NO') . "\n";
                echo "Test file URL: " . Storage::url($testFileName) . "\n";
                
                // Clean up
                Storage::disk('public')->delete($testFileName);
                echo "Test file cleaned up\n";
            }
        } catch (Exception $e) {
            echo "Error in file upload test: " . $e->getMessage() . "\n";
        }
    });
} else {
    echo "Tenant not found\n";
}

echo "\n=== Checking PHP upload settings ===\n";
echo "Upload max file size: " . ini_get('upload_max_filesize') . "\n";
echo "Post max size: " . ini_get('post_max_size') . "\n";
echo "Max input time: " . ini_get('max_input_time') . "\n";
echo "Memory limit: " . ini_get('memory_limit') . "\n";
