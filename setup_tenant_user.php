<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use App\Models\User;
use App\Models\SchoolUnit;
use Illuminate\Support\Facades\Hash;

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

$email = 'aditku02@gmail.com';
$password = 'password';

// 1. Ensure a school unit exists
$school = SchoolUnit::first();
if (!$school) {
    echo "No school unit found. Creating one...\n";
    $school = SchoolUnit::create([
        'name' => 'Unit Pelita Hati',
        'status' => 'active',
        // Assuming foundation_id might be needed, but it's nullable
    ]);
    echo "School unit created: " . $school->name . "\n";
} else {
    echo "School unit found: " . $school->name . " (Status: " . $school->status . ")\n";
    if ($school->status !== 'active') {
        echo "Updating school status to active...\n";
        $school->update(['status' => 'active']);
    }
}

// 2. Check/Create User
$user = User::where('email', $email)->first();
if ($user) {
    echo "User $email already exists.\n";
    echo "Updating role and school link...\n";
    $user->update([
        'role' => 'school_admin',
        'school_unit_id' => $school->id,
        'password' => Hash::make($password),
    ]);
    echo "User updated.\n";
} else {
    echo "User $email not found. Creating...\n";
    $user = User::create([
        'name' => 'Admin Pelita Hati',
        'email' => $email,
        'password' => Hash::make($password),
        'role' => 'school_admin',
        'school_unit_id' => $school->id,
        'is_active' => true,
    ]);
    echo "User created.\n";
}

echo "\n--- Summary ---\n";
echo "Email: " . $email . "\n";
echo "Password: " . $password . "\n";
echo "Role: " . $user->role . "\n";
echo "School ID: " . $user->school_unit_id . "\n";
echo "School Status: " . $school->status . "\n";
echo "DONE.\n";
