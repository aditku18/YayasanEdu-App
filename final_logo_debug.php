<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FINAL LOGO DEBUG - STEP BY STEP ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "✓ Tenant found: " . $tenant->id . "\n";
    
    echo "\n=== STEP 1: CHECK DATABASE ===\n";
    
    $tenant->run(function() {
        // Check Foundation data
        if (class_exists('App\Models\Foundation')) {
            $foundation = \App\Models\Foundation::find(1);
            if ($foundation) {
                echo "Foundation ID: " . $foundation->id . "\n";
                echo "Foundation Name: " . $foundation->name . "\n";
                echo "Logo Path: " . ($foundation->logo_path ?? 'NULL') . "\n";
                
                if ($foundation->logo_path) {
                    echo "✓ Foundation has logo path\n";
                } else {
                    echo "❌ Foundation logo path is NULL\n";
                }
            } else {
                echo "❌ Foundation ID 1 not found\n";
            }
        } else {
            echo "❌ Foundation model not found\n";
        }
        
        // Check Yayasan data
        if (class_exists('App\Models\Yayasan')) {
            $yayasan = \App\Models\Yayasan::first();
            if ($yayasan) {
                echo "\nYayasan ID: " . $yayasan->id . "\n";
                echo "Yayasan Name: " . $yayasan->name . "\n";
                echo "Yayasan Logo: " . ($yayasan->logo ?? 'NULL') . "\n";
            }
        }
    });
    
    echo "\n=== STEP 2: CHECK FILE EXISTENCE ===\n";
    
    $tenant->run(function() {
        if (class_exists('App\Models\Foundation')) {
            $foundation = \App\Models\Foundation::find(1);
            if ($foundation && $foundation->logo_path) {
                $logoPath = $foundation->logo_path;
                echo "Checking logo path: " . $logoPath . "\n";
                
                // Check different storage locations
                $locations = [
                    'Public Storage' => public_path('storage/' . $logoPath),
                    'Storage App' => storage_path('app/public/' . $logoPath),
                    'Tenant Storage' => storage_path('tenant' . tenant()->id . '/app/public/' . $logoPath),
                ];
                
                foreach ($locations as $name => $path) {
                    $exists = file_exists($path);
                    echo "$name: " . ($exists ? 'EXISTS' : 'MISSING');
                    if ($exists) {
                        echo " (" . filesize($path) . " bytes)";
                    }
                    echo "\n";
                    echo "  Path: $path\n";
                }
            }
        }
    });
    
    echo "\n=== STEP 3: CHECK HTTP ACCESS ===\n";
    
    $tenant->run(function() {
        if (class_exists('App\Models\Foundation')) {
            $foundation = \App\Models\Foundation::find(1);
            if ($foundation && $foundation->logo_path) {
                $url = '/storage/' . $foundation->logo_path;
                $fullUrl = 'http://yayasan-kemala-bhayangkari.localhost:8000' . $url;
                
                echo "Testing URL: $fullUrl\n";
                
                if (function_exists('curl_init')) {
                    $ch = curl_init($fullUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_NOBODY, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
                    curl_close($ch);
                    
                    echo "HTTP Code: $httpCode\n";
                    echo "Content Type: $contentType\n";
                    
                    if ($httpCode == 200) {
                        echo "✅ Logo is accessible via HTTP\n";
                    } else {
                        echo "❌ Logo NOT accessible via HTTP\n";
                    }
                }
            }
        }
    });
    
    echo "\n=== STEP 4: CHECK LAYOUT FILE ===\n";
    
    $layoutPath = resource_path('views/layouts/tenant.blade.php');
    if (file_exists($layoutPath)) {
        echo "✓ Layout file exists\n";
        $layoutContent = file_get_contents($layoutPath);
        
        // Check if logo code is present
        if (strpos($layoutContent, 'Foundation::find(1)') !== false) {
            echo "✓ Layout contains Foundation logo code\n";
        } else {
            echo "❌ Layout does NOT contain Foundation logo code\n";
        }
        
        if (strpos($layoutContent, 'Storage::url') !== false) {
            echo "✓ Layout contains Storage::url code\n";
        } else {
            echo "❌ Layout does NOT contain Storage::url code\n";
        }
        
        // Show the logo section
        if (preg_match('/\{\{-- Logo & Foundation Info --\}\}.*?\n\s*<\/div>/s', $layoutContent, $matches)) {
            echo "\nCurrent logo section:\n";
            echo $matches[0] . "\n";
        }
    } else {
        echo "❌ Layout file not found\n";
    }
    
    echo "\n=== STEP 5: CHECK ACTUAL PAGE ===\n";
    
    $testUrls = [
        'http://yayasan-kemala-bhayangkari.localhost:8000/',
        'http://yayasan-kemala-bhayangkari.localhost:8000/dashboard',
    ];
    
    foreach ($testUrls as $url) {
        echo "\nTesting: $url\n";
        
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            $html = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            echo "HTTP Code: $httpCode\n";
            
            if ($httpCode == 200 && $html) {
                // Look for logo references
                if (strpos($html, 'logo') !== false) {
                    echo "✅ Page contains logo references\n";
                    
                    // Look for img tags with logo
                    if (preg_match_all('/<img[^>]*src=["\']([^"\']*)["\'][^>]*>/i', $html, $imgMatches)) {
                        echo "Found images:\n";
                        foreach ($imgMatches[1] as $src) {
                            echo "  - $src\n";
                        }
                    }
                    
                    // Look for Foundation references
                    if (strpos($html, 'Foundation') !== false) {
                        echo "✅ Page contains Foundation references\n";
                    }
                } else {
                    echo "❌ Page does NOT contain logo references\n";
                }
                
                // Check for errors
                if (strpos($html, 'error') !== false || strpos($html, 'Error') !== false) {
                    echo "⚠ Page may contain errors\n";
                }
            }
        }
    }
    
} else {
    echo "❌ Tenant not found\n";
}

echo "\n=== RECOMMENDATIONS ===\n";
echo "1. If Foundation logo_path is NULL, update it with correct path\n";
echo "2. If file doesn't exist, copy it to correct location\n";
echo "3. If HTTP access fails, check web server configuration\n";
echo "4. If layout doesn't have logo code, manually update it\n";
echo "5. Check browser developer tools for 404 errors\n";
