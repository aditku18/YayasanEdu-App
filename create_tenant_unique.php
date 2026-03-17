<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use App\Services\TenantService;

// Generate unique subdomain
$randomNumber = rand(1000, 9999);
$subdomain = 'yayasan-' . $randomNumber . '.localhost';

// Create new foundation with unique name
$foundation = \App\Models\Foundation::create([
    'name' => 'Yayasan Pendidikan ' . $randomNumber,
    'email' => 'info' . $randomNumber . '@yayasan.com',
    'phone' => '081' . $randomNumber,
    'subdomain' => $subdomain,
    'status' => 'pending',
]);

echo "Creating new foundation: " . $foundation->name . "\n";
echo "Subdomain: " . $foundation->subdomain . "\n";

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
    echo "\nAccess URL: http://" . $foundation->subdomain . ":8000\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
