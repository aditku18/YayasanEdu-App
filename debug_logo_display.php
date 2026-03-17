<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Debug Logo Display Issue ===\n";

// Find the tenant
$tenant = \App\Models\Tenant::find('tenant-yayasan-hidayattul-amin');
if (!$tenant) {
    echo "Tenant not found!\n";
    exit(1);
}

// Initialize tenancy
tenancy()->initialize($tenant);

echo "Tenant initialized: " . $tenant->id . "\n";

// Get current yayasan data
$yayasan = \App\Models\Yayasan::first();
if (!$yayasan) {
    echo "No Yayasan found!\n";
    exit(1);
}

echo "Current Yayasan data:\n";
echo "- ID: " . $yayasan->id . "\n";
echo "- Name: " . $yayasan->name . "\n";
echo "- Logo in DB: " . ($yayasan->logo ?? 'NULL') . "\n";

if ($yayasan->logo) {
    // Test different URL generation methods
    echo "\n=== URL Generation Tests ===\n";
    
    // Method 1: Storage::url()
    $url1 = Storage::url($yayasan->logo);
    echo "Storage::url(): " . $url1 . "\n";
    
    // Method 2: asset('storage/' . $path)
    $url2 = asset('storage/' . $yayasan->logo);
    echo "asset('storage/'): " . $url2 . "\n";
    
    // Method 3: Direct URL
    $url3 = '/storage/' . $yayasan->logo;
    echo "Direct URL: " . $url3 . "\n";
    
    // Check file existence in different locations
    echo "\n=== File Location Checks ===\n";
    
    $publicPath = public_path('storage/' . $yayasan->logo);
    echo "Public storage path: " . $publicPath . "\n";
    echo "Public file exists: " . (file_exists($publicPath) ? 'YES' : 'NO') . "\n";
    
    if (file_exists($publicPath)) {
        echo "File size: " . filesize($publicPath) . " bytes\n";
        echo "File permissions: " . substr(sprintf('%o', fileperms($publicPath)), -4) . "\n";
    }
    
    $storagePath = storage_path('app/public/' . $yayasan->logo);
    echo "Storage path: " . $storagePath . "\n";
    echo "Storage file exists: " . (file_exists($storagePath) ? 'YES' : 'NO') . "\n";
    
    // Test web server accessibility
    echo "\n=== Web Server Test ===\n";
    $testUrl = 'http://localhost' . $url3;
    echo "Testing URL: " . $testUrl . "\n";
    
    // Check if the file is accessible via localhost
    $context = stream_context_create([
        'http' => [
            'timeout' => 5
        ]
    ]);
    
    $headers = @get_headers($testUrl, 1, $context);
    if ($headers) {
        echo "HTTP Status: " . $headers[0] . "\n";
        if (isset($headers['Content-Type'])) {
            echo "Content-Type: " . $headers['Content-Type'] . "\n";
        }
        if (isset($headers['Content-Length'])) {
            echo "Content-Length: " . $headers['Content-Length'] . "\n";
        }
    } else {
        echo "❌ File not accessible via web server\n";
    }
    
    // Check view file for URL generation
    echo "\n=== View Template Check ===\n";
    $viewPath = resource_path('views/yayasan/profil.blade.php');
    if (file_exists($viewPath)) {
        $viewContent = file_get_contents($viewPath);
        
        // Look for logo URL generation patterns
        if (preg_match('/Storage::url\([^)]+\)/', $viewContent, $matches)) {
            echo "Found Storage::url() usage: " . $matches[0] . "\n";
        }
        
        if (preg_match('/asset\([^)]+\)/', $viewContent, $matches)) {
            echo "Found asset() usage: " . $matches[0] . "\n";
        }
        
        if (preg_match('/storage\/[^\'"\s]+/', $viewContent, $matches)) {
            echo "Found direct storage path: " . $matches[0] . "\n";
        }
    }
}

echo "\n=== Browser Debugging Steps ===\n";
echo "1. Open browser developer tools (F12)\n";
echo "2. Go to Network tab\n";
echo "3. Refresh the page\n";
echo "4. Look for the logo image request\n";
echo "5. Check the URL being requested\n";
echo "6. Check the response status (200, 404, etc.)\n";
echo "7. Check if the URL matches expected format\n";
