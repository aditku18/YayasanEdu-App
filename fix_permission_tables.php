<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== Fixing Permission Tables ===\n";

// Check if permission tables exist
$tables = ['permissions', 'roles', 'role_has_permissions', 'model_has_roles', 'model_has_permissions'];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo "✅ Table '$table' exists\n";
    } else {
        echo "❌ Table '$table' missing\n";
    }
}

// Create roles manually if not exists
if (!Schema::hasTable('roles')) {
    echo "\nCreating roles table...\n";
    DB::statement("
        CREATE TABLE roles (
            id bigint unsigned auto_increment primary key,
            name varchar(255) not null,
            guard_name varchar(255) not null,
            created_at timestamp null,
            updated_at timestamp null,
            unique roles_name_guard_name_unique (name, guard_name)
        ) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
    ");
    echo "✅ Created roles table\n";
}

// Create model_has_roles table if not exists
if (!Schema::hasTable('model_has_roles')) {
    echo "\nCreating model_has_roles table...\n";
    DB::statement("
        CREATE TABLE model_has_roles (
            role_id bigint unsigned not null,
            model_type varchar(255) not null,
            model_id bigint unsigned not null,
            primary model_has_roles_role_model_type_model_id_primary (role_id, model_type, model_id),
            foreign key (role_id) references roles (id) on delete cascade
        ) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
    ");
    echo "✅ Created model_has_roles table\n";
}

// Insert platform_admin role if not exists
$roleExists = DB::table('roles')->where('name', 'platform_admin')->where('guard_name', 'web')->exists();
if (!$roleExists) {
    echo "\nInserting platform_admin role...\n";
    DB::table('roles')->insert([
        'name' => 'platform_admin',
        'guard_name' => 'web',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Inserted platform_admin role\n";
} else {
    echo "\n✅ platform_admin role already exists\n";
}

// Assign role to admin user
use App\Models\User;
$user = User::where('email', 'admin@edusaas.com')->first();
if ($user) {
    $platformAdminRole = DB::table('roles')->where('name', 'platform_admin')->where('guard_name', 'web')->first();
    if ($platformAdminRole) {
        $hasRole = DB::table('model_has_roles')
            ->where('role_id', $platformAdminRole->id)
            ->where('model_type', 'App\Models\User')
            ->where('model_id', $user->id)
            ->exists();
        
        if (!$hasRole) {
            echo "\nAssigning platform_admin role to admin user...\n";
            DB::table('model_has_roles')->insert([
                'role_id' => $platformAdminRole->id,
                'model_type' => 'App\Models\User',
                'model_id' => $user->id
            ]);
            echo "✅ Assigned platform_admin role to admin user\n";
        } else {
            echo "\n✅ Admin user already has platform_admin role\n";
        }
    }
}

echo "\n=== Testing Fixed Setup ===\n";
if ($user) {
    $hasRole = $user->hasRole('platform_admin');
    echo "User hasRole('platform_admin'): " . ($hasRole ? 'YES' : 'NO') . "\n";
    
    if ($hasRole) {
        echo "✅ SUCCESS: Admin user should now have access to platform/dashboard\n";
    } else {
        echo "❌ FAILED: Still no access\n";
    }
}
