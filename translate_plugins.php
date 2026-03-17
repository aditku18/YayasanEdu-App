<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Plugin;

$translations = [
    'Student Information System' => [
        'name' => 'Sistem Informasi Siswa',
        'description' => 'Sistem manajemen siswa komprehensif dengan pelacakan pendaftaran, catatan akademik, dan profil siswa.'
    ],
    'Financial Management System' => [
        'name' => 'Sistem Manajemen Keuangan',
        'description' => 'Manajemen keuangan lengkap dengan tagihan otomatis, pelacakan pembayaran, dan laporan keuangan.'
    ],
    'Attendance Management' => [
        'name' => 'Manajemen Kehadiran',
        'description' => 'Pelacakan kehadiran tingkat lanjut dengan laporan otomatis dan notifikasi ke orang tua.'
    ],
    'Library Management System' => [
        'name' => 'Sistem Manajemen Perpustakaan',
        'description' => 'Manajemen perpustakaan lengkap dengan sistem katalog, pelacakan peminjaman, dan denda.'
    ],
    'Human Resource Management' => [
        'name' => 'Manajemen Sumber Daya Manusia',
        'description' => 'Manajemen SDM komprehensif untuk sekolah dengan catatan pegawai, penggajian, dan evaluasi.'
    ],
    'Online Examination System' => [
        'name' => 'Sistem Ujian Online',
        'description' => 'Platform penilaian digital yang aman dengan pembuatan soal, auto-grading, dan analisis hasil.'
    ],
    'Communication Portal' => [
        'name' => 'Portal Komunikasi',
        'description' => 'Pusat komunikasi internal sekolah yang memfasilitasi pengumuman, pesan, dan kolaborasi.'
    ],
    'Transportation Management' => [
        'name' => 'Manajemen Transportasi',
        'description' => 'Pelacakan rute bus, manajemen armada transportasi sekolah, dan penjadwalan.'
    ],
    'Hostel Management' => [
        'name' => 'Manajemen Asrama',
        'description' => 'Sistem pengelolaan asrama lengkap dengan manajemen kamar, alokasi tempat tidur, dan laporan.'
    ]
];

$updatedCount = 0;

foreach ($translations as $englishName => $indoData) {
    $plugins = Plugin::where('name', $englishName)->get();
    foreach ($plugins as $plugin) {
        $plugin->name = $indoData['name'];
        $plugin->description = $indoData['description'];
        
        // Also translate categories if needed, let's just make them Title Case
        if ($plugin->category == 'Academic') $plugin->category = 'Akademik';
        if ($plugin->category == 'Finance') $plugin->category = 'Keuangan';
        if ($plugin->category == 'Library') $plugin->category = 'Perpustakaan';
        if ($plugin->category == 'HR') $plugin->category = 'SDM';
        if ($plugin->category == 'Communication') $plugin->category = 'Komunikasi';
        if ($plugin->category == 'Logistics') $plugin->category = 'Logistik';
        if ($plugin->category == 'management') $plugin->category = 'Manajemen';

        $plugin->save();
        $updatedCount++;
        echo "Updated: {$englishName} -> {$indoData['name']}\n";
    }
}

// Any other plugin translation
$otherPlugins = Plugin::all();
foreach ($otherPlugins as $p) {
    if (strpos(strtolower($p->description), 'comprehensive') !== false || strpos(strtolower($p->description), 'complete') !== false) {
        // Fallback translation for anything missed
        $desc = $p->description;
        $desc = str_replace(['Comprehensive', 'comprehensive'], 'Komprehensif', $desc);
        $desc = str_replace(['Complete', 'complete'], 'Lengkap', $desc);
        $desc = str_replace('with', 'dengan', $desc);
        $desc = str_replace('tracking', 'pelacakan', $desc);
        $desc = str_replace('management', 'manajemen', $desc);
        $desc = str_replace('records', 'catatan', $desc);
        $desc = str_replace('automated', 'otomatis', $desc);
        $desc = str_replace('reports', 'laporan', $desc);
        $p->description = $desc;

        if ($p->category == 'Academic') $p->category = 'Akademik';
        if ($p->category == 'Finance') $p->category = 'Keuangan';
        
        $p->save();
        echo "Fallback updated: {$p->name}\n";
    }
}

echo "Total plugins updated: {$updatedCount}\n";
