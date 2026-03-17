<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

// Get all tenants
$tenants = Tenant::all();

foreach ($tenants as $tenant) {
    echo "Checking tenant: {$tenant->id}\n";

    try {
        // Switch to tenant database
        tenancy()->initialize($tenant);

        // Check what tables exist
        $tables = DB::select('SHOW TABLES');
        echo "Tables in tenant database:\n";

        foreach ($tables as $table) {
            $tableName = current($table);
            echo "- $tableName\n";
        }

        // Check if payments table exists
        if (DB::schema()->hasTable('payments')) {
            echo "✓ payments table exists\n";
        } else {
            echo "✗ payments table does NOT exist\n";
        }

        echo "\n";

    } catch (\Exception $e) {
        echo "Error checking tenant {$tenant->id}: " . $e->getMessage() . "\n";
    }

    // Reset tenancy
    tenancy()->end();
}
