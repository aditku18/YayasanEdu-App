<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $platformAdmin = Role::firstOrCreate(['name' => 'platform_admin', 'guard_name' => 'web']);
        $foundationAdmin = Role::firstOrCreate(['name' => 'foundation_admin', 'guard_name' => 'web']);
        $schoolAdmin = Role::firstOrCreate(['name' => 'school_admin', 'guard_name' => 'web']);
        $teacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $student = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        // Create basic permissions
        $permissions = [
            'view_dashboard',
            'manage_foundations',
            'manage_users',
            'manage_subscriptions',
            'manage_payments',
            'manage_invoices',
            'manage_plugins',
            'manage_storage',
            'manage_webhooks',
            'manage_statistics',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign permissions to platform admin
        $platformAdmin->syncPermissions($permissions);

        // Assign specific permissions to foundation admin
        $foundationAdmin->syncPermissions([
            'view_dashboard',
            'manage_foundations',
            'manage_users',
            'manage_subscriptions',
        ]);

        // Assign specific permissions to school admin
        $schoolAdmin->syncPermissions([
            'view_dashboard',
            'manage_users',
        ]);

        // Teacher permissions
        $teacher->syncPermissions([
            'view_dashboard',
        ]);

        // Student permissions (minimal)
        $student->syncPermissions([
            'view_dashboard',
        ]);
    }
}
