<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CREATE SIMPLE TEST PAGE ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "✓ Tenant found: " . $tenant->id . "\n";
    
    $tenant->run(function() {
        // Get foundation info
        $foundationName = 'Portal Yayasan';
        $foundationLogo = '';
        
        if (class_exists('App\Models\Foundation')) {
            $foundation = \App\Models\Foundation::find(1);
            if ($foundation) {
                $foundationName = $foundation->name;
                if ($foundation->logo_path) {
                    $foundationLogo = '/storage/' . $foundation->logo_path;
                }
            }
        }
        
        echo "Foundation: " . $foundationName . "\n";
        echo "Logo URL: " . $foundationLogo . "\n";
        
        // Create a simple test page with the logo
        $html = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logo Test - ' . $foundationName . '</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-8 text-center">Logo Test - ' . $foundationName . '</h1>
        
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md mx-auto">
            <h2 class="text-xl font-semibold mb-4">Foundation Logo</h2>
            
            <div class="flex items-center space-x-4 mb-6">
                ' . ($foundationLogo ? '
                <img src="' . $foundationLogo . '" 
                     alt="' . $foundationName . ' Logo" 
                     class="w-12 h-12 rounded-lg object-cover shadow-lg"
                     onerror="this.style.border=\'2px solid red\'; this.alt=\'Logo Failed to Load\';" 
                     onload="this.style.border=\'2px solid green\';" />
                ' : '
                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                ') . '
                
                <div>
                    <h3 class="font-bold text-lg">' . $foundationName . '</h3>
                    <p class="text-sm text-gray-600">Tenant: ' . tenant('id') . '</p>
                </div>
            </div>
            
            <div class="border-t pt-4">
                <h3 class="font-semibold mb-2">Debug Info:</h3>
                <ul class="text-sm space-y-1">
                    <li><strong>Foundation Name:</strong> ' . $foundationName . '</li>
                    <li><strong>Logo Path:</strong> ' . ($foundationLogo ?: 'None') . '</li>
                    <li><strong>Full URL:</strong> ' . ($foundationLogo ? 'http://yayasan-kemala-bhayangkari.localhost:8000' . $foundationLogo : 'N/A') . '</li>
                    <li><strong>File Exists:</strong> ' . ($foundationLogo && file_exists(public_path('storage/' . substr($foundationLogo, 9))) ? 'YES' : 'NO') . '</li>
                </ul>
            </div>
            
            ' . ($foundationLogo ? '
            <div class="border-t pt-4">
                <h3 class="font-semibold mb-2">Direct Image Test:</h3>
                <img src="' . $foundationLogo . '" 
                     alt="Direct Logo Test" 
                     class="w-full max-w-xs mx-auto rounded border"
                     onerror="this.style.border=\'2px solid red\'; this.alt=\'Direct Load Failed\';" 
                     onload="this.style.border=\'2px solid green\';" />
                <p class="text-xs text-center mt-2">Direct image URL: ' . $foundationLogo . '</p>
            </div>
            ' : '') . '
        </div>
        
        <div class="mt-8 text-center">
            <a href="/dashboard" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg inline-block">
                Go to Dashboard
            </a>
        </div>
    </div>
</body>
</html>';
        
        // Save the test page
        file_put_contents(public_path('logo-simple-test.html'), $html);
        echo "✓ Simple test page created\n";
        echo "URL: http://yayasan-kemala-bhayangkari.localhost:8000/logo-simple-test.html\n";
        
        // Also create a minimal dashboard test
        $dashboardHtml = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ' . $foundationName . '</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex h-screen">
        <!-- Sidebar with Logo -->
        <aside class="w-64 bg-gray-800 text-white">
            <div class="flex items-center gap-3 px-6 h-16 border-b border-gray-700">
                ' . ($foundationLogo ? '
                <img src="' . $foundationLogo . '" 
                     alt="' . $foundationName . ' Logo" 
                     class="w-9 h-9 rounded-lg object-cover shadow-lg"
                     onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">
                <div class="w-9 h-9 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg" style="display: none;">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                ' : '
                <div class="w-9 h-9 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                ') . '
                <div>
                    <span class="text-lg font-bold">' . $foundationName . '</span>
                    <span class="block text-xs text-gray-400">' . tenant('id') . '</span>
                </div>
            </div>
            
            <nav class="p-4">
                <a href="#" class="block py-2 px-4 rounded hover:bg-gray-700">Dashboard</a>
                <a href="#" class="block py-2 px-4 rounded hover:bg-gray-700">Keuangan</a>
                <a href="#" class="block py-2 px-4 rounded hover:bg-gray-700">Data Sekolah</a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 p-8">
            <h1 class="text-3xl font-bold mb-6">Dashboard</h1>
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Selamat Datang!</h2>
                <p>Ini adalah dashboard untuk <strong>' . $foundationName . '</strong>.</p>
                
                ' . ($foundationLogo ? '
                <div class="mt-6">
                    <h3 class="font-semibold mb-2">Logo Test:</h3>
                    <img src="' . $foundationLogo . '" alt="Foundation Logo" class="w-16 h-16 rounded-lg shadow-md" />
                </div>
                ' : '') . '
            </div>
        </main>
    </div>
</body>
</html>';
        
        file_put_contents(public_path('dashboard-test.html'), $dashboardHtml);
        echo "✓ Dashboard test page created\n";
        echo "URL: http://yayasan-kemala-bhayangkari.localhost:8000/dashboard-test.html\n";
    });
    
} else {
    echo "❌ Tenant not found\n";
}

echo "\n=== TESTING INSTRUCTIONS ===\n";
echo "1. Visit: http://yayasan-kemala-bhayangkari.localhost:8000/logo-simple-test.html\n";
echo "2. Visit: http://yayasan-kemala-bhayangkari.localhost:8000/dashboard-test.html\n";
echo "3. If logo appears in these test pages, the issue is with the main dashboard\n";
echo "4. If logo doesn\'t appear, check browser developer tools for 404 errors\n";
