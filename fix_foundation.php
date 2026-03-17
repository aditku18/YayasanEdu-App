<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;

// Get foundation
$foundation = \App\Models\Foundation::where('subdomain', 'pelita-hati2.localhost')->first();
if (!$foundation) {
    echo "Foundation not found\n";
    exit;
}

// Find existing tenant
$tenant = Tenant::whereHas('domains', function($query) use ($foundation) {
    $query->where('domain', $foundation->subdomain);
})->first();

if ($tenant) {
    echo "Found existing tenant: " . $tenant->id . "\n";
    
    // Update foundation with existing tenant
    $foundation->update([
        'tenant_id' => $tenant->id,
        'status' => 'trial',
        'trial_ends_at' => now()->addDays(14),
        'plan_id' => 1,
    ]);
    
    echo "Foundation updated successfully\n";
    echo "Status: " . $foundation->status . "\n";
    echo "Trial ends at: " . $foundation->trial_ends_at . "\n";
    echo "Tenant ID: " . $foundation->tenant_id . "\n";
} else {
    echo "No tenant found for domain: " . $foundation->subdomain . "\n";
}
