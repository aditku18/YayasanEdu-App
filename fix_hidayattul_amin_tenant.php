<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use App\Services\TenantService;

// Get the foundation
$foundation = \App\Models\Foundation::where('subdomain', 'hidayattul-amin.localhost')->first();
if (!$foundation) {
    echo "Foundation not found\n";
    exit;
}

echo "Fixing tenant for foundation: " . $foundation->name . "\n";

// Get the existing tenant
$tenant = Tenant::find('tenant-yayasan-hidayattul-amin');
if (!$tenant) {
    echo "Creating new tenant...\n";
    $tenantService = new TenantService();
    $tenantId = 'tenant-yayasan-hidayattul-amin';
    
    try {
        $tenant = $tenantService->createTenantWithDomain($tenantId, $foundation->subdomain);
        echo "Tenant created: " . $tenant->id . "\n";
    } catch (\Exception $e) {
        echo "Error creating tenant: " . $e->getMessage() . "\n";
        exit;
    }
} else {
    echo "Found existing tenant: " . $tenant->id . "\n";
    
    // Check if domain exists
    $domains = $tenant->domains;
    if ($domains->isEmpty()) {
        echo "Creating domain for existing tenant...\n";
        $tenant->domains()->create([
            'domain' => $foundation->subdomain
        ]);
        echo "Domain created: " . $foundation->subdomain . "\n";
    } else {
        echo "Tenant already has domains:\n";
        foreach ($domains as $domain) {
            echo "  - " . $domain->domain . "\n";
        }
    }
}

// Update foundation with tenant ID
$foundation->update([
    'tenant_id' => $tenant->id,
    'status' => 'trial',
    'trial_ends_at' => now()->addDays(14),
]);

echo "Foundation updated successfully\n";
echo "Status: " . $foundation->status . "\n";
echo "Tenant ID: " . $foundation->tenant_id . "\n";
echo "Access URL: http://" . $foundation->subdomain . ":8000\n";
