<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PlanSeeder::class,
            DemoSeeder::class,
            PaymentGatewaySeeder::class,
            CbtPluginSeeder::class,
            AttendancePluginSeeder::class,
            PPDBPluginSeeder::class,
            RolesSeeder::class,
            AdminUserSeeder::class,
            PlatformAdminSeeder::class,
            PluginSeeder::class,
        ]);

        $this->command->info('Database seeding completed successfully!');
    }
}
