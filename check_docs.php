<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\PPDBApplicant;

$reg = 'PPDB-20260003';
$applicant = PPDBApplicant::where('registration_number', $reg)->first();

if ($applicant) {
    echo "Applicant: " . $applicant->name . "\n";
    echo "KK: " . ($applicant->document_kk ?: 'Empty') . "\n";
    echo "Akta: " . ($applicant->document_akta ?: 'Empty') . "\n";
    echo "Ijazah: " . ($applicant->document_ijazah ?: 'Empty') . "\n";
    echo "Foto: " . ($applicant->document_foto ?: 'Empty') . "\n";
} else {
    echo "Applicant not found: " . $reg . "\n";
}
