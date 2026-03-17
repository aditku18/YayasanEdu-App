<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plugin;

class CbtPluginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // CBT Plugin
        $plugin = Plugin::updateOrCreate(
            ['name' => 'CBT (Computer-Based Training)'],
            [
                'description' => 'Plugin LMS lengkap untuk pembuatan kursus, quiz, progress tracking, dan sertifikasi. Fitur mencakup berbagai jenis soal (multiple choice, true/false, essay, drag-drop, matching), timer quiz, grading otomatis dan manual, analytics, serta sistem sertifikat.',
                'version' => '1.0.0',
                'category' => 'education',
                'developer' => 'YayasanEdu Team',
                'price' => 150000,
                'is_available_in_marketplace' => true,
                'status' => 'active',
                'features' => [
                    'Course Management - Pembuatan dan pengelolaan kursus/modul',
                    'Quiz System - 5 jenis soal dengan timer dan batas percobaan',
                    'Progress Tracking - Pelacakan kemajuan belajar siswa',
                    'Certificate System - Sertifikat completion dengan PDF generation',
                    'Analytics Dashboard - Laporan statistik lengkap',
                    'Anti-Cheat - Deteksi tab switching dan aktivitas mencurigakan',
                    'Import/Export - Support CSV, Excel, JSON, QTI',
                    'RESTful API - Integrasi dengan platform lain',
                    'Responsive Design - Mobile dan desktop friendly'
                ],
                'requirements' => [
                    'Laravel 10.x or higher',
                    'PHP 8.1 or higher',
                    'MySQL 8.0 or higher',
                    'barryvdh/laravel-dompdf for certificate PDF',
                    'maatwebsite/excel for import/export'
                ],
                'documentation_url' => '#'
            ]
        );

        // Payment Gateway Plugin
        Plugin::updateOrCreate(
            ['name' => 'Payment Gateway'],
            [
                'description' => 'Plugin pembayaran online lengkap dengan dukungan multiple payment methods: Transfer Bank, Virtual Account, E-Wallet (GoPay, OVO, DANA, LinkAja), QRIS, dan Kartu Kredit. Fitur还包括自动发票生成和支付通知。',
                'version' => '1.0.0',
                'category' => 'finance',
                'developer' => 'YayasanEdu Team',
                'price' => 200000,
                'is_available_in_marketplace' => true,
                'status' => 'active',
                'features' => [
                    'Multiple Payment Methods',
                    'Automatic Invoice Generation',
                    'Payment Notification',
                    'Transaction History',
                    'Refund Management',
                    'Payment Reports'
                ],
                'requirements' => ['Laravel 10.x'],
                'documentation_url' => '#'
            ]
        );

        // SMS Notification Plugin
        Plugin::updateOrCreate(
            ['name' => 'SMS Notification'],
            [
                'description' => 'Plugin notifikasi SMS untuk sekolah. Kirim pengumuman, laporan kehadiran, dan informasi penting melalui SMS ke orang tua siswa.',
                'version' => '1.0.0',
                'category' => 'communication',
                'developer' => 'YayasanEdu Team',
                'price' => 100000,
                'is_available_in_marketplace' => true,
                'status' => 'active',
                'features' => [
                    'Bulk SMS Sending',
                    'Scheduled Messages',
                    'Attendance Notifications',
                    'Announcement Broadcast',
                    'Parent Communication',
                    'SMS Templates'
                ],
                'requirements' => ['Laravel 10.x'],
                'documentation_url' => '#'
            ]
        );

        $this->command->info('Plugins registered successfully!');
    }
}
