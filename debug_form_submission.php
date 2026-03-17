<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Debugging Form Submission Issues ===\n";

// Check if storage link exists
$publicStoragePath = public_path('storage');
$storageLinkTarget = storage_path('app/public');

echo "Storage link check:\n";
echo "- Public storage path: " . $publicStoragePath . "\n";
echo "- Storage target path: " . $storageLinkTarget . "\n";
echo "- Link exists: " . (is_link($publicStoragePath) ? 'YES' : 'NO') . "\n";

if (is_link($publicStoragePath)) {
    echo "- Link target: " . readlink($publicStoragePath) . "\n";
} else {
    echo "- Creating storage link...\n";
    try {
        $result = symlink($storageLinkTarget, $publicStoragePath);
        echo "- Link creation result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
    } catch (Exception $e) {
        echo "- Link creation error: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Checking File Permissions ===\n";
$paths = [
    'storage/app/public',
    'storage/app/public/logos',
    'public/storage'
];

foreach ($paths as $path) {
    $fullPath = base_path($path);
    echo "- " . $path . ": ";
    if (file_exists($fullPath)) {
        echo "EXISTS, permissions: " . substr(sprintf('%o', fileperms($fullPath)), -4);
        if (is_writable($fullPath)) {
            echo " (WRITABLE)";
        } else {
            echo " (NOT WRITABLE)";
        }
    } else {
        echo "NOT EXISTS";
    }
    echo "\n";
}

echo "\n=== Checking Current Logo Files ===\n";
$tenant = \App\Models\Tenant::find('tenant-yayasan-hidayattul-amin');
if ($tenant) {
    tenancy()->initialize($tenant);
    
    $yayasan = \App\Models\Yayasan::first();
    if ($yayasan && $yayasan->logo) {
        echo "Current logo: " . $yayasan->logo . "\n";
        
        $fullPath = storage_path('app/public/' . $yayasan->logo);
        echo "Full path: " . $fullPath . "\n";
        echo "File exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
        
        if (file_exists($fullPath)) {
            echo "File size: " . filesize($fullPath) . " bytes\n";
            echo "File type: " . mime_content_type($fullPath) . "\n";
        }
        
        $publicPath = public_path('storage/' . $yayasan->logo);
        echo "Public path: " . $publicPath . "\n";
        echo "Public file exists: " . (file_exists($publicPath) ? 'YES' : 'NO') . "\n";
        
        // Test URL accessibility
        $url = asset('storage/' . $yayasan->logo);
        echo "Asset URL: " . $url . "\n";
    }
}

echo "\n=== Checking PHP Upload Settings ===\n";
$uploadSettings = [
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'max_file_uploads' => ini_get('max_file_uploads'),
    'file_uploads' => ini_get('file_uploads'),
    'max_execution_time' => ini_get('max_execution_time'),
    'memory_limit' => ini_get('memory_limit')
];

foreach ($uploadSettings as $key => $value) {
    echo "- " . $key . ": " . $value . "\n";
}

echo "\n=== Testing HTTP Request Simulation ===\n";
echo "To test the actual form submission:\n";
echo "1. Open browser to: http://yayasan-hidayattul-amin.localhost:8000/yayasan/profil\n";
echo "2. Open browser developer tools (F12)\n";
echo "3. Go to Network tab\n";
echo "4. Try uploading a logo\n";
echo "5. Check the network request for any errors\n";
echo "6. Check the response from server\n";

echo "\n=== Common Issues to Check ===\n";
echo "1. Form enctype='multipart/form-data' - REQUIRED for file uploads\n";
echo "2. CSRF token present\n";
echo "3. File size within limits (2MB max in validation)\n";
echo "4. File type validation (jpeg,png,jpg,gif,svg)\n";
echo "5. JavaScript errors preventing form submission\n";
echo "6. Network connectivity issues\n";
