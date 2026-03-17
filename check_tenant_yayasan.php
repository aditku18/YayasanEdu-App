<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Checking Tenant Setup ===\n";

// Get tenant
$tenant = \App\Models\Tenant::where('domain', 'yayasan-hidayattul-amin.localhost')->first();

if ($tenant) {
    echo "Tenant found: " . $tenant->id . "\n";
    
    // Initialize tenancy
    tenancy()->initialize($tenant);
    
    echo "=== Checking Yayasan Logo Data (Tenant) ===\n";
    
    $yayasan = \App\Models\Yayasan::first();
    
    if ($yayasan) {
        echo "ID: " . $yayasan->id . "\n";
        echo "Name: " . ($yayasan->name ?? 'NULL') . "\n";
        echo "Logo: " . ($yayasan->logo ?? 'NULL') . "\n";
        
        if ($yayasan->logo) {
            echo "Logo path exists: " . (Storage::disk('public')->exists($yayasan->logo) ? 'YES' : 'NO') . "\n";
            echo "Full logo URL: " . Storage::url($yayasan->logo) . "\n";
        } else {
            echo "Logo is NULL\n";
        }
    } else {
        echo "No Yayasan found in tenant\n";
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
    
} else {
    echo "Tenant not found for domain: yayasan-hidayattul-amin.localhost\n";
    
    // List all tenants
    echo "\n=== Available Tenants ===\n";
    $tenants = \App\Models\Tenant::all();
    foreach ($tenants as $t) {
        echo "- " . $t->id . " (domain: " . $t->domain . ")\n";
    }
}
