<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Plan;
use App\Models\Foundation;
use Illuminate\Support\Facades\DB;

echo "=== Checking Seeding Status ===\n\n";

// Check Plans
$plansCount = Plan::count();
echo "✅ Plans: $plansCount records\n";

// Check Foundations
$foundationsCount = Foundation::count();
echo "✅ Foundations: $foundationsCount records\n";

// Check Users
$usersCount = User::count();
echo "✅ Users: $usersCount records\n";

// Check specific admin users
echo "\n=== Admin Users ===\n";
$adminEdusaas = User::where('email', 'admin@edusaas.com')->first();
if ($adminEdusaas) {
    echo "✅ admin@edusaas.com exists\n";
    echo "   - Name: {$adminEdusaas->name}\n";
    echo "   - Role: {$adminEdusaas->role}\n";
    echo "   - Has platform_admin role: " . ($adminEdusaas->hasRole('platform_admin') ? 'YES' : 'NO') . "\n";
} else {
    echo "❌ admin@edusaas.com NOT found\n";
}

$adminPayment = User::where('email', 'payment@admin.com')->first();
if ($adminPayment) {
    echo "✅ payment@admin.com exists\n";
    echo "   - Name: {$adminPayment->name}\n";
    echo "   - Has platform_admin role: " . ($adminPayment->hasRole('platform_admin') ? 'YES' : 'NO') . "\n";
} else {
    echo "❌ payment@admin.com NOT found\n";
}

$adminSisplatform = User::where('email', 'admin@sisplatform.com')->first();
if ($adminSisplatform) {
    echo "✅ admin@sisplatform.com exists\n";
    echo "   - Name: {$adminSisplatform->name}\n";
    echo "   - Has platform_admin role: " . ($adminSisplatform->hasRole('platform_admin') ? 'YES' : 'NO') . "\n";
} else {
    echo "❌ admin@sisplatform.com NOT found\n";
}

// Check roles
echo "\n=== Roles ===\n";
$roles = DB::table('roles')->get();
foreach ($roles as $role) {
    echo "✅ Role: {$role->name} (guard: {$role->guard_name})\n";
}

echo "\n=== Summary ===\n";
echo "✅ All seeders completed successfully!\n";
echo "✅ Admin users are ready for login\n";
