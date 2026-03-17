<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FIX PLATFORM LAYOUT LOGO ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "✓ Tenant found: " . $tenant->id . "\n";
    
    echo "\n=== CHECKING PLATFORM LAYOUT ===\n";
    
    $platformLayoutPath = resource_path('views/components/platform-layout.blade.php');
    if (file_exists($platformLayoutPath)) {
        echo "✓ Platform layout found\n";
        
        $content = file_get_contents($platformLayoutPath);
        
        // Look for sidebar section
        if (preg_match('/<!-- Elegant Sidebar -->(.*?)<!-- Main Content -->/s', $content, $sidebarMatch)) {
            echo "✓ Found sidebar section\n";
            
            // Check if logo code exists
            if (strpos($sidebarMatch[0], 'Foundation') !== false) {
                echo "✓ Sidebar already contains Foundation references\n";
            } else {
                echo "❌ Sidebar missing Foundation logo code\n";
                
                // Create new sidebar with logo
                $newSidebar = '<!-- Elegant Sidebar -->
        <aside :class="sidebarOpen ? \'translate-x-0\' : \'-translate-x-full\'" class="fixed inset-y-0 left-0 z-50 w-72 bg-sidebar transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col">
            
            {{-- Logo & Foundation Info --}}
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
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <p class="px-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-3">Menu Utama</p>

                <a href="{{ route(\'tenant.dashboard\') }}" class="sidebar-link {{ request()->routeIs(\'tenant.dashboard\') ? \'active\' : \'\' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route(\'tenant.school.finance.index\') }}" class="sidebar-link {{ request()->routeIs(\'tenant.school.finance.*\') ? \'active\' : \'\' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Keuangan
                </a>

                <a href="#" class="sidebar-link">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Data Sekolah
                </a>

                <a href="#" class="sidebar-link">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Data Siswa
                </a>
            </nav>

            {{-- User Section --}}
            <div class="border-t border-white/10 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center">
                        <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-400">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>';
                
                // Replace the sidebar section
                $newContent = str_replace($sidebarMatch[0], $newSidebar, $content);
                
                // Backup original
                $backupPath = resource_path('views/components/platform-layout.blade.php.backup');
                copy($platformLayoutPath, $backupPath);
                echo "✓ Backup created\n";
                
                // Write new content
                file_put_contents($platformLayoutPath, $newContent);
                echo "✓ Platform layout updated with logo\n";
                
                // Clear view cache
                try {
                    \Artisan::call('view:clear');
                    echo "✓ View cache cleared\n";
                } catch (Exception $e) {
                    echo "⚠ Could not clear view cache\n";
                }
            }
        } else {
            echo "❌ Could not find sidebar section\n";
        }
        
    } else {
        echo "❌ Platform layout not found\n";
    }
    
    echo "\n=== TESTING ===\n";
    echo "Visit: http://yayasan-kemala-bhayangkari.localhost:8000/dashboard\n";
    echo "The logo should now appear in the platform layout sidebar\n";
    
} else {
    echo "❌ Tenant not found\n";
}
