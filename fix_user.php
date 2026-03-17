<?php

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$centralConnection = config('tenancy.database.central_connection');
$domain = DB::connection($centralConnection)->table('domains')->where('domain', 'yab2.localhost')->first();

$tenant = Tenant::find($domain->tenant_id);
tenancy()->initialize($tenant);

$user = User::where('email', 'aditku1@gmail.com')->first();
if ($user) {
    $user->email_verified_at = now();
    $user->save();
    echo "User {$user->email} has been verified.\n";
} else {
    echo "User not found.\n";
}
