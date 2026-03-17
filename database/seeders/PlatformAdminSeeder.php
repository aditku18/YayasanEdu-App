<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PlatformAdminSeeder extends Seeder
{
    /**
     * Seed the platform admin user.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@yayasanedu.com'],
            [
                'name' => 'Platform Administrator',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'tenant_id' => null, // Platform admin has no tenant
                'role' => 'super_admin', // Platform admin role
                'is_active' => true,
            ]
        );
        
        $this->command->info('Platform admin user created successfully!');
        $this->command->info('Email: admin@yayasanedu.com');
        $this->command->info('Password: admin123');
    }
}
