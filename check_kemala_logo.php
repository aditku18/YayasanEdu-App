<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Checking Kemala Bhayangkari Logo ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "Tenant ID: " . $tenant->id . "\n";
    
    // Run within tenant context
    $tenant->run(function() {
        echo "=== Running in tenant context ===\n";
        
        // Check if Yayasan model exists
        if (class_exists('App\Models\Yayasan')) {
            echo "Yayasan model found\n";
            
            $yayasan = \App\Models\Yayasan::first();
            if ($yayasan) {
                echo "Yayasan ID: " . $yayasan->id . "\n";
                echo "Yayasan Name: " . ($yayasan->name ?? 'NULL') . "\n";
                echo "Logo field: " . ($yayasan->logo ?? 'NULL') . "\n";
                
                if ($yayasan->logo) {
                    echo "Logo path exists in storage: " . (Storage::disk('public')->exists($yayasan->logo) ? 'YES' : 'NO') . "\n";
                    echo "Full logo URL: " . Storage::url($yayasan->logo) . "\n";
                    echo "Logo file path: " . storage_path('app/public/' . $yayasan->logo) . "\n";
                    
                    // Check if file actually exists
                    if (file_exists(storage_path('app/public/' . $yayasan->logo))) {
                        echo "Logo file exists on filesystem: YES\n";
                        echo "File size: " . filesize(storage_path('app/public/' . $yayasan->logo)) . " bytes\n";
                    } else {
                        echo "Logo file exists on filesystem: NO\n";
                    }
                } else {
                    echo "Logo field is NULL\n";
                }
            } else {
                echo "No Yayasan found in tenant database\n";
            }
        } else {
            echo "Yayasan model not found in tenant\n";
        }
        
        // Check if Foundation model exists
        if (class_exists('App\Models\Foundation')) {
            echo "\n=== Checking Foundation model ===\n";
            $foundation = \App\Models\Foundation::first();
            if ($foundation) {
                echo "Foundation ID: " . $foundation->id . "\n";
                echo "Foundation Name: " . ($foundation->name ?? 'NULL') . "\n";
                echo "Logo path: " . ($foundation->logo_path ?? 'NULL') . "\n";
                
                if ($foundation->logo_path) {
                    echo "Logo path exists in storage: " . (Storage::disk('public')->exists($foundation->logo_path) ? 'YES' : 'NO') . "\n";
                    echo "Full logo URL: " . Storage::url($foundation->logo_path) . "\n";
                }
            } else {
                echo "No Foundation found in tenant database\n";
            }
        }
    });
} else {
    echo "Tenant not found\n";
}

echo "\n=== Checking Storage Directory ===\n";
echo "Storage path: " . storage_path('app/public') . "\n";

if (is_dir(storage_path('app/public'))) {
    echo "Public storage directory exists\n";
    $files = scandir(storage_path('app/public'));
    echo "Contents: " . implode(', ', array_diff($files, ['.', '..'])) . "\n";
} else {
    echo "Public storage directory does not exist\n";
}
