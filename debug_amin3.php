<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Foundation;
use App\Models\Tenant;

$email = 'amin3@gmail.com';

echo "--- CENTRAL DATABASE ---\n";
$centralUser = User::where('email', $email)->first();
if ($centralUser) {
    echo "User ID: {$centralUser->id}\n";
    echo "Email: {$centralUser->email}\n";
    echo "Verified At: " . ($centralUser->email_verified_at ?? 'NULL') . "\n";
    echo "Tenant ID: " . ($centralUser->tenant_id ?? 'NULL') . "\n";
} else {
    echo "User $email not found in central DB!\n";
}

$foundation = Foundation::where('email', $email)->first();
if ($foundation) {
    echo "\nFoundation Name: {$foundation->name}\n";
    echo "Status: {$foundation->status}\n";
    echo "Tenant ID: " . ($foundation->tenant_id ?? 'NULL') . "\n";
    echo "Subdomain: " . ($foundation->subdomain ?? 'NULL') . "\n";
} else {
    echo "\nFoundation for $email not found!\n";
}

if ($foundation && $foundation->tenant_id) {
    echo "\n--- TENANT DATABASE ({$foundation->tenant_id}) ---\n";
    $tenant = Tenant::find($foundation->tenant_id);
    if ($tenant) {
        tenancy()->initialize($tenant);
        $tenantUser = User::where('email', $email)->first();
        if ($tenantUser) {
            echo "Tenant User Email: {$tenantUser->email}\n";
            echo "Tenant User Verified At: " . ($tenantUser->email_verified_at ?? 'NULL') . "\n";
        } else {
            echo "User $email NOT FOUND in tenant DB!\n";
        }
        tenancy()->end();
    } else {
        echo "Tenant record not found in central DB!\n";
    }
}
