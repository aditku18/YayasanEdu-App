<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG LOGO UPLOAD PROCESS ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "✓ Tenant found: " . $tenant->id . "\n";
    
    $tenant->run(function() {
        echo "\n=== CHECKING CURRENT STATE ===\n";
        
        // Check Foundation model
        if (class_exists('App\Models\Foundation')) {
            $foundation = \App\Models\Foundation::find(1);
            if ($foundation) {
                echo "Foundation ID: " . $foundation->id . "\n";
                echo "Foundation Name: " . $foundation->name . "\n";
                echo "Current logo_path: " . ($foundation->logo_path ?? 'NULL') . "\n";
                
                // Check if logo file exists
                if ($foundation->logo_path) {
                    $logoFile = storage_path('app/public/' . $foundation->logo_path);
                    echo "Logo file in storage: " . (file_exists($logoFile) ? 'EXISTS' : 'MISSING') . "\n";
                    if (file_exists($logoFile)) {
                        echo "File size: " . filesize($logoFile) . " bytes\n";
                    }
                }
            } else {
                echo "❌ Foundation not found\n";
            }
        } else {
            echo "❌ Foundation model not found\n";
        }
        
        echo "\n=== TESTING UPLOAD PROCESS ===\n";
        
        // Simulate logo upload process
        echo "1. Creating test logo file...\n";
        
        // Create a test image
        $testImagePath = storage_path('app/public/temp/test-logo-upload.png');
        $testImageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
        file_put_contents($testImagePath, $testImageData);
        
        echo "✓ Test image created: " . $testImagePath . "\n";
        echo "File size: " . filesize($testImagePath) . " bytes\n";
        
        echo "\n2. Testing Foundation update...\n";
        
        try {
            if (class_exists('App\Models\Foundation')) {
                $foundation = \App\Models\Foundation::find(1);
                if ($foundation) {
                    // Move test file to permanent location
                    $permanentPath = 'uploads/foundations/1/test-logo-' . time() . '.png';
                    $permanentFile = storage_path('app/public/' . $permanentPath);
                    
                    // Create directory if needed
                    $permanentDir = dirname($permanentFile);
                    if (!is_dir($permanentDir)) {
                        mkdir($permanentDir, 0755, true);
                        echo "✓ Created directory: " . $permanentDir . "\n";
                    }
                    
                    // Copy file
                    $copyResult = copy($testImagePath, $permanentFile);
                    echo "Copy result: " . ($copyResult ? 'SUCCESS' : 'FAILED') . "\n";
                    
                    if ($copyResult) {
                        echo "✓ File copied to: " . $permanentFile . "\n";
                        
                        // Update foundation record
                        $foundation->logo_path = $permanentPath;
                        $updateResult = $foundation->save();
                        echo "Database update result: " . ($updateResult ? 'SUCCESS' : 'FAILED') . "\n";
                        
                        if ($updateResult) {
                            echo "✓ Foundation logo_path updated to: " . $permanentPath . "\n";
                            
                            // Test URL generation
                            $url = \Storage::url($permanentPath);
                            echo "Generated URL: " . $url . "\n";
                            
                            // Copy to public storage for web access
                            $publicFile = public_path('storage/' . $permanentPath);
                            $publicDir = dirname($publicFile);
                            if (!is_dir($publicDir)) {
                                mkdir($publicDir, 0755, true);
                            }
                            
                            $publicCopyResult = copy($permanentFile, $publicFile);
                            echo "Public copy result: " . ($publicCopyResult ? 'SUCCESS' : 'FAILED') . "\n";
                            
                            if ($publicCopyResult) {
                                echo "✓ File copied to public storage\n";
                                echo "Public file: " . $publicFile . "\n";
                                
                                // Test HTTP access
                                $testUrl = 'http://yayasan-kemala-bhayangkari.localhost:8000' . $url;
                                if (function_exists('curl_init')) {
                                    $ch = curl_init($testUrl);
                                    curl_setopt($ch, CURLOPT_NOBODY, true);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                                    curl_exec($ch);
                                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                    curl_close($ch);
                                    echo "HTTP Test: $httpCode " . ($httpCode == 200 ? '✅' : '❌') . "\n";
                                }
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            echo "❌ Error during upload test: " . $e->getMessage() . "\n";
            echo "Stack trace: " . $e->getTraceAsString() . "\n";
        }
        
        echo "\n=== CHECKING REGISTRATION CONTROLLER ===\n";
        
        // Check the FoundationRegistrationController
        $controllerPath = app_path('Http/Controllers/FoundationRegistrationController.php');
        if (file_exists($controllerPath)) {
            echo "✓ FoundationRegistrationController exists\n";
            
            $controllerContent = file_get_contents($controllerPath);
            
            // Look for logo processing
            if (strpos($controllerContent, 'processDocumentUploads') !== false) {
                echo "✓ Contains processDocumentUploads method\n";
            }
            
            if (strpos($controllerContent, 'logo_path') !== false) {
                echo "✓ Contains logo_path references\n";
            }
            
            if (strpos($controllerContent, 'logo') !== false) {
                echo "✓ Contains logo references\n";
            }
            
            // Extract the processDocumentUploads method
            if (preg_match('/private function processDocumentUploads.*?\{.*?\}/s', $controllerContent, $matches)) {
                echo "\n--- processDocumentUploads method ---\n";
                echo $matches[0] . "\n";
            }
        } else {
            echo "❌ FoundationRegistrationController not found\n";
        }
        
        // Clean up test file
        if (file_exists($testImagePath)) {
            unlink($testImagePath);
            echo "\n✓ Test file cleaned up\n";
        }
    });
    
} else {
    echo "❌ Tenant not found\n";
}

echo "\n=== RECOMMENDATIONS ===\n";
echo "1. Check if FoundationRegistrationController is properly processing logo uploads\n";
echo "2. Verify the processDocumentUploads method is handling logo files correctly\n";
echo "3. Ensure the logo_path field is being updated in the database\n";
echo "4. Check file permissions in storage directories\n";
echo "5. Test the actual upload form to see what errors occur\n";
