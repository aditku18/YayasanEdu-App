<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Assign tenant ID to foundation
$foundation = \App\Models\Foundation::where('subdomain', 'pelita-hati2.localhost')->first();
if ($foundation) {
    $tenantId = 'test-tenant-' . uniqid();
    $foundation->update(['tenant_id' => $tenantId]);
    echo "Tenant ID assigned: " . $tenantId . "\n";
    echo "Foundation status: " . $foundation->status . "\n";
    echo "Trial ends at: " . $foundation->trial_ends_at . "\n";
} else {
    echo "Foundation not found\n";
}
