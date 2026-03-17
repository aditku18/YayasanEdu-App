<!DOCTYPE html>
<html>
<head>
    <title>Logo Test</title>
</head>
<body>
    <h1>Logo Test Page</h1>
    
    <h2>Current Logo Info:</h2>
    <?php
    require_once __DIR__ . '/../vendor/autoload.php';
    
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Find tenant and initialize
    $tenant = \App\Models\Tenant::find('tenant-yayasan-hidayattul-amin');
    if ($tenant) {
        tenancy()->initialize($tenant);
        
        $yayasan = \App\Models\Yayasan::first();
        if ($yayasan && $yayasan->logo) {
            echo '<p><strong>Logo in DB:</strong> ' . htmlspecialchars($yayasan->logo) . '</p>';
            
            $url = \Storage::url($yayasan->logo);
            echo '<p><strong>Storage URL:</strong> ' . htmlspecialchars($url) . '</p>';
            
            $assetUrl = asset('storage/' . $yayasan->logo);
            echo '<p><strong>Asset URL:</strong> ' . htmlspecialchars($assetUrl) . '</p>';
            
            echo '<h2>Logo Display Tests:</h2>';
            
            // Test 1: Storage URL
            echo '<h3>Test 1: Storage URL</h3>';
            echo '<img src="' . htmlspecialchars($url) . '" alt="Logo Test 1" style="border: 1px solid #ccc; max-width: 200px;">';
            
            // Test 2: Asset URL  
            echo '<h3>Test 2: Asset URL</h3>';
            echo '<img src="' . htmlspecialchars($assetUrl) . '" alt="Logo Test 2" style="border: 1px solid #ccc; max-width: 200px;">';
            
            // Test 3: Direct path
            echo '<h3>Test 3: Direct Path</h3>';
            echo '<img src="/storage/' . htmlspecialchars($yayasan->logo) . '" alt="Logo Test 3" style="border: 1px solid #ccc; max-width: 200px;">';
            
            // File existence check
            $publicPath = public_path('storage/' . $yayasan->logo);
            echo '<h3>File Status:</h3>';
            echo '<p>Public file exists: ' . (file_exists($publicPath) ? 'YES' : 'NO') . '</p>';
            if (file_exists($publicPath)) {
                echo '<p>File size: ' . filesize($publicPath) . ' bytes</p>';
            }
            
        } else {
            echo '<p>No logo found in database</p>';
        }
    } else {
        echo '<p>Tenant not found</p>';
    }
    ?>
    
    <h2>Manual Upload Test:</h2>
    <form action="http://yayasan-hidayattul-amin.localhost:8000/yayasan/profil" method="POST" enctype="multipart/form-data" target="_blank">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="file" name="logo" accept="image/*" required>
        <input type="text" name="name" value="YAYASAN HIDAYATTUL AMIN" required>
        <button type="submit">Upload Logo</button>
    </form>
</body>
</html>
