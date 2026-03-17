<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plugin;

class PluginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plugins = [
            [
                'name' => 'PPDB',
                'description' => 'Penerimaan Peserta Didik Baru (PPDB) System - Sistem penerimaan siswa baru untuk sekolah',
                'version' => '2.0.0',
                'category' => 'Akademik',
                'developer' => 'YayasanEdu',
                'price' => 0,
                'is_available_in_marketplace' => true,
                'status' => 'active',
                'features' => json_encode([
                    'Pendaftaran online siswa baru',
                    'Pengelolaan gelombang penerimaan',
                    'Verifikasi data applicants',
                    'Pembayaran biaya pendaftaran',
                    'Dashboard statistik',
                    'Export data applicants',
                ]),
                'requirements' => json_encode([
                    'PHP >= 8.1',
                    'Laravel >= 10.0',
                ]),
                'documentation_url' => '#',
            ],
            [
                'name' => 'Attendance System',
                'description' => 'Sistem Absensi digital untuk mengelola kehadiran siswa dan guru',
                'version' => '1.0.0',
                'category' => 'Kehadiran',
                'developer' => 'YayasanEdu',
                'price' => 0,
                'is_available_in_marketplace' => true,
                'status' => 'active',
                'features' => json_encode([
                    'Absensi harian siswa',
                    'Absensi guru',
                    'Laporan kehadiran',
                    'Export data absensi',
                    'Notifikasi ketidakhadiran',
                ]),
                'requirements' => json_encode([
                    'PHP >= 8.1',
                    'Laravel >= 10.0',
                ]),
                'documentation_url' => '#',
            ],
            [
                'name' => 'E-Learning',
                'description' => 'Platform pembelajaran digital untuk mendukung kegiatan belajar mengajar',
                'version' => '1.0.0',
                'category' => 'Akademik',
                'developer' => 'YayasanEdu',
                'price' => 0,
                'is_available_in_marketplace' => true,
                'status' => 'active',
                'features' => json_encode([
                    'Pengelolaan materi pembelajaran',
                    'Tugas dan quiz',
                    'Diskusi online',
                    'Progress tracking',
                    'Online class',
                ]),
                'requirements' => json_encode([
                    'PHP >= 8.1',
                    'Laravel >= 10.0',
                ]),
                'documentation_url' => '#',
            ],
            [
                'name' => 'E-Learning',
                'description' => 'Platform pembelajaran digital untuk mendukung kegiatan belajar mengajar',
                'version' => '1.0.0',
                'category' => 'Akademik',
                'developer' => 'YayasanEdu',
                'price' => 0,
                'is_available_in_marketplace' => true,
                'status' => 'active',
                'features' => json_encode([
                    'Pengelolaan materi pembelajaran',
                    'Tugas dan quiz',
                    'Diskusi online',
                    'Progress tracking',
                    'Online class',
                ]),
                'requirements' => json_encode([
                    'PHP >= 8.1',
                    'Laravel >= 10.0',
                ]),
                'documentation_url' => '#',
            ],
            [
                'name' => 'Exam',
                'description' => 'Sistem ujian online untuk evaluasi siswa',
                'version' => '1.0.0',
                'category' => 'Akademik',
                'developer' => 'YayasanEdu',
                'price' => 0,
                'is_available_in_marketplace' => true,
                'status' => 'active',
                'features' => json_encode([
                    'Bank soal',
                    'Ujian online',
                    'Grading otomatis',
                    'Hasil ujian',
                    'Analisis hasil',
                ]),
                'requirements' => json_encode([
                    'PHP >= 8.1',
                    'Laravel >= 10.0',
                ]),
                'documentation_url' => '#',
            ],
            [
                'name' => 'Payment',
                'description' => 'Sistem pembayaran spp dan biaya sekolah',
                'version' => '1.0.0',
                'category' => 'Keuangan',
                'developer' => 'YayasanEdu',
                'price' => 0,
                'is_available_in_marketplace' => true,
                'status' => 'active',
                'features' => json_encode([
                    'Pengelolaan spp',
                    'Pembayaran online',
                    'Invoice otomatis',
                    'Laporan keuangan',
                    'Notifikasi pembayaran',
                ]),
                'requirements' => json_encode([
                    'PHP >= 8.1',
                    'Laravel >= 10.0',
                ]),
                'documentation_url' => '#',
            ],
            [
                'name' => 'Library',
                'description' => 'Sistem perpustakaan digital untuk mengelola koleksi buku',
                'version' => '1.0.0',
                'category' => 'Akademik',
                'developer' => 'YayasanEdu',
                'price' => 0,
                'is_available_in_marketplace' => true,
                'status' => 'active',
                'features' => json_encode([
                    'Katalog buku',
                    'Peminjaman buku',
                    'Pengembalian buku',
                    'Denda keterlambatan',
                    'Laporan perpustakaan',
                ]),
                'requirements' => json_encode([
                    'PHP >= 8.1',
                    'Laravel >= 10.0',
                ]),
                'documentation_url' => '#',
            ],
            [
                'name' => 'CBT',
                'description' => 'Computer Based Test untuk ujian nasional dan ujian sekolah',
                'version' => '1.0.0',
                'category' => 'Akademik',
                'developer' => 'YayasanEdu',
                'price' => 0,
                'is_available_in_marketplace' => true,
                'status' => 'active',
                'features' => json_encode([
                    'Ujian berbasis komputer',
                    'Bank soal lengkap',
                    'Monitoring ujian',
                    'Hasil otomatis',
                    'Analisis butir soal',
                ]),
                'requirements' => json_encode([
                    'PHP >= 8.1',
                    'Laravel >= 10.0',
                ]),
                'documentation_url' => '#',
            ],
        ];

        foreach ($plugins as $plugin) {
            Plugin::updateOrCreate(
                ['name' => $plugin['name']],
                $plugin
            );
        }
    }
}
