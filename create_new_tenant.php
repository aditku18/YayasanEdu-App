<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use App\Services\TenantService;

// Create new foundation with unique name
$foundation = \App\Models\Foundation::create([
    'name' => 'Yayasan Ilmu Cerdas',
    'email' => 'info@ilmucerdas.com',
    'phone' => '081987654321',
    'subdomain' => 'ilmu-cerdas.localhost',
    'status' => 'pending',
]);

echo "Creating new foundation: " . $foundation->name . "\n";

// Create tenant
$tenantService = new TenantService();
$tenantId = \Illuminate\Support\Str::uuid()->toString();

try {
    $tenant = $tenantService->createTenantWithDomain($tenantId, $foundation->subdomain);
    echo "Tenant created: " . $tenant->id . "\n";
    echo "Domain: " . $foundation->subdomain . "\n";
    
    // Update foundation
    $foundation->update([
        'tenant_id' => $tenantId,
        'status' => 'trial',
        'trial_ends_at' => now()->addDays(14),
    ]);
    
    echo "Foundation updated successfully\n";
    echo "Status: " . $foundation->status . "\n";
    echo "Trial ends at: " . $foundation->trial_ends_at . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
