<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing Logo URL Access ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "✓ Tenant found\n";
    
    $tenant->run(function() {
        if (class_exists('App\Models\Yayasan')) {
            $yayasan = \App\Models\Yayasan::first();
            if ($yayasan && $yayasan->logo) {
                echo "Logo path: " . $yayasan->logo . "\n";
                
                // Generate URL
                $url = Storage::url($yayasan->logo);
                echo "Storage URL: " . $url . "\n";
                
                // Test different URL formats
                $testUrls = [
                    'Generated URL' => $url,
                    'Direct URL' => '/storage/' . $yayasan->logo,
                    'Full URL' => 'http://yayasan-kemala-bhayangkari.localhost:8000/storage/' . $yayasan->logo,
                ];
                
                foreach ($testUrls as $name => $testUrl) {
                    echo "\n--- Testing $name ---\n";
                    echo "URL: $testUrl\n";
                    
                    // Check if file exists via public path
                    $publicPath = public_path('storage/' . $yayasan->logo);
                    echo "Public path: $publicPath\n";
                    echo "File exists: " . (file_exists($publicPath) ? 'YES' : 'NO') . "\n";
                    
                    if (file_exists($publicPath)) {
                        echo "File size: " . filesize($publicPath) . " bytes\n";
                    }
                    
                    // Test HTTP request if curl available
                    if (function_exists('curl_init') && strpos($testUrl, 'http') === 0) {
                        $ch = curl_init($testUrl);
                        curl_setopt($ch, CURLOPT_NOBODY, true);
                        curl_setopt($ch, CURLOPT_HEADER, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_exec($ch);
                        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        curl_close($ch);
                        echo "HTTP Code: $httpCode " . ($httpCode == 200 ? '✓' : '✗') . "\n";
                    }
                }
                
                // Check directory structure
                echo "\n--- Directory Structure ---\n";
                $logoDir = dirname(public_path('storage/' . $yayasan->logo));
                echo "Logo directory: $logoDir\n";
                echo "Directory exists: " . (is_dir($logoDir) ? 'YES' : 'NO') . "\n";
                
                if (is_dir($logoDir)) {
                    $files = scandir($logoDir);
                    echo "Directory contents: " . implode(', ', array_diff($files, ['.', '..'])) . "\n";
                }
                
                // Create a simple HTML test
                echo "\n--- HTML Test ---\n";
                $htmlTest = '<!DOCTYPE html>
<html>
<head>
    <title>Logo Test</title>
</head>
<body>
    <h1>Logo Test</h1>
    <p>Testing logo display:</p>
    <img src="' . $url . '" alt="Logo" onerror="this.style.border=\'2px solid red\'" onload="this.style.border=\'2px solid green\'" />
    <p>URL: ' . $url . '</p>
</body>
</html>';
                
                file_put_contents(public_path('logo-test.html'), $htmlTest);
                echo "HTML test created: http://yayasan-kemala-bhayangkari.localhost:8000/logo-test.html\n";
            }
        }
    });
} else {
    echo "✗ Tenant not found\n";
}
