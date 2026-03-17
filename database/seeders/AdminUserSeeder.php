<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create or find platform admin role
        $adminRole = Role::firstOrCreate(['name' => 'platform_admin']);

        // Create admin user
        $admin = User::firstOrCreate([
            'email' => 'payment@admin.com',
        ], [
            'name' => 'Payment Admin',
            'password' => bcrypt('admin123'),
            'email_verified_at' => now(),
        ]);

        // Assign role
        $admin->assignRole($adminRole);

        $this->command->info('Admin user created: payment@admin.com / admin123');
    }
}
