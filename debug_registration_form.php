<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG REGISTRATION FORM ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "✓ Tenant found: " . $tenant->id . "\n";
    
    echo "\n=== 1. CHECK MIDDLEWARE ===\n";
    
    // Check if there are any middleware that might interfere
    $middlewareGroups = config('middleware');
    echo "Middleware groups:\n";
    foreach ($middlewareGroups as $group => $middlewares) {
        echo "- $group: " . implode(', ', $middlewares) . "\n";
    }
    
    echo "\n=== 2. CHECK SESSION CONFIGURATION ===\n";
    
    echo "Session driver: " . config('session.driver') . "\n";
    echo "Session path: " . config('session.files') . "\n";
    echo "Session lifetime: " . config('session.lifetime') . "\n";
    
    echo "\n=== 3. CREATE TEST REGISTRATION ===\n";
    
    // Create a complete test registration
    $tenant->run(function() {
        echo "Running in tenant context...\n";
        
        // Test complete registration flow
        try {
            // Step 1 data
            $step1Data = [
                'foundation_name' => 'Test Foundation ' . time(),
                'institution_type' => 'Yayasan',
                'education_levels' => ['SD', 'SMP'],
                'student_count' => 100,
                'address' => 'Test Address',
                'province' => '31', // DKI Jakarta
                'regency' => '3171', // Jakarta Selatan
                'phone' => '08123456789',
                'email' => 'test' . time() . '@example.com',
                'website' => 'https://example.com',
            ];
            
            echo "✓ Step 1 data prepared\n";
            
            // Step 2: Create test logo file
            $testLogoContent = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
            $tempLogoPath = storage_path('app/public/temp/test-logo-' . time() . '.png');
            file_put_contents($tempLogoPath, $testLogoContent);
            
            $step2Data = [
                'sk_pendirian' => $tempLogoPath,
                'npsn_document' => null,
                'logo' => 'temp/test-logo-' . time() . '.png',
                'building_photo' => $tempLogoPath,
                'ktp' => $tempLogoPath,
            ];
            
            echo "✓ Step 2 data prepared\n";
            
            // Step 3: Plan data
            $step3Data = [
                'plan_id' => 1, // Assuming plan ID 1 exists
                'additional_plugins' => [],
            ];
            
            echo "✓ Step 3 data prepared\n";
            
            // Step 4: Admin data
            $step4Data = [
                'admin_name' => 'Test Admin',
                'admin_email' => 'admin' . time() . '@test.com',
                'admin_phone' => '08123456789',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ];
            
            echo "✓ Step 4 data prepared\n";
            
            echo "\n=== 4. TEST COMPLETE REGISTRATION ===\n";
            
            // Create user
            $user = \App\Models\User::create([
                'name' => $step4Data['admin_name'],
                'email' => $step4Data['admin_email'],
                'password' => \Hash::make($step4Data['password']),
                'tenant_id' => null,
                'role' => 'foundation_admin',
                'is_active' => true,
            ]);
            
            echo "✓ User created: " . $user->id . "\n";
            
            // Create foundation
            $foundation = \App\Models\Foundation::create([
                'tenant_id' => null,
                'name' => $step1Data['foundation_name'],
                'email' => $step1Data['email'],
                'phone' => $step1Data['phone'],
                'address' => $step1Data['address'],
                'province' => $step1Data['province'],
                'regency' => $step1Data['regency'],
                'npsn' => null,
                'institution_type' => $step1Data['institution_type'],
                'education_levels' => json_encode($step1Data['education_levels']),
                'student_count' => $step1Data['student_count'],
                'website' => $step1Data['website'],
                'subdomain' => strtolower(str_replace(' ', '-', $step1Data['foundation_name'])),
                'status' => 'pending',
                'plan_id' => $step3Data['plan_id'],
                'admin_user_id' => $user->id,
                'trial_ends_at' => now()->addDays(14),
            ]);
            
            echo "✓ Foundation created: " . $foundation->id . "\n";
            
            // Process document uploads
            $uploadPath = 'uploads/foundations/' . $foundation->id;
            $fullUploadPath = storage_path('app/public/' . $uploadPath);
            
            if (!is_dir($fullUploadPath)) {
                mkdir($fullUploadPath, 0755, true);
            }
            
            // Process logo
            if (isset($step2Data['logo']) && Storage::disk('public')->exists($step2Data['logo'])) {
                $fileName = 'logo.' . pathinfo($step2Data['logo'], PATHINFO_EXTENSION);
                $finalPath = $uploadPath . '/' . $fileName;
                
                echo "Processing logo: " . $step2Data['logo'] . " -> " . $finalPath . "\n";
                
                $moveResult = Storage::disk('public')->move($step2Data['logo'], $finalPath);
                echo "Move result: " . ($moveResult ? 'SUCCESS' : 'FAILED') . "\n";
                
                if ($moveResult) {
                    $foundation->update(['logo_path' => $finalPath]);
                    echo "✓ Logo path updated: " . $finalPath . "\n";
                }
            }
            
            // Copy to public storage
            if ($foundation->logo_path) {
                $sourceFile = storage_path('app/public/' . $foundation->logo_path);
                $publicFile = public_path('storage/' . $foundation->logo_path);
                $publicDir = dirname($publicFile);
                
                if (!is_dir($publicDir)) {
                    mkdir($publicDir, 0755, true);
                }
                
                $copyResult = copy($sourceFile, $publicFile);
                echo "Public copy result: " . ($copyResult ? 'SUCCESS' : 'FAILED') . "\n";
                
                if ($copyResult) {
                    echo "✓ Logo copied to public storage\n";
                    
                    // Test HTTP access
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
                    
                    echo "✅ FINAL LOGO URL: $url\n";
                    echo "✅ FULL URL: $testUrl\n";
                }
            }
            
        } catch (Exception $e) {
            echo "❌ Registration error: " . $e->getMessage() . "\n";
            echo "Stack trace: " . $e->getTraceAsString() . "\n";
        }
    });
    
} else {
    echo "❌ Tenant not found\n";
}

echo "\n=== NEXT STEPS ===\n";
echo "1. Check the test registration above\n";
echo "2. Try manual registration via form\n";
echo "3. Check browser developer tools for errors\n";
echo "4. Check Laravel logs for detailed errors\n";
