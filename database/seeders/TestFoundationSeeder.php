<?php

namespace Database\Seeders;

use App\Models\Foundation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestFoundationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Foundation::create([
            'name' => 'Test Foundation Pending',
            'email' => 'test@example.com',
            'phone' => '08123456789',
            'subdomain' => 'testfoundation.localhost',
            'status' => 'pending',
            'plan_id' => null,
            'trial_ends_at' => null,
            'subscription_ends_at' => null,
            'tenant_id' => null,
        ]);

        Foundation::create([
            'name' => 'Demo Foundation',
            'email' => 'demo@example.com',
            'phone' => '08123456790',
            'subdomain' => 'demofoundation.localhost',
            'status' => 'pending',
            'plan_id' => null,
            'trial_ends_at' => null,
            'subscription_ends_at' => null,
            'tenant_id' => null,
        ]);
    }
}
