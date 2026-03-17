<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

// Check admin user
$user = User::where('email', 'admin@edusaas.com')->first();
if ($user) {
    echo "User found:\n";
    echo "- Name: " . $user->name . "\n";
    echo "- Email: " . $user->email . "\n";
    echo "- Tenant ID: " . ($user->tenant_id ?? 'null') . "\n";
    echo "- Email Verified: " . ($user->email_verified_at ? 'Yes' : 'No') . "\n";
    
    echo "\nChecking roles:\n";
    
    // Check roles table
    $roles = DB::table('roles')->get();
    echo "\nAvailable roles in database:\n";
    foreach ($roles as $role) {
        echo "- ID: {$role->id}, Name: {$role->name}\n";
    }
    
    // Check user_roles table
    $userRoles = DB::table('user_roles')->where('user_id', $user->id)->get();
    echo "\nUser roles directly assigned:\n";
    if ($userRoles->count() > 0) {
        foreach ($userRoles as $userRole) {
            echo "- Role ID: {$userRole->role_id}\n";
        }
    } else {
        echo "No roles assigned directly\n";
    }
    
    // Check if user has platform_admin role
    echo "\nChecking platform_admin role:\n";
    $platformAdminRole = DB::table('roles')->where('name', 'platform_admin')->first();
    if ($platformAdminRole) {
        echo "platform_admin role found with ID: {$platformAdminRole->id}\n";
        $hasRole = DB::table('user_roles')
            ->where('user_id', $user->id)
            ->where('role_id', $platformAdminRole->id)
            ->exists();
        echo "User has platform_admin role: " . ($hasRole ? 'Yes' : 'No') . "\n";
    } else {
        echo "platform_admin role not found in database\n";
    }
    
} else {
    echo "User admin@edusaas.com not found\n";
}
