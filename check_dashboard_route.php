<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHECK DASHBOARD ROUTE AND VIEW ===\n";

use App\Models\Tenant;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "✓ Tenant found: " . $tenant->id . "\n";
    
    echo "\n=== CHECKING ROUTES ===\n";
    
    // Check available routes
    try {
        $routeCollection = \Route::getRoutes();
        $dashboardRoutes = [];
        
        foreach ($routeCollection as $route) {
            if (strpos($route->getName(), 'dashboard') !== false) {
                $dashboardRoutes[] = [
                    'name' => $route->getName(),
                    'uri' => $route->uri(),
                    'methods' => implode(', ', $route->methods())
                ];
            }
        }
        
        if (!empty($dashboardRoutes)) {
            echo "Found dashboard routes:\n";
            foreach ($dashboardRoutes as $route) {
                echo "  - {$route['name']}: {$route['uri']} [{$route['methods']}]\n";
            }
        } else {
            echo "❌ No dashboard routes found\n";
        }
    } catch (Exception $e) {
        echo "Error checking routes: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== CHECKING VIEW INHERITANCE ===\n";
    
    // Check what view is actually being used for dashboard
    $viewPaths = [
        'dashboard',
        'tenant.dashboard',
        'layouts.tenant',
        'layouts.app'
    ];
    
    foreach ($viewPaths as $view) {
        try {
            if (view()->exists($view)) {
                echo "✓ View exists: $view\n";
                
                // Get view content
                $viewContent = view($view)->render();
                
                // Check if it extends tenant layout
                if (strpos($viewContent, 'layouts.tenant') !== false) {
                    echo "  ✅ Extends layouts.tenant\n";
                }
                
                // Check if it has logo references
                if (strpos($viewContent, 'logo') !== false) {
                    echo "  ✅ Contains logo references\n";
                }
                
            } else {
                echo "❌ View not found: $view\n";
            }
        } catch (Exception $e) {
            echo "Error checking view $view: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== TESTING DASHBOARD RENDERING ===\n";
    
    // Try to simulate dashboard rendering
    $tenant->run(function() {
        try {
            echo "Running in tenant context...\n";
            
            // Check if Foundation model is accessible
            if (class_exists('App\Models\Foundation')) {
                $foundation = \App\Models\Foundation::find(1);
                if ($foundation) {
                    echo "✓ Foundation accessible in tenant context\n";
                    echo "  Foundation name: " . $foundation->name . "\n";
                    echo "  Logo path: " . ($foundation->logo_path ?? 'NULL') . "\n";
                    
                    if ($foundation->logo_path) {
                        $url = \Storage::url($foundation->logo_path);
                        echo "  Generated URL: $url\n";
                    }
                } else {
                    echo "❌ Foundation not found in tenant context\n";
                }
            } else {
                echo "❌ Foundation model not available in tenant context\n";
            }
            
            // Try to render the tenant layout
            if (view()->exists('layouts.tenant')) {
                echo "✓ Tenant layout view exists\n";
                
                try {
                    $rendered = view('layouts.tenant')->render();
                    echo "✅ Tenant layout renders successfully\n";
                    
                    if (strpos($rendered, 'Foundation') !== false) {
                        echo "✅ Rendered layout contains Foundation references\n";
                    } else {
                        echo "❌ Rendered layout does NOT contain Foundation references\n";
                    }
                    
                    if (strpos($rendered, 'logo') !== false) {
                        echo "✅ Rendered layout contains logo references\n";
                    } else {
                        echo "❌ Rendered layout does NOT contain logo references\n";
                    }
                    
                } catch (Exception $e) {
                    echo "❌ Error rendering tenant layout: " . $e->getMessage() . "\n";
                }
            }
            
        } catch (Exception $e) {
            echo "Error in tenant context: " . $e->getMessage() . "\n";
        }
    });
    
    echo "\n=== CHECKING ACTUAL DASHBOARD VIEW ===\n";
    
    // Look for dashboard view files
    $dashboardViewPaths = [
        resource_path('views/dashboard.blade.php'),
        resource_path('views/tenant/dashboard.blade.php'),
        resource_path('views/school/dashboard.blade.php'),
    ];
    
    foreach ($dashboardViewPaths as $viewPath) {
        if (file_exists($viewPath)) {
            echo "✓ Found dashboard view: " . basename($viewPath) . "\n";
            
            $content = file_get_contents($viewPath);
            
            // Check what layout it extends
            if (preg_match('/@extends\([\'"]([^\'"]+)[\'"]\)/', $content, $matches)) {
                echo "  Extends: " . $matches[1] . "\n";
            }
            
            // Check for logo references
            if (strpos($content, 'logo') !== false) {
                echo "  ✅ Contains logo references\n";
            }
        }
    }
    
} else {
    echo "❌ Tenant not found\n";
}

echo "\n=== NEXT STEPS ===\n";
echo "1. Identify which view is actually used for dashboard\n";
echo "2. Ensure that view extends layouts.tenant\n";
echo "3. Check if Foundation model is accessible in tenant context\n";
echo "4. Verify the layout is being rendered correctly\n";
