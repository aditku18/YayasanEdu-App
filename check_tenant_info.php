<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Checking Tenant Domains ===\n";

// List all tenants
$tenants = \App\Models\Tenant::all();

foreach ($tenants as $tenant) {
    echo "Tenant ID: " . $tenant->id . "\n";
    
    // Get domains for this tenant
    $domains = \Stancl\Tenancy\Database\Models\Domain::where('tenant_id', $tenant->id)->get();
    
    foreach ($domains as $domain) {
        echo "  Domain: " . $domain->domain . "\n";
        
        if ($domain->domain === 'yayasan-hidayattul-amin.localhost') {
            echo "  -> Found target tenant!\n";
            
            // Initialize tenancy
            tenancy()->initialize($tenant);
            
            echo "\n=== Checking Yayasan Logo Data ===\n";
            
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
                
                echo "\n=== Testing Logo Upload Simulation ===\n";
                echo "Current form data that would be processed:\n";
                echo "- Route: tenant.yayasan.profil.update\n";
                echo "- Controller: App\\Http\\Controllers\\Tenant\\FoundationController@update\n";
                echo "- Validation: logo nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048\n";
                
            } else {
                echo "No Yayasan found in tenant\n";
            }
        }
    }
    echo "\n";
}
