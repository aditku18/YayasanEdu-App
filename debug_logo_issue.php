<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Comprehensive Logo Debug ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "✓ Tenant found: " . $tenant->id . "\n";
    
    $tenant->run(function() {
        echo "\n=== TENANT CONTEXT ANALYSIS ===\n";
        
        // Check both models
        $models = ['Yayasan', 'Foundation'];
        
        foreach ($models as $modelName) {
            echo "\n--- Checking $modelName model ---\n";
            
            if (class_exists('App\\Models\\' . $modelName)) {
                $modelClass = 'App\\Models\\' . $modelName;
                $record = $modelClass::first();
                
                if ($record) {
                    echo "ID: " . $record->id . "\n";
                    echo "Name: " . ($record->name ?? 'NULL') . "\n";
                    
                    // Check different possible logo fields
                    $logoFields = ['logo', 'logo_path', 'logo_url'];
                    foreach ($logoFields as $field) {
                        if (isset($record->$field)) {
                            echo "$field: " . ($record->$field ?? 'NULL') . "\n";
                            
                            if ($record->$field) {
                                $exists = Storage::disk('public')->exists($record->$field);
                                echo "  - Exists in storage: " . ($exists ? 'YES' : 'NO') . "\n";
                                echo "  - Full URL: " . Storage::url($record->$field) . "\n";
                                echo "  - File path: " . storage_path('app/public/' . $record->$field) . "\n";
                                
                                if (file_exists(storage_path('app/public/' . $record->$field))) {
                                    echo "  - File exists: YES\n";
                                    echo "  - File size: " . filesize(storage_path('app/public/' . $record->$field)) . " bytes\n";
                                } else {
                                    echo "  - File exists: NO\n";
                                }
                            }
                        }
                    }
                } else {
                    echo "No $modelName record found\n";
                }
            } else {
                echo "$modelName model not found\n";
            }
        }
        
        echo "\n=== STORAGE ANALYSIS ===\n";
        
        // Check storage structure
        $basePath = storage_path('app/public');
        echo "Base storage path: $basePath\n";
        
        $directories = ['logos', 'uploads', 'uploads/foundations'];
        foreach ($directories as $dir) {
            $fullPath = $basePath . '/' . $dir;
            if (is_dir($fullPath)) {
                echo "✓ $dir directory exists\n";
                $files = scandir($fullPath);
                $fileList = array_diff($files, ['.', '..']);
                if (!empty($fileList)) {
                    echo "  Files: " . implode(', ', array_slice($fileList, 0, 5)) . (count($fileList) > 5 ? '...' : '') . "\n";
                }
            } else {
                echo "✗ $dir directory missing\n";
            }
        }
        
        echo "\n=== SYMBOLIC LINK CHECK ===\n";
        $publicStorage = public_path('storage');
        if (is_link($publicStorage)) {
            echo "✓ Symbolic link exists\n";
            echo "  Target: " . readlink($publicStorage) . "\n";
        } else {
            echo "✗ Symbolic link MISSING - This is likely the problem!\n";
            echo "  Run: php artisan storage:link\n";
        }
        
        echo "\n=== WEB ACCESS TEST ===\n";
        
        // Test if storage is accessible via web
        $testUrl = 'http://yayasan-kemala-bhayangkari.localhost:8000/storage/logos/VOn2OQvz7cD4gxuQv4wjKagHS5IOnb4PTcWRWoZS.png';
        echo "Test URL: $testUrl\n";
        
        // Try to access via curl if available
        if (function_exists('curl_init')) {
            $ch = curl_init($testUrl);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            echo "HTTP Response Code: $httpCode\n";
            if ($httpCode == 200) {
                echo "✓ Logo is accessible via web\n";
            } else {
                echo "✗ Logo is NOT accessible via web (Code: $httpCode)\n";
            }
        } else {
            echo "Curl not available - cannot test web access\n";
        }
    });
} else {
    echo "✗ Tenant not found\n";
}

echo "\n=== RECOMMENDATIONS ===\n";
echo "1. Run: php artisan storage:link\n";
echo "2. Check .htaccess in public/storage directory\n";
echo "3. Verify Apache/Nginx configuration allows /storage access\n";
echo "4. Clear browser cache and reload\n";
