<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Tenant;
use Stancl\Tenancy\Database\Models\Domain;

$tenantId = 'tenant-yayasan-hidayattul-amin';
$domainName = 'yayasan-hidayattul-amin.localhost';

$tenant = Tenant::find($tenantId);
echo "Tenant ($tenantId): " . ($tenant ? "Found" : "NOT FOUND") . "\n";

if ($tenant) {
    echo "Domains for this tenant:\n";
    foreach ($tenant->domains as $d) {
        echo "- " . $d->domain . "\n";
    }
}

$domain = Domain::where('domain', $domainName)->first();
echo "Domain record ($domainName): " . ($domain ? "Found (Tenant ID: " . $domain->tenant_id . ")" : "NOT FOUND") . "\n";
