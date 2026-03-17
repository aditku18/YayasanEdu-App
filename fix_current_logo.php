<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FIX CURRENT LOGO ISSUE ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "✓ Tenant found: " . $tenant->id . "\n";
    
    $tenant->run(function() {
        echo "\n=== FIXING YAYASAN LOGO ===\n";
        
        if (class_exists('App\Models\Yayasan')) {
            $yayasan = \App\Models\Yayasan::first();
            if ($yayasan && $yayasan->logo) {
                echo "Current logo in database: " . $yayasan->logo . "\n";
                
                // Source file in tenant storage
                $sourceFile = storage_path('tenant' . tenant()->id . '/app/public/' . $yayasan->logo);
                echo "Source file: " . $sourceFile . "\n";
                echo "Source exists: " . (file_exists($sourceFile) ? 'YES' : 'NO') . "\n";
                
                if (file_exists($sourceFile)) {
                    // Destination file in public storage
                    $destFile = public_path('storage/' . $yayasan->logo);
                    $destDir = dirname($destFile);
                    
                    echo "Destination file: " . $destFile . "\n";
                    
                    // Create directory if needed
                    if (!is_dir($destDir)) {
                        echo "Creating directory...\n";
                        mkdir($destDir, 0755, true);
                    }
                    
                    // Copy file
                    echo "Copying current logo...\n";
                    $copyResult = copy($sourceFile, $destFile);
                    echo "Copy result: " . ($copyResult ? 'SUCCESS' : 'FAILED') . "\n";
                    
                    if ($copyResult) {
                        echo "✅ Current logo copied to public storage\n";
                        
                        // Test the URL
                        $url = '/storage/' . $yayasan->logo;
                        $testUrl = 'http://yayasan-kemala-bhayangkari.localhost:8000' . $url;
                        
                        if (function_exists('curl_init')) {
                            $ch = curl_init($testUrl);
                            curl_setopt($ch, CURLOPT_NOBODY, true);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                            curl_exec($ch);
                            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            curl_close($ch);
                            echo "HTTP Test: $httpCode " . ($httpCode == 200 ? '✅' : '❌') . "\n";
                        }
                    }
                }
            }
        }
        
        echo "\n=== CHECKING FOUNDATION LOGO ===\n";
        
        if (class_exists('App\Models\Foundation')) {
            $foundations = \App\Models\Foundation::all();
            
            foreach ($foundations as $foundation) {
                echo "Foundation ID: " . $foundation->id . "\n";
                echo "Name: " . $foundation->name . "\n";
                
                if ($foundation->logo_path) {
                    echo "Logo path: " . $foundation->logo_path . "\n";
                    
                    // Check if file exists in public storage
                    $publicFile = public_path('storage/' . $foundation->logo_path);
                    echo "Public file: " . $publicFile . "\n";
                    echo "File exists: " . (file_exists($publicFile) ? 'YES' : 'NO') . "\n";
                    
                    if (file_exists($publicFile)) {
                        echo "File size: " . filesize($publicFile) . " bytes\n";
                        
                        // Test URL
                        $url = '/storage/' . $foundation->logo_path;
                        $testUrl = 'http://yayasan-kemala-bhayangkari.localhost:8000' . $url;
                        
                        if (function_exists('curl_init')) {
                            $ch = curl_init($testUrl);
                            curl_setopt($ch, CURLOPT_NOBODY, true);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                            curl_exec($ch);
                            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            curl_close($ch);
                            echo "HTTP Test: $httpCode " . ($httpCode == 200 ? '✅' : '❌') . "\n";
                        }
                    }
                }
                echo "\n";
            }
        }
        
        echo "\n=== CREATING TEST PAGES ===\n";
        
        // Create test page for Yayasan logo
        $yayasanLogoUrl = '';
        if (class_exists('App\Models\Yayasan')) {
            $yayasan = \App\Models\Yayasan::first();
            if ($yayasan && $yayasan->logo) {
                $yayasanLogoUrl = '/storage/' . $yayasan->logo;
            }
        }
        
        $yayasanTestHtml = '<!DOCTYPE html>
<html>
<head>
    <title>Yayasan Logo Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .logo-test { border: 2px solid #ccc; padding: 20px; margin: 20px 0; text-align: center; }
        .logo-test img { max-width: 300px; height: auto; }
        .success { border-color: green; background: #f0fff0; }
        .error { border-color: red; background: #fff0f0; }
    </style>
</head>
<body>
    <h1>Yayasan Logo Test</h1>
    <div class="logo-test">
        <h2>Yayasan Model Logo</h2>
        <img src="' . $yayasanLogoUrl . '" alt="Yayasan Logo" 
             onerror="this.parentElement.className=\'logo-test error\'; this.alt=\'Logo Failed to Load\';" 
             onload="this.parentElement.className=\'logo-test success\';" />
        <p><strong>URL:</strong> ' . $yayasanLogoUrl . '</p>
    </div>
    
    <h2>Foundation Model Logos</h2>';
        
        if (class_exists('App\Models\Foundation')) {
            $foundations = \App\Models\Foundation::all();
            foreach ($foundations as $foundation) {
                if ($foundation->logo_path) {
                    $foundationLogoUrl = '/storage/' . $foundation->logo_path;
                    $yayasanTestHtml .= '
    <div class="logo-test">
        <h3>Foundation: ' . $foundation->name . '</h3>
        <img src="' . $foundationLogoUrl . '" alt="Foundation Logo" 
             onerror="this.parentElement.className=\'logo-test error\'; this.alt=\'Logo Failed to Load\';" 
             onload="this.parentElement.className=\'logo-test success\';" />
        <p><strong>URL:</strong> ' . $foundationLogoUrl . '</p>
    </div>';
                }
            }
        }
        
        $yayasanTestHtml .= '
</body>
</html>';
        
        file_put_contents(public_path('test-yayasan-logo.html'), $yayasanTestHtml);
        echo "Yayasan logo test page created: http://yayasan-kemala-bhayangkari.localhost:8000/test-yayasan-logo.html\n";
        
    });
    
} else {
    echo "❌ Tenant not found\n";
}

echo "\n=== NEXT STEPS ===\n";
echo "1. Visit: http://yayasan-kemala-bhayangkari.localhost:8000/test-yayasan-logo.html\n";
echo "2. Check which logo actually loads\n";
echo "3. Identify which model is being used in your views\n";
echo "4. Update the correct model with the working logo path\n";
