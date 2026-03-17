<?php
// Simple logo test page
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Initialize tenant
$tenant = \App\Models\Tenant::find('tenant-yayasan-hidayattul-amin');
if ($tenant) {
    tenancy()->initialize($tenant);
    
    $yayasan = \App\Models\Yayasan::first();
    if ($yayasan && $yayasan->logo) {
        $logoUrl = \Storage::url($yayasan->logo);
        $assetUrl = asset('storage/' . $yayasan->logo);
        
        echo "<h1>Logo Test Results</h1>";
        echo "<p><strong>Logo in DB:</strong> " . htmlspecialchars($yayasan->logo) . "</p>";
        echo "<p><strong>Storage URL:</strong> " . htmlspecialchars($logoUrl) . "</p>";
        echo "<p><strong>Asset URL:</strong> " . htmlspecialchars($assetUrl) . "</p>";
        
        echo "<h2>Test Images:</h2>";
        
        // Test 1: Storage URL
        echo "<h3>Storage URL:</h3>";
        echo '<img src="' . htmlspecialchars($logoUrl) . '" alt="Logo" style="border: 2px solid green; max-width: 200px;" onerror="this.style.border=\'2px solid red\'; this.alt=\'FAILED TO LOAD\';">';
        
        // Test 2: Asset URL
        echo "<h3>Asset URL:</h3>";
        echo '<img src="' . htmlspecialchars($assetUrl) . '" alt="Logo" style="border: 2px solid blue; max-width: 200px;" onerror="this.style.border=\'2px solid red\'; this.alt=\'FAILED TO LOAD\';">';
        
        // Test 3: Direct path
        echo "<h3>Direct Path:</h3>";
        echo '<img src="/storage/' . htmlspecialchars($yayasan->logo) . '" alt="Logo" style="border: 2px solid orange; max-width: 200px;" onerror="this.style.border=\'2px solid red\'; this.alt=\'FAILED TO LOAD\';">';
        
        // File existence check
        $publicPath = public_path('storage/' . $yayasan->logo);
        echo "<h2>File Status:</h2>";
        echo "<p>Public file exists: " . (file_exists($publicPath) ? 'YES ✅' : 'NO ❌') . "</p>";
        if (file_exists($publicPath)) {
            echo "<p>File size: " . filesize($publicPath) . " bytes</p>";
            echo "<p>File path: " . htmlspecialchars($publicPath) . "</p>";
        }
        
    } else {
        echo "<h1>No Logo Found</h1>";
        echo "<p>Either no yayasan found or no logo set.</p>";
    }
} else {
    echo "<h1>Tenant Not Found</h1>";
}
?>

<p><a href="/yayasan/profil">Back to Yayasan Profile</a></p>
<p><a href="javascript:location.reload()">Refresh Page</a></p>

<script>
// Auto-refresh every 5 seconds
setTimeout(function() {
    location.reload();
}, 5000);
</script>
