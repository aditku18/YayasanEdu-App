<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TENANTS ===\n";
$tenants = \App\Models\Tenant::all();
foreach ($tenants as $tenant) {
    echo "Tenant ID: " . $tenant->id . "\n";
    $domains = $tenant->domains;
    foreach ($domains as $domain) {
        echo "  Domain: " . $domain->domain . "\n";
    }
    echo "\n";
}

echo "=== FOUNDATIONS ===\n";
$foundations = \App\Models\Foundation::with('tenant')->get();
foreach ($foundations as $foundation) {
    echo "Foundation: " . $foundation->name . "\n";
    echo "  Subdomain: " . $foundation->subdomain . "\n";
    echo "  Tenant ID: " . ($foundation->tenant_id ?? 'NULL') . "\n";
    if ($foundation->tenant) {
        echo "  Tenant exists: YES\n";
    } else {
        echo "  Tenant exists: NO\n";
    }
    echo "\n";
}

echo "=== DOMAINS ===\n";
$domains = \App\Models\Domain::all();
foreach ($domains as $domain) {
    echo "Domain: " . $domain->domain . " -> Tenant: " . $domain->tenant_id . "\n";
}
