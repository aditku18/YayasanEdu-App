<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Foundation;
use App\Models\Student;
use App\Models\Plan;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Foundation::count() === 0) {
            $plan = Plan::first();

            Foundation::create([
                'name' => 'Demo Yayasan',
                'subdomain' => 'demoyayasan',
                'email' => 'demo@yayasan.test',
                'status' => 'trial',
                'plan_id' => $plan ? $plan->id : null,
            ]);
            $this->command->info('Demo Foundation created.');
        } else {
            $this->command->info('Foundations exist, skipping.');
        }

        if (Student::count() === 0) {
            Student::create([
                'name' => 'Siswa Contoh',
                'nis' => 'NIS123',
                'nisn' => 'NISN123',
                'gender' => 'L',
                'birth_place' => 'Jakarta',
                'birth_date' => now()->subYears(10)->toDateString(),
                'address' => 'Jl. Contoh',
                'parent_name' => 'Ortu Contoh',
                'parent_phone' => '08123456789',
                'status' => 'Aktif',
            ]);
            $this->command->info('Demo Student created.');
        } else {
            $this->command->info('Students exist, skipping.');
        }
    }
}
