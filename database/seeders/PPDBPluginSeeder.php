<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plugin;

class PPDBPluginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PPDB Plugin Entry
        $plugin = Plugin::updateOrCreate(
            ['name' => 'PPDB (Penerimaan Peserta Didik Baru)'],
            [
                'description' => 'Sistem penerimaan peserta didik baru lengkap dengan gelombang pendaftaran, manajemen aplikan, tracking status, dan integrasi pembayaran. Fitur mencakup public registration portal, wave management, quota control, document upload, payment verification, dan comprehensive reporting dashboard.',
                'version' => '2.0.0',
                'category' => 'education',
                'developer' => 'YayasanEdu Team',
                'price' => 250000,
                'is_available_in_marketplace' => true,
                'status' => 'active',
                'features' => [
                    'Public Registration Portal - Form pendaftaran online yang user-friendly',
                    'Wave Management System - Manajemen gelombang pendaftaran dengan quota',
                    'Applicant Tracking - Tracking status aplikan real-time',
                    'Document Upload - Upload dokumen persyaratan dengan validasi',
                    'Payment Integration - Integrasi pembayaran online dan manual',
                    'Quota Management - Kontrol kapasitas per gelombang dan jurusan',
                    'Major Selection - Pemilihan jurusan dengan capacity tracking',
                    'Status Notifications - Notifikasi otomatis via email/SMS',
                    'Reporting Dashboard - Dashboard analitik dan laporan lengkap',
                    'Public Status Check - Cek status pendaftaran untuk publik',
                    'Multi-Tenant Support - Support multi-school dalam satu yayasan',
                    'Role-Based Access - Sistem permission yang fleksibel',
                    'Data Export - Export data dalam berbagai format (Excel, PDF, CSV)',
                    'Archive System - Arsip data aplikan dari tahun sebelumnya'
                ],
                'requirements' => [
                    'Laravel 10.x or higher',
                    'PHP 8.1 or higher',
                    'MySQL 8.0 or higher',
                    'Multi-tenant Architecture Support',
                    'File Storage System (local or cloud)',
                    'Email Notification System',
                    'Valid SSL Certificate untuk public portal',
                    'Minimum 1GB storage untuk dokumen upload'
                ],
                'documentation_url' => '/plugins/ppdb/docs'
            ]
        );

        $this->command->info('PPDB Plugin registered successfully!');
    }
}
