<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use App\Models\Foundation;
use Stancl\Tenancy\Database\Models\Domain;

echo "--- Checking Foundation ---\n";
$subdomain = 'pelita-hati2.localhost';
$foundation = Foundation::where('subdomain', $subdomain)->first();

if (!$foundation) {
    echo "Foundation with subdomain '$subdomain' NOT FOUND. Creating one...\n";
    $foundation = Foundation::create([
        'name' => 'Yayasan Pelita Hati 2',
        'email' => 'admin@pelita-hati2.sch.id',
        'subdomain' => $subdomain,
        'status' => 'active',
    ]);
    echo "Foundation created.\n";
} else {
    echo "Foundation found: " . $foundation->name . " (ID: " . $foundation->id . ")\n";
}

echo "--- Checking Tenant ---\n";
$tenant = null;
if ($foundation->tenant_id) {
    $tenant = Tenant::find($foundation->tenant_id);
    if ($tenant) {
        echo "Linked Tenant found: " . $tenant->id . "\n";
    } else {
        echo "Foundation has tenant_id " . $foundation->tenant_id . " but no such Tenant exists.\n";
    }
}

if (!$tenant) {
    echo "Creating new Tenant...\n";
    try {
        $tenantId = \Illuminate\Support\Str::uuid()->toString();
        $tenant = Tenant::create([
            'id' => $tenantId,
        ]);
        $foundation->update(['tenant_id' => $tenant->id]);
        echo "Tenant created: " . $tenant->id . "\n";
    } catch (\Exception $e) {
        echo "ERROR creating tenant: " . $e->getMessage() . "\n";
        echo $e->getTraceAsString() . "\n";
        exit(1);
    }
}

echo "--- Checking Domain ---\n";
$domain = Domain::where('domain', $subdomain)->first();
if ($domain) {
    echo "Domain '$subdomain' already exists for tenant: " . $domain->tenant_id . "\n";
    if ($domain->tenant_id !== $tenant->id) {
        echo "Updating domain to point to correct tenant...\n";
        $domain->update(['tenant_id' => $tenant->id]);
    }
} else {
    echo "Creating Domain mapping for '$subdomain'...\n";
    Domain::create([
        'domain' => $subdomain,
        'tenant_id' => $tenant->id,
    ]);
}

echo "--- Summary ---\n";
echo "Foundation: " . $foundation->name . "\n";
echo "Tenant ID: " . $tenant->id . "\n";
echo "Domain: " . $subdomain . "\n";
echo "Status: DONE. Please try accessing http://$subdomain/login again.\n";
