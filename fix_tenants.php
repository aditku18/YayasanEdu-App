<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tenants = \Illuminate\Support\Facades\DB::table('tenants')->get();
foreach ($tenants as $tenantObj) {
    $data = json_decode($tenantObj->data, true);
    $dbName = $data['tenancy_db_name'] ?? null;
    
    if (!$dbName) continue;

    $exists = \Illuminate\Support\Facades\DB::select("SHOW DATABASES LIKE '$dbName'");
    if (empty($exists)) {
        echo "Deleting tenant " . $tenantObj->id . " without database ($dbName)\n";
        \Illuminate\Support\Facades\DB::table('tenants')->where('id', $tenantObj->id)->delete();
        \Illuminate\Support\Facades\DB::table('domains')->where('tenant_id', $tenantObj->id)->delete();
    }
}
echo "Done.\n";
