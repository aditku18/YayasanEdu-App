<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\Foundation;

class SampleInvoiceSeeder extends Seeder
{
    public function run()
    {
        // Get or create foundation
        $foundation = Foundation::where('tenant_id', 'sample-tenant')->first();
        if (!$foundation) {
            $this->command->error('Foundation not found. Please run foundation seeder first.');
            return;
        }

        // Get Professional plan
        $plan = Plan::where('slug', 'professional')->first();
        if (!$plan) {
            $this->command->error('Professional plan not found. Please run PlanSeeder first.');
            return;
        }

        // Create sample subscription
        $subscription = Subscription::updateOrCreate([
            'foundation_id' => $foundation->id,
            'plan_id' => $plan->id,
        ], [
            'status' => 'active',
            'starts_at' => now()->startOfMonth(),
            'ends_at' => now()->endOfMonth(),
            'price' => $plan->price_per_month,
            'billing_cycle' => 'monthly',
            'auto_renew' => true,
        ]);

        // Create sample invoice with Professional plan
        $invoice = Invoice::updateOrCreate([
            'foundation_id' => $foundation->id,
            'subscription_id' => $subscription->id,
            'invoice_number' => 'INV-202503-0001',
        ], [
            'amount' => $plan->price_per_month,
            'status' => 'unpaid',
            'due_date' => now()->addDays(7),
            'items' => [
                [
                    'type' => 'subscription',
                    'name' => 'Paket Professional',
                    'description' => 'Paket terbaik untuk yayasan dengan banyak sekolah',
                    'price' => $plan->price_per_month,
                    'period' => now()->format('F Y'),
                    'duration' => '1 Bulan',
                    'features' => $plan->getFeaturesArray(),
                ]
            ],
        ]);

        // Create another invoice with plugins
        $invoiceWithPlugins = Invoice::updateOrCreate([
            'foundation_id' => $foundation->id,
            'invoice_number' => 'INV-202503-0002',
        ], [
            'subscription_id' => $subscription->id,
            'amount' => $plan->price_per_month + 350000, // Professional + CBT + SMS
            'status' => 'unpaid',
            'due_date' => now()->addDays(14),
            'items' => [
                [
                    'type' => 'subscription',
                    'name' => 'Paket Professional',
                    'description' => 'Paket terbaik untuk yayasan dengan banyak sekolah',
                    'price' => $plan->price_per_month,
                    'period' => now()->format('F Y'),
                    'duration' => '1 Bulan',
                    'features' => $plan->getFeaturesArray(),
                ],
                [
                    'type' => 'plugin',
                    'name' => 'CBT (Computer-Based Training)',
                    'description' => 'Plugin LMS lengkap untuk pembuatan kursus, quiz, progress tracking, dan sertifikasi',
                    'price' => 150000,
                    'category' => 'education',
                    'version' => '1.0.0',
                    'features' => [
                        'Course Management - Pembuatan dan pengelolaan kursus/modul',
                        'Quiz System - 5 jenis soal dengan timer dan batas percobaan',
                        'Progress Tracking - Pelacakan kemajuan belajar siswa',
                        'Certificate System - Sertifikat completion dengan PDF generation',
                    ],
                ],
                [
                    'type' => 'plugin',
                    'name' => 'SMS Notification',
                    'description' => 'Plugin notifikasi SMS untuk sekolah. Kirim pengumuman, laporan kehadiran, dan informasi penting',
                    'price' => 100000,
                    'category' => 'communication',
                    'version' => '1.0.0',
                    'features' => [
                        'Bulk SMS Sending',
                        'Scheduled Messages',
                        'Attendance Notifications',
                        'Parent Communication',
                    ],
                ],
                [
                    'type' => 'setup',
                    'name' => 'Setup & Konfigurasi Awal',
                    'description' => 'Biaya setup awal platform termasuk migrasi data dan training',
                    'price' => 100000,
                    'period' => 'One-time',
                ],
            ],
        ]);

        $this->command->info('Sample invoices created successfully!');
        $this->command->info('Invoice 1: ' . $invoice->invoice_number . ' - Paket Professional only');
        $this->command->info('Invoice 2: ' . $invoiceWithPlugins->invoice_number . ' - Paket Professional + Plugins');
    }
}
