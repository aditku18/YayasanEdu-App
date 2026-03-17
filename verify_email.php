<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use App\Models\User;

// User kemala.localhost
$subdomain = 'kemala.localhost';
$tenant = Tenant::whereHas('domains', function ($q) use ($subdomain) {
    $q->where('domain', $subdomain);
})->first();

if ($tenant) {
    echo "Found tenant: " . $subdomain . "\n";
    tenancy()->initialize($tenant);
    
    // Find user
    $user = User::where('email', 'aditku023@gmail.com')->first();
    if ($user) {
        echo "User found: " . $user->email . "\n";
        echo "Current verification status: " . ($user->email_verified_at ? 'Verified' : 'Not Verified') . "\n";
        
        $user->update(['email_verified_at' => now()]);
        echo '✅ Email verified successfully!' . PHP_EOL;
    } else {
        echo 'User aditku023@gmail.com not found in this tenant.\n';
        
        // List all users
        $users = User::all();
        echo "\nAll users in this tenant:\n";
        foreach ($users as $u) {
            echo "- " . $u->email . " (" . ($u->email_verified_at ? 'verified' : 'NOT verified') . ")\n";
        }
    }
} else {
    echo 'Tenant not found: ' . $subdomain . '\n';
    
    // List all tenants
    $tenants = Tenant::with('domains')->get();
    echo "\nAvailable tenants:\n";
    foreach ($tenants as $t) {
        foreach ($t->domains as $d) {
            echo "- " . $d->domain . "\n";
        }
    }
}
