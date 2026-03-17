<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

$email = 'admin@platform.com'; // Ganti dengan email yang diinginkan
$password = 'password'; // Ganti dengan password yang diinginkan
$name = 'Platform Admin';

echo "Creating platform user...\n";

// Buat atau ambil role platform_admin
$role = Role::firstOrCreate(['name' => 'platform_admin']);

$user = User::firstOrCreate(
    ['email' => $email],
    [
        'name' => $name,
        'password' => Hash::make($password),
        'email_verified_at' => now(),
        'is_active' => true,
    ]
);

// Assign role jika belum ada
if (!$user->hasRole('platform_admin')) {
    $user->assignRole($role);
    echo "Role 'platform_admin' assigned to user.\n";
}

echo "\n--- User Created ---\n";
echo "Name: $name\n";
echo "Email: $email\n";
echo "Password: $password\n";
echo "Role: platform_admin\n";
echo "DONE.\n";
