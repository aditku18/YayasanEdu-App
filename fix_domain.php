<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Tenant;

$tenantId = 'tenant-yayasan-hidayattul-amin';
$domainName = 'yayasan-hidayattul-amin.localhost';

$tenant = Tenant::find($tenantId);
if ($tenant) {
    if (!$tenant->domains()->where('domain', $domainName)->exists()) {
        $tenant->domains()->create(['domain' => $domainName]);
        echo "Domain $domainName added to tenant $tenantId.\n";
    } else {
        echo "Domain $domainName already exists for tenant $tenantId.\n";
    }
} else {
    echo "Tenant $tenantId not found.\n";
}
