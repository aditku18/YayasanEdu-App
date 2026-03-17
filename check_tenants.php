<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use App\Models\Domain;

// Check existing tenants
$tenants = Tenant::all();
echo "Total tenants: " . $tenants->count() . "\n";

foreach ($tenants as $tenant) {
    $domain = $tenant->domains()->first();
    echo "- Tenant ID: " . $tenant->id . "\n";
    echo "  Domain: " . ($domain ? $domain->domain : 'No domain') . "\n";
    echo "\n";
}

// Check domains table
$domains = Domain::all();
echo "Total domains: " . $domains->count() . "\n";
foreach ($domains as $domain) {
    echo "- " . $domain->domain . " (Tenant: " . $domain->tenant_id . ")\n";
}
