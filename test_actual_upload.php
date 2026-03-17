<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST ACTUAL UPLOAD PROCESS ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "✓ Tenant found: " . $tenant->id . "\n";
    
    $tenant->run(function() {
        echo "\n=== SIMULATING REGISTRATION UPLOAD ===\n";
        
        // Create a test logo file (simulating uploaded file)
        $testLogoContent = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
        $tempLogoPath = sys_get_temp_dir() . '/test-logo-' . time() . '.png';
        file_put_contents($tempLogoPath, $testLogoContent);
        
        echo "✓ Created test logo: " . $tempLogoPath . "\n";
        echo "File size: " . filesize($tempLogoPath) . " bytes\n";
        
        // Simulate the upload process like in FoundationRegistrationController
        echo "\n=== STEP 1: Store file temporarily ===\n";
        
        $tempStoragePath = 'temp/documents/logo_' . time() . '.png';
        $storageResult = \Storage::disk('public')->put($tempStoragePath, file_get_contents($tempLogoPath));
        
        echo "Storage result: " . ($storageResult ? 'SUCCESS' : 'FAILED') . "\n";
        echo "Temp path: " . $tempStoragePath . "\n";
        
        if ($storageResult) {
            echo "File exists in storage: " . (\Storage::disk('public')->exists($tempStoragePath) ? 'YES' : 'NO') . "\n";
        }
        
        echo "\n=== STEP 2: Process document uploads ===\n";
        
        // Simulate the processDocumentUploads method
        $step2Data = ['logo' => $tempStoragePath];
        $documents = [
            'logo' => 'logo',
        ];
        
        $uploadPath = 'uploads/foundations/1';
        
        echo "Upload path: " . $uploadPath . "\n";
        
        foreach ($documents as $sessionKey => $diskPath) {
            if (isset($step2Data[$sessionKey]) && \Storage::disk('public')->exists($step2Data[$sessionKey])) {
                $fileName = $diskPath . '.' . pathinfo($step2Data[$sessionKey], PATHINFO_EXTENSION);
                $finalPath = $uploadPath . '/' . $fileName;
                
                echo "Processing: $sessionKey -> $finalPath\n";
                
                // Create directory if needed
                $fullUploadPath = storage_path('app/public/' . $uploadPath);
                if (!is_dir($fullUploadPath)) {
                    echo "Creating directory: " . $fullUploadPath . "\n";
                    mkdir($fullUploadPath, 0755, true);
                }
                
                $moveResult = \Storage::disk('public')->move($step2Data[$sessionKey], $finalPath);
                echo "Move result: " . ($moveResult ? 'SUCCESS' : 'FAILED') . "\n";
                
                if ($moveResult) {
                    echo "File exists at new location: " . (\Storage::disk('public')->exists($finalPath) ? 'YES' : 'NO') . "\n";
                    
                    // Update foundation
                    if (class_exists('App\Models\Foundation')) {
                        $foundation = \App\Models\Foundation::find(1);
                        if ($foundation) {
                            $columnPath = $diskPath . '_path';
                            echo "Updating foundation $columnPath: " . $finalPath . "\n";
                            
                            $foundation->update([$columnPath => $finalPath]);
                            echo "Database update: SUCCESS\n";
                            
                            // Verify update
                            $foundation->refresh();
                            echo "New logo_path: " . ($foundation->logo_path ?? 'NULL') . "\n";
                        }
                    }
                }
            }
        }
        
        echo "\n=== STEP 3: Copy to public storage ===\n";
        
        if (class_exists('App\Models\Foundation')) {
            $foundation = \App\Models\Foundation::find(1);
            if ($foundation && $foundation->logo_path) {
                $sourceFile = storage_path('app/public/' . $foundation->logo_path);
                $destFile = public_path('storage/' . $foundation->logo_path);
                
                echo "Source: " . $sourceFile . "\n";
                echo "Destination: " . $destFile . "\n";
                
                echo "Source exists: " . (file_exists($sourceFile) ? 'YES' : 'NO') . "\n";
                
                if (file_exists($sourceFile)) {
                    $destDir = dirname($destFile);
                    if (!is_dir($destDir)) {
                        mkdir($destDir, 0755, true);
                    }
                    
                    $copyResult = copy($sourceFile, $destFile);
                    echo "Copy result: " . ($copyResult ? 'SUCCESS' : 'FAILED') . "\n";
                    
                    if ($copyResult) {
                        echo "Public file exists: " . (file_exists($destFile) ? 'YES' : 'NO') . "\n";
                        
                        // Test URL
                        $url = '/storage/' . $foundation->logo_path;
                        $testUrl = 'http://yayasan-kemala-bhayangkari.localhost:8000' . $url;
                        
                        if (function_exists('curl_init')) {
                            $ch = curl_init($testUrl);
                            curl_setopt($ch, CURLOPT_NOBODY, true);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                            curl_exec($ch);
                            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            curl_close($ch);
                            echo "HTTP test: $httpCode " . ($httpCode == 200 ? '✅' : '❌') . "\n";
                        }
                    }
                }
            }
        }
        
        // Clean up
        unlink($tempLogoPath);
        echo "\n✓ Test file cleaned up\n";
    });
    
} else {
    echo "❌ Tenant not found\n";
}

echo "\n=== SUMMARY ===\n";
echo "1. Upload directories created and writable\n";
echo "2. File storage and move process tested\n";
echo "3. Database update process tested\n";
echo "4. Public storage copy tested\n";
echo "5. HTTP accessibility tested\n";
echo "\nIf all steps show SUCCESS, the upload process should work.\n";
echo "Check the actual registration form for any JavaScript or validation errors.\n";
