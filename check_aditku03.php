<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Stancl\Tenancy\Database\Models\Domain;
use App\Models\User;

$domain = Domain::where('domain', 'kemala.localhost')->first();
if ($domain) {
    $tenant = $domain->tenant;
    echo 'Tenant ID: ' . $tenant->id . PHP_EOL;
    tenancy()->initialize($tenant);
    $user = User::where('email', 'aditku03@gmail.com')->first();
    if ($user) {
        echo 'User found in tenant DB' . PHP_EOL;
        echo 'Email Verified At: ' . ($user->email_verified_at ?? 'NULL') . PHP_EOL;
        echo 'Has Verified Email: ' . ($user->hasVerifiedEmail() ? 'YES' : 'NO') . PHP_EOL;
        echo 'Role: ' . $user->role . PHP_EOL;
        echo 'School Unit ID: ' . ($user->school_unit_id ?? 'NULL') . PHP_EOL;
        
        // Fix: Mark as verified
        $user->email_verified_at = now();
        $user->save();
        echo 'Email has been marked as verified.' . PHP_EOL;
    } else {
        echo 'User NOT found in tenant DB' . PHP_EOL;
    }
    tenancy()->end();
} else {
    echo 'Domain not found' . PHP_EOL;
}

// Also check central user
$centralUser = User::on(config('tenancy.database.central_connection'))->where('email', 'aditku03@gmail.com')->first();
if ($centralUser) {
    echo 'Central User Email Verified At: ' . ($centralUser->email_verified_at ?? 'NULL') . PHP_EOL;
    echo 'Central User Has Verified Email: ' . ($centralUser->hasVerifiedEmail() ? 'YES' : 'NO') . PHP_EOL;
} else {
    echo 'Central User NOT found' . PHP_EOL;
}
