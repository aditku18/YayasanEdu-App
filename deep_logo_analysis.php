<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEEP LOGO ANALYSIS ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "✓ Tenant found: " . $tenant->id . "\n";
    
    echo "\n=== 1. STORAGE CONFIGURATION ANALYSIS ===\n";
    
    // Check filesystem configuration
    $config = config('filesystems');
    echo "Default disk: " . $config['default'] . "\n";
    echo "Public disk root: " . $config['disks']['public']['root'] . "\n";
    echo "Public disk url: " . $config['disks']['public']['url'] . "\n";
    
    echo "\n=== 2. DATABASE CONNECTION ANALYSIS ===\n";
    
    // Check Foundation model connection
    $foundation = new \App\Models\Foundation();
    echo "Foundation model connection: " . $foundation->getConnectionName() . "\n";
    echo "Foundation table: " . $foundation->getTable() . "\n";
    
    // Test database operations
    try {
        $foundationRecord = \App\Models\Foundation::find(1);
        if ($foundationRecord) {
            echo "✓ Foundation record accessible\n";
            echo "Foundation name: " . $foundationRecord->name . "\n";
            echo "Logo path: " . ($foundationRecord->logo_path ?? 'NULL') . "\n";
        } else {
            echo "❌ Foundation record not found\n";
        }
    } catch (Exception $e) {
        echo "❌ Database error: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== 3. TENANT CONTEXT ANALYSIS ===\n";
    
    $tenant->run(function() {
        echo "Running in tenant context...\n";
        
        // Check storage paths in tenant context
        echo "Storage path: " . storage_path() . "\n";
        echo "App public path: " . storage_path('app/public') . "\n";
        
        // Test file operations in tenant context
        $testFile = storage_path('app/public/tenant-test.txt');
        $writeResult = file_put_contents($testFile, 'test content');
        echo "Tenant file write: " . ($writeResult !== false ? 'SUCCESS' : 'FAILED') . "\n";
        
        if ($writeResult !== false) {
            echo "Tenant file exists: " . (file_exists($testFile) ? 'YES' : 'NO') . "\n";
            unlink($testFile);
        }
        
        // Check Foundation in tenant context
        try {
            $tenantFoundation = \App\Models\Foundation::find(1);
            if ($tenantFoundation) {
                echo "✓ Foundation accessible in tenant context\n";
                echo "Tenant foundation logo_path: " . ($tenantFoundation->logo_path ?? 'NULL') . "\n";
            } else {
                echo "❌ Foundation not accessible in tenant context\n";
            }
        } catch (Exception $e) {
            echo "❌ Tenant context error: " . $e->getMessage() . "\n";
        }
    });
    
    echo "\n=== 4. REGISTRATION PROCESS SIMULATION ===\n";
    
    // Simulate the exact registration process
    echo "Simulating registration upload process...\n";
    
    // Create test upload data
    $testContent = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
    $tempFile = sys_get_temp_dir() . '/test-upload-' . time() . '.png';
    file_put_contents($tempFile, $testContent);
    
    // Create UploadedFile mock
    $uploadedFile = new \Illuminate\Http\UploadedFile(
        $tempFile,
        'test-logo.png',
        'image/png',
        null,
        true
    );
    
    // Test validation
    $validator = \Validator::make(['logo' => $uploadedFile], [
        'logo' => 'required|file|mimes:jpg,jpeg,png|max:1024'
    ]);
    
    echo "Validation result: " . ($validator->fails() ? 'FAILED' : 'PASSED') . "\n";
    if ($validator->fails()) {
        echo "Validation errors: " . json_encode($validator->errors()) . "\n";
    }
    
    // Test storage
    if (!$validator->fails()) {
        try {
            $path = $uploadedFile->store('temp/documents', 'public');
            echo "Storage result: " . ($path ? 'SUCCESS' : 'FAILED') . "\n";
            echo "Stored path: " . $path . "\n";
            
            if ($path) {
                echo "File exists in storage: " . (\Storage::disk('public')->exists($path) ? 'YES' : 'NO') . "\n";
                
                // Test move operation
                $finalPath = 'uploads/foundations/1/logo.png';
                $moveResult = \Storage::disk('public')->move($path, $finalPath);
                echo "Move result: " . ($moveResult ? 'SUCCESS' : 'FAILED') . "\n";
                
                if ($moveResult) {
                    echo "File exists at final location: " . (\Storage::disk('public')->exists($finalPath) ? 'YES' : 'NO') . "\n";
                    
                    // Test database update
                    try {
                        \App\Models\Foundation::find(1)->update(['logo_path' => $finalPath]);
                        echo "Database update: SUCCESS\n";
                        
                        // Verify update
                        $updated = \App\Models\Foundation::find(1);
                        echo "Updated logo_path: " . ($updated->logo_path ?? 'NULL') . "\n";
                    } catch (Exception $e) {
                        echo "Database update error: " . $e->getMessage() . "\n";
                    }
                }
            }
        } catch (Exception $e) {
            echo "Storage error: " . $e->getMessage() . "\n";
        }
    }
    
    // Cleanup
    unlink($tempFile);
    
    echo "\n=== 5. DISPLAY LOGIC ANALYSIS ===\n";
    
    // Check platform layout logic
    $platformLayout = resource_path('views/components/platform-layout.blade.php');
    if (file_exists($platformLayout)) {
        $content = file_get_contents($platformLayout);
        
        echo "Platform layout exists: YES\n";
        echo "Contains Foundation::find(1): " . (strpos($content, 'Foundation::find(1)') !== false ? 'YES' : 'NO') . "\n";
        echo "Contains Storage::url: " . (strpos($content, 'Storage::url') !== false ? 'YES' : 'NO') . "\n";
        echo "Contains logo_path: " . (strpos($content, 'logo_path') !== false ? 'YES' : 'NO') . "\n";
        
        // Check for potential errors
        if (strpos($content, 'function_exists') !== false) {
            echo "⚠ Uses function_exists() - potential issue\n";
        }
    }
    
    echo "\n=== 6. ROUTE AND MIDDLEWARE ANALYSIS ===\n";
    
    // Check registration routes
    $routes = \Route::getRoutes();
    $registrationRoutes = [];
    
    foreach ($routes as $route) {
        if (strpos($route->getName(), 'register.foundation') !== false) {
            $registrationRoutes[] = [
                'name' => $route->getName(),
                'uri' => $route->uri(),
                'action' => $route->getActionName(),
                'middleware' => $route->middleware()
            ];
        }
    }
    
    echo "Registration routes found: " . count($registrationRoutes) . "\n";
    foreach ($registrationRoutes as $route) {
        echo "- {$route['name']}: {$route['uri']} -> {$route['action']}\n";
        if (!empty($route['middleware'])) {
            echo "  Middleware: " . implode(', ', $route['middleware']) . "\n";
        }
    }
    
    echo "\n=== 7. LOG ANALYSIS ===\n";
    
    // Check Laravel logs
    $logFile = storage_path('logs/laravel.log');
    if (file_exists($logFile)) {
        echo "Laravel log exists: YES\n";
        echo "Log size: " . filesize($logFile) . " bytes\n";
        
        // Read last few lines
        $lines = file($logFile);
        $lastLines = array_slice($lines, -20);
        echo "Last 20 log entries:\n";
        foreach ($lastLines as $line) {
            echo trim($line) . "\n";
        }
    } else {
        echo "Laravel log: NOT FOUND\n";
    }
    
} else {
    echo "❌ Tenant not found\n";
}

echo "\n=== RECOMMENDATIONS ===\n";
echo "1. Check if Foundation model is accessible in tenant context during registration\n";
echo "2. Verify storage configuration for tenant vs main storage\n";
echo "3. Test actual registration form with browser developer tools\n";
echo "4. Check Laravel logs for specific errors during upload\n";
echo "5. Verify middleware is not interfering with file uploads\n";
echo "6. Check if database transactions are being committed properly\n";
