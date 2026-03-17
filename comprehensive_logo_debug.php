<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== COMPREHENSIVE LOGO DEBUG ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "✓ Tenant found: " . $tenant->id . "\n";
    
    $tenant->run(function() {
        echo "\n=== TENANT CONTEXT DEBUG ===\n";
        
        // Check all models that might have logo
        $models = ['Yayasan', 'Foundation', 'School', 'Institution'];
        
        foreach ($models as $modelName) {
            echo "\n--- Checking $modelName ---\n";
            
            if (class_exists('App\\Models\\' . $modelName)) {
                $modelClass = 'App\\Models\\' . $modelName;
                $records = $modelClass::all();
                
                echo "Found " . $records->count() . " records\n";
                
                foreach ($records as $record) {
                    echo "Record ID: " . $record->id . "\n";
                    echo "Name: " . ($record->name ?? 'NULL') . "\n";
                    
                    // Check all possible logo fields
                    $logoFields = ['logo', 'logo_path', 'logo_url', 'image', 'image_path'];
                    foreach ($logoFields as $field) {
                        if (isset($record->$field) && $record->$field) {
                            echo "$field: " . $record->$field . "\n";
                            
                            // Check if file exists in different locations
                            $paths = [
                                'Main storage' => storage_path('app/public/' . $record->$field),
                                'Tenant storage' => storage_path('tenant' . tenant()->id . '/app/public/' . $record->$field),
                                'Public storage' => public_path('storage/' . $record->$field),
                            ];
                            
                            foreach ($paths as $name => $path) {
                                $exists = file_exists($path);
                                echo "  - $name ($path): " . ($exists ? 'EXISTS' : 'MISSING');
                                if ($exists) {
                                    echo " (" . filesize($path) . " bytes)";
                                }
                                echo "\n";
                            }
                            
                            // Test URL generation
                            $url = Storage::url($record->$field);
                            echo "  - Generated URL: $url\n";
                            
                            // Test HTTP access
                            $testUrl = 'http://yayasan-kemala-bhayangkari.localhost:8000' . $url;
                            if (function_exists('curl_init')) {
                                $ch = curl_init($testUrl);
                                curl_setopt($ch, CURLOPT_NOBODY, true);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                curl_exec($ch);
                                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                curl_close($ch);
                                echo "  - HTTP Test ($testUrl): $httpCode " . ($httpCode == 200 ? '✅' : '❌') . "\n";
                            }
                        }
                    }
                    echo "\n";
                }
            } else {
                echo "$modelName model not found\n";
            }
        }
        
        // Check what's actually being used in views
        echo "\n=== VIEW INTEGRATION DEBUG ===\n";
        
        // Look for logo references in common view files
        $viewFiles = [
            'layouts.app',
            'layouts.tenant',
            'dashboard',
            'home',
            'welcome'
        ];
        
        foreach ($viewFiles as $viewFile) {
            echo "--- Checking view: $viewFile ---\n";
            try {
                if (view()->exists($viewFile)) {
                    echo "View exists\n";
                    // Note: We can't easily read view content here, but we know it exists
                } else {
                    echo "View not found\n";
                }
            } catch (Exception $e) {
                echo "Error checking view: " . $e->getMessage() . "\n";
            }
        }
        
        // Check current route and what might be displayed
        echo "\n=== ROUTE DEBUG ===\n";
        echo "Current URL would be: http://yayasan-kemala-bhayangkari.localhost:8000\n";
        
        // Test different URLs that might show logo
        $testUrls = [
            'http://yayasan-kemala-bhayangkari.localhost:8000/',
            'http://yayasan-kemala-bhayangkari.localhost:8000/dashboard',
            'http://yayasan-kemala-bhayangkari.localhost:8000/home',
        ];
        
        foreach ($testUrls as $url) {
            echo "Testing: $url\n";
            if (function_exists('curl_init')) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                $html = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                echo "  HTTP Code: $httpCode\n";
                
                if ($httpCode == 200 && $html) {
                    // Look for logo references in HTML
                    if (strpos($html, 'logo') !== false) {
                        echo "  ✅ Contains logo references\n";
                        
                        // Extract logo URLs
                        preg_match_all('/src=["\']([^"\']*logo[^"\']*)["\']/', $html, $matches);
                        if (!empty($matches[1])) {
                            echo "  Found logo URLs:\n";
                            foreach ($matches[1] as $logoUrl) {
                                echo "    - $logoUrl\n";
                            }
                        }
                    } else {
                        echo "  ❌ No logo references found\n";
                    }
                }
            }
            echo "\n";
        }
    });
    
} else {
    echo "❌ Tenant not found\n";
}

echo "\n=== FINAL DIAGNOSIS ===\n";
echo "1. Check if logo is being referenced correctly in views\n";
echo "2. Verify the URL generation is working\n";
echo "3. Test actual web pages that should display the logo\n";
echo "4. Check browser developer tools for 404 errors\n";
