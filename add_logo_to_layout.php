<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ADD LOGO TO TENANT LAYOUT ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "✓ Tenant found: " . $tenant->id . "\n";
    
    $tenant->run(function() {
        // Get foundation logo info
        $foundationLogoUrl = '';
        $foundationName = '';
        
        if (class_exists('App\Models\Foundation')) {
            $foundation = \App\Models\Foundation::find(1); // Get the main foundation
            if ($foundation && $foundation->logo_path) {
                $foundationLogoUrl = '/storage/' . $foundation->logo_path;
                $foundationName = $foundation->name;
                echo "✓ Foundation logo found: " . $foundationLogoUrl . "\n";
            }
        }
        
        // Read the current tenant layout
        $layoutPath = resource_path('views/layouts/tenant.blade.php');
        if (file_exists($layoutPath)) {
            echo "✓ Reading current layout file\n";
            $layoutContent = file_get_contents($layoutPath);
            
            // Find the logo section and replace it
            $oldLogoSection = '                {{-- Logo & Foundation Info --}}
                <div class="flex items-center gap-3 px-6 h-16 border-b border-white/10 flex-shrink-0">
                    <div class="w-9 h-9 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg font-bold text-white tracking-tight">Portal Yayasan</span>
                        <span class="block text-[10px] text-slate-500 uppercase tracking-widest font-medium truncate max-w-[150px]">{{ tenant(\'id\') ?? \'Tenant\' }}</span>
                    </div>
                </div>';
            
            $newLogoSection = '                {{-- Logo & Foundation Info --}}
                <div class="flex items-center gap-3 px-6 h-16 border-b border-white/10 flex-shrink-0">
                    @if(function_exists(\'App\\\Models\\\Foundation\') && \App\Models\Foundation::find(1) && \App\Models\Foundation::find(1)->logo_path)
                        <img src="{{ Storage::url(\App\Models\Foundation::find(1)->logo_path) }}" 
                             alt="{{ \App\Models\Foundation::find(1)->name ?? \'Logo\' }}" 
                             class="w-9 h-9 rounded-lg object-cover shadow-lg shadow-blue-500/20"
                             onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">
                        <div class="w-9 h-9 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/20" style="display: none;">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    @else
                        <div class="w-9 h-9 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    @endif
                    <div>
                        @if(function_exists(\'App\\\Models\\\Foundation\') && \App\Models\Foundation::find(1))
                            <span class="text-lg font-bold text-white tracking-tight">{{ \App\Models\Foundation::find(1)->name ?? \'Portal Yayasan\' }}</span>
                        @else
                            <span class="text-lg font-bold text-white tracking-tight">Portal Yayasan</span>
                        @endif
                        <span class="block text-[10px] text-slate-500 uppercase tracking-widest font-medium truncate max-w-[150px]">{{ tenant(\'id\') ?? \'Tenant\' }}</span>
                    </div>
                </div>';
            
            if (strpos($layoutContent, $oldLogoSection) !== false) {
                echo "✓ Found old logo section\n";
                
                // Replace the logo section
                $newLayoutContent = str_replace($oldLogoSection, $newLogoSection, $layoutContent);
                
                // Backup the original file
                $backupPath = resource_path('views/layouts/tenant.blade.php.backup');
                copy($layoutPath, $backupPath);
                echo "✓ Backup created: tenant.blade.php.backup\n";
                
                // Write the new layout
                file_put_contents($layoutPath, $newLayoutContent);
                echo "✓ Layout updated with logo support\n";
                
                // Clear view cache
                try {
                    \Artisan::call('view:clear');
                    echo "✓ View cache cleared\n";
                } catch (Exception $e) {
                    echo "⚠ Could not clear view cache: " . $e->getMessage() . "\n";
                }
                
            } else {
                echo "❌ Could not find the exact logo section to replace\n";
                echo "You may need to manually update the layout file\n";
            }
            
        } else {
            echo "❌ Layout file not found\n";
        }
    });
    
} else {
    echo "❌ Tenant not found\n";
}

echo "\n=== TESTING ===\n";
echo "Visit: http://yayasan-kemala-bhayangkari.localhost:8000/dashboard\n";
echo "The logo should now appear in the sidebar\n";
echo "If not, check browser console for errors\n";
