<?php

use App\Models\User;
use App\Models\SchoolUnit;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$centralConnection = config('tenancy.database.central_connection');

// Find the tenant for yab2.localhost
$domain = DB::connection($centralConnection)->table('domains')->where('domain', 'yab2.localhost')->first();
if (!$domain) {
    die("Domain yab2.localhost not found.\n");
}

$tenantId = $domain->tenant_id;
echo "Initializing Tenancy for Tenant: {$tenantId}\n";

$tenant = Tenant::find($tenantId);
tenancy()->initialize($tenant);

echo "SCHOOL UNITS:\n";
$schools = SchoolUnit::all();
foreach ($schools as $s) {
    echo "ID: {$s->id}, Name: {$s->name}, Status: {$s->status}\n";
    
    // Find admin users for this school
    $admins = User::where('school_unit_id', $s->id)->get();
    foreach ($admins as $u) {
        echo "  - Admin ID: {$u->id}, Email: {$u->email}, Verified At: " . ($u->email_verified_at ?? 'NULL') . ", Role: {$u->role}\n";
        
        // Check central DB
        $centralUser = DB::connection($centralConnection)->table('users')->where('email', $u->email)->first();
        if ($centralUser) {
            echo "    - Central DB: Email: {$centralUser->email}, Verified At: " . ($centralUser->email_verified_at ?? 'NULL') . "\n";
        } else {
            echo "    - Central DB: User not found.\n";
        }
    }
}

echo "\nFOUNDATION ADMINS:\n";
$foundationAdmins = User::where('role', 'foundation_admin')->get();
foreach ($foundationAdmins as $u) {
    echo "ID: {$u->id}, Email: {$u->email}, Verified At: " . ($u->email_verified_at ?? 'NULL') . "\n";
}
