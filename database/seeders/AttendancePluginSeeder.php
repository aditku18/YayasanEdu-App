<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plugin;

class AttendancePluginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Attendance Plugin
        $plugin = Plugin::updateOrCreate(
            ['name' => 'Attendance System'],
            [
                'description' => 'Sistem absensi lengkap dengan dukungan multiple metode otentikasi: QR Code, Fingerprint, Face Recognition, RFID Card, dan GPS Location. Fitur mencakup waktu kedatang dan kepulangan, laporan kehadiran, analisis keterlambatan, dan export data.',
                'version' => '1.0.0',
                'category' => 'management',
                'developer' => 'YayasanEdu Team',
                'price' => 250000,
                'is_available_in_marketplace' => true,
                'status' => 'active',
                'features' => [
                    'QR Code Attendance - Scan kode unik dengan timer kedaluwarsa',
                    'Fingerprint Integration - Sensor biometrik dengan anti-spoofing',
                    'Face Recognition - Deteksi wajah real-time dengan liveness detection',
                    'RFID Card Support - Kartu proximity dengan enkripsi data',
                    'GPS Attendance - Check-in jarak jauh dengan geofencing',
                    'Attendance Reports - Analisis lengkap dengan export PDF/Excel/CSV',
                    'Audit Logs - Pencatatan semua event kehadiran',
                    'Role-Based Access - Kontrol akses administrator dan pengguna',
                    'Backup & Recovery - Mekanisme backup data otomatis',
                    'RESTful API - Endpoint integrasi pihak ketiga',
                    'Multi-Device Support - Kompatibel berbagai perangkat',
                    'Real-time Dashboard - Visualisasi data kehadiran'
                ],
                'requirements' => [
                    'Laravel 10.x or higher',
                    'PHP 8.1 or higher',
                    'MySQL 8.0 or higher',
                    'OpenSSL untuk enkripsi data',
                    'Kamera untuk Face Recognition (opsional)',
                    'Sensor Fingerprint (opsional)',
                    'RFID Reader (opsional)'
                ],
                'documentation_url' => '#'
            ]
        );

        $this->command->info('Attendance Plugin registered successfully!');
    }
}
