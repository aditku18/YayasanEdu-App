<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Debug Tenant Access ===\n";

// Check if tenant exists
$tenant = \App\Models\Tenant::find('tenant-yayasan-hidayattul-amin');
if (!$tenant) {
    echo "❌ Tenant not found!\n";
    exit(1);
}

echo "✅ Tenant found: " . $tenant->id . "\n";

// Check domains
$domains = \Stancl\Tenancy\Database\Models\Domain::where('tenant_id', $tenant->id)->get();
echo "Domains:\n";
foreach ($domains as $domain) {
    echo "  - " . $domain->domain . "\n";
}

// Initialize tenancy
try {
    tenancy()->initialize($tenant);
    echo "✅ Tenancy initialized\n";
} catch (Exception $e) {
    echo "❌ Failed to initialize tenancy: " . $e->getMessage() . "\n";
    exit(1);
}

// Check database connection
echo "Current database: " . config('database.default') . "\n";
echo "Database name: " . config('database.connections.mysql.database') . "\n";

// Get yayasan data
try {
    $yayasan = \App\Models\Yayasan::first();
    if ($yayasan) {
        echo "✅ Yayasan found\n";
        echo "  ID: " . $yayasan->id . "\n";
        echo "  Name: " . $yayasan->name . "\n";
        echo "  Logo: " . ($yayasan->logo ?? 'NULL') . "\n";
        
        if ($yayasan->logo) {
            // Check file paths
            $publicPath = public_path('storage/' . $yayasan->logo);
            echo "  Public file exists: " . (file_exists($publicPath) ? 'YES' : 'NO') . "\n";
            
            if (file_exists($publicPath)) {
                echo "  File size: " . filesize($publicPath) . " bytes\n";
                echo "  File readable: " . (is_readable($publicPath) ? 'YES' : 'NO') . "\n";
                
                // Try to read file content
                $content = file_get_contents($publicPath);
                if ($content !== false) {
                    echo "  File content length: " . strlen($content) . " bytes\n";
                    
                    // Check if it's a valid image
                    if (function_exists('getimagesizefromstring')) {
                        $imageInfo = @getimagesizefromstring($content);
                        if ($imageInfo) {
                            echo "  Image type: " . $imageInfo['mime'] . "\n";
                            echo "  Image dimensions: " . $imageInfo[0] . "x" . $imageInfo[1] . "\n";
                        } else {
                            echo "  ❌ Not a valid image file\n";
                        }
                    }
                } else {
                    echo "  ❌ Cannot read file content\n";
                }
            }
        }
    } else {
        echo "❌ No Yayasan found\n";
    }
} catch (Exception $e) {
    echo "❌ Error accessing Yayasan: " . $e->getMessage() . "\n";
}

// Test URL generation
echo "\n=== URL Generation Test ===\n";
if (isset($yayasan) && $yayasan->logo) {
    try {
        $url1 = Storage::url($yayasan->logo);
        echo "Storage::url(): " . $url1 . "\n";
        
        $url2 = asset('storage/' . $yayasan->logo);
        echo "asset(): " . $url2 . "\n";
        
        // Test if URL resolves correctly
        $fullPath = public_path('storage/' . $yayasan->logo);
        echo "Full path: " . $fullPath . "\n";
        
    } catch (Exception $e) {
        echo "❌ URL generation error: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Complete ===\n";
