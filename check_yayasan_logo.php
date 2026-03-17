<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Checking Yayasan Logo Data ===\n";

$yayasan = \App\Models\Yayasan::first();

if ($yayasan) {
    echo "ID: " . $yayasan->id . "\n";
    echo "Name: " . ($yayasan->name ?? 'NULL') . "\n";
    echo "Logo: " . ($yayasan->logo ?? 'NULL') . "\n";
    
    if ($yayasan->logo) {
        echo "Logo path exists: " . (Storage::disk('public')->exists($yayasan->logo) ? 'YES' : 'NO') . "\n";
        echo "Full logo URL: " . Storage::url($yayasan->logo) . "\n";
    } else {
        echo "Logo is NULL\n";
    }
} else {
    echo "No Yayasan found\n";
}

echo "\n=== Checking Storage Directory ===\n";
echo "Storage path: " . storage_path('app/public') . "\n";

if (is_dir(storage_path('app/public'))) {
    echo "Public storage directory exists\n";
    $files = scandir(storage_path('app/public'));
    echo "Contents: " . implode(', ', array_diff($files, ['.', '..'])) . "\n";
} else {
    echo "Public storage directory does not exist\n";
}

echo "\n=== Checking File Permissions ===\n";
$publicPath = storage_path('app/public');
if (is_dir($publicPath)) {
    echo "Permissions: " . substr(sprintf('%o', fileperms($publicPath)), -4) . "\n";
}
