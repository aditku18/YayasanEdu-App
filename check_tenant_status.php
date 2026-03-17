<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use App\Models\User;
use App\Models\SchoolUnit;

$subdomain = 'pelita-hati2.localhost';
$tenant = Tenant::whereHas('domains', function ($q) use ($subdomain) {
    $q->where('domain', $subdomain);
})->first();

if (!$tenant) {
    echo "Tenant for $subdomain not found.\n";
    exit(1);
}

echo "Initializing tenant: " . $tenant->id . "\n";
tenancy()->initialize($tenant);

// Check all users in tenant
$users = User::all();
echo "\n=== All Users in Tenant ===\n";
foreach ($users as $user) {
    echo "ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Role: {$user->role}\n";
    echo "Active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
    echo "Email Verified: " . ($user->email_verified_at ? 'Yes' : 'No') . "\n";
    echo "School Unit ID: " . ($user->school_unit_id ?? 'None') . "\n";
    
    if ($user->school_unit_id) {
        $school = SchoolUnit::find($user->school_unit_id);
        echo "School Status: " . ($school ? $school->status : 'Not found') . "\n";
    }
    
    echo "---\n";
}

// Check school units
echo "\n=== School Units ===\n";
$schools = SchoolUnit::all();
foreach ($schools as $school) {
    echo "ID: {$school->id}\n";
    echo "Name: {$school->name}\n";
    echo "Status: {$school->status}\n";
    echo "---\n";
}

echo "\nDONE.\n";
