<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Copy Logo to Public Storage ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "✓ Tenant found: " . $tenant->id . "\n";
    
    // Get paths
    $tenantId = 'tenant-yayasan-kemala-bhayangkari';
    $tenantStorageDir = storage_path('tenant' . $tenantId . '/app/public');
    $publicStorageDir = public_path('storage');
    
    echo "Tenant storage: " . $tenantStorageDir . "\n";
    echo "Public storage: " . $publicStorageDir . "\n";
    
    // Run in tenant context to get logo info
    $tenant->run(function() use ($tenantStorageDir, $publicStorageDir) {
        if (class_exists('App\Models\Yayasan')) {
            $yayasan = \App\Models\Yayasan::first();
            if ($yayasan && $yayasan->logo) {
                echo "Logo path: " . $yayasan->logo . "\n";
                
                // Source file in tenant storage
                $sourceFile = $tenantStorageDir . '/' . $yayasan->logo;
                echo "Source file: " . $sourceFile . "\n";
                echo "Source exists: " . (file_exists($sourceFile) ? 'YES' : 'NO') . "\n";
                
                if (file_exists($sourceFile)) {
                    echo "Source file size: " . filesize($sourceFile) . " bytes\n";
                    
                    // Destination file in public storage
                    $destFile = $publicStorageDir . '/' . $yayasan->logo;
                    $destDir = dirname($destFile);
                    
                    echo "Destination file: " . $destFile . "\n";
                    echo "Destination directory: " . $destDir . "\n";
                    
                    // Create destination directory if needed
                    if (!is_dir($destDir)) {
                        echo "Creating destination directory...\n";
                        mkdir($destDir, 0755, true);
                        echo "Directory created: " . (is_dir($destDir) ? 'YES' : 'NO') . "\n";
                    }
                    
                    // Copy the file
                    echo "Copying file...\n";
                    $copyResult = copy($sourceFile, $destFile);
                    echo "Copy result: " . ($copyResult ? 'SUCCESS' : 'FAILED') . "\n";
                    
                    if ($copyResult) {
                        echo "✓ File copied successfully\n";
                        echo "Destination exists: " . (file_exists($destFile) ? 'YES' : 'NO') . "\n";
                        echo "Destination size: " . filesize($destFile) . " bytes\n";
                        
                        // Test URL access
                        $url = '/storage/' . $yayasan->logo;
                        echo "Test URL: " . $url . "\n";
                        echo "Full URL: http://yayasan-kemala-bhayangkari.localhost:8000" . $url . "\n";
                        
                        // Test HTTP request
                        if (function_exists('curl_init')) {
                            $testUrl = 'http://yayasan-kemala-bhayangkari.localhost:8000' . $url;
                            echo "Testing HTTP access: " . $testUrl . "\n";
                            
                            $ch = curl_init($testUrl);
                            curl_setopt($ch, CURLOPT_NOBODY, true);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                            curl_exec($ch);
                            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            curl_close($ch);
                            
                            echo "HTTP Response Code: " . $httpCode . "\n";
                            if ($httpCode == 200) {
                                echo "✅ Logo is now accessible via web!\n";
                            } else {
                                echo "❌ Logo still not accessible (HTTP $httpCode)\n";
                            }
                        }
                        
                        // Create test HTML page
                        $htmlContent = '<!DOCTYPE html>
<html>
<head>
    <title>Logo Test - Fixed</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .logo-test { border: 2px solid #ccc; padding: 20px; text-align: center; }
        .logo-test img { max-width: 200px; height: auto; }
        .success { border-color: green; background: #f0fff0; }
        .error { border-color: red; background: #fff0f0; }
    </style>
</head>
<body>
    <h1>Logo Access Test - Fixed Version</h1>
    <div class="logo-test">
        <h2>Testing Logo Display</h2>
        <img src="' . $url . '" alt="Yayasan Logo" 
             onerror="this.parentElement.className=\'logo-test error\'; this.alt=\'Logo Failed to Load\';" 
             onload="this.parentElement.className=\'logo-test success\';" />
        <p><strong>Logo URL:</strong> ' . $url . '</p>
        <p><strong>Full URL:</strong> <a href="' . $url . '" target="_blank">' . $url . '</a></p>
    </div>
    
    <h2>Debug Info</h2>
    <ul>
        <li>Database Logo Path: ' . $yayasan->logo . '</li>
        <li>Generated URL: ' . $url . '</li>
        <li>File Exists: ' . (file_exists($destFile) ? 'YES' : 'NO') . '</li>
        <li>File Size: ' . (file_exists($destFile) ? filesize($destFile) . ' bytes' : 'N/A') . '</li>
    </ul>
</body>
</html>';
                        
                        file_put_contents(public_path('logo-test-fixed.html'), $htmlContent);
                        echo "HTML test page created: http://yayasan-kemala-bhayangkari.localhost:8000/logo-test-fixed.html\n";
                        
                    } else {
                        echo "❌ Failed to copy file\n";
                    }
                } else {
                    echo "❌ Source file not found\n";
                }
            } else {
                echo "❌ No logo found in database\n";
            }
        } else {
            echo "❌ Yayasan model not found\n";
        }
    });
    
} else {
    echo "❌ Tenant not found\n";
}

echo "\n=== Summary ===\n";
echo "The logo file has been copied from tenant storage to public storage.\n";
echo "This should make it accessible via the web URL.\n";
echo "Test the logo at: http://yayasan-kemala-bhayangkari.localhost:8000/logo-test-fixed.html\n";
