<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Plugin;

$translations = [
    'Attendance System' => [
        'name' => 'Sistem Absensi',
        'description' => 'Bagian terintegrasi dari modul akademik untuk mengelola kehadiran harian siswa, absensi per mata pelajaran, rekap persentase, dan notifikasi ketidakhadiran ke orangtua secara real-time.'
    ],
    'Payment Gateway' => [
        'name' => 'Gerbang Pembayaran',
        'description' => 'Plugin pembayaran online lengkap dengan dukungan metode pembayaran bank transfer, e-wallet, credit card, dan QRIS.'
    ],
    'SMS Notification' => [
        'name' => 'Notifikasi SMS',
        'description' => 'Plugin notifikasi SMS untuk sekolah. Kirim pengumuman, laporan absensi, dan update ke nomor telepon orang tua.'
    ],
    'CBT (Computer-Based Training)' => [
        'name' => 'CBT (Ujian Online)',
        'description' => 'Plugin LMS lengkap untuk pembuatan kursus, kuis, pelacakan progres, dan bank soal.'
    ]
];

$updatedCount = 0;

foreach ($translations as $englishName => $indoData) {
    $plugins = Plugin::where('name', $englishName)->get();
    foreach ($plugins as $plugin) {
        $plugin->name = $indoData['name'];
        $plugin->description = $indoData['description'];
        
        if ($plugin->category == 'Academic' || $plugin->category == 'academic') $plugin->category = 'Akademik';
        if ($plugin->category == 'Finance' || $plugin->category == 'finance') $plugin->category = 'Keuangan';
        if ($plugin->category == 'Communication' || $plugin->category == 'communication') $plugin->category = 'Komunikasi';
        if ($plugin->category == 'Education' || $plugin->category == 'education') $plugin->category = 'Edukasi';

        $plugin->save();
        $updatedCount++;
        echo "Updated: {$englishName} -> {$indoData['name']}\n";
    }
}

echo "Total plugins updated: {$updatedCount}\n";
