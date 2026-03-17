<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Foundation;
use App\Services\TenantService;

$tenantService = new TenantService();

// Get foundations without tenant_id
$foundations = Foundation::whereNull('tenant_id')->get();

echo "Found " . $foundations->count() . " foundations without tenant_id\n\n";

foreach ($foundations as $foundation) {
    echo "Processing: {$foundation->name} (ID: {$foundation->id})\n";

    try {
        // Create tenant using foundation name
        $tenant = $tenantService->createTenantWithFoundationName($foundation->name, $foundation->subdomain);

        // Update foundation with tenant_id and set status to trial
        $foundation->update([
            'tenant_id' => $tenant->id,
            'status' => 'trial',
            'trial_ends_at' => now()->addDays(14),
        ]);

        echo "✓ Created tenant: {$tenant->id}\n";
        echo "✓ Updated foundation status to trial\n\n";

    } catch (\Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n\n";
    }
}

echo "Synchronization complete.\n";
