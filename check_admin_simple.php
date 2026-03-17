<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

// Check admin user
$user = User::where('email', 'admin@edusaas.com')->first();
if ($user) {
    echo "User found:\n";
    echo "- Name: " . $user->name . "\n";
    echo "- Email: " . $user->email . "\n";
    echo "- Tenant ID: " . ($user->tenant_id ?? 'null') . "\n";
    echo "- Email Verified: " . ($user->email_verified_at ? 'Yes' : 'No') . "\n";
    echo "- Role column: " . ($user->role ?? 'null') . "\n";
    echo "- Is Active: " . ($user->is_active ?? 'null') . "\n";
    
    // Check if this is a platform admin by checking if tenant_id is null
    echo "\nPlatform Admin Check:\n";
    if ($user->tenant_id === null) {
        echo "✅ User is a platform user (tenant_id is null)\n";
    } else {
        echo "❌ User is a tenant user (tenant_id: " . $user->tenant_id . ")\n";
    }
    
    // Check if role column has correct value
    echo "\nRole Check:\n";
    if ($user->role === 'platform_admin') {
        echo "✅ User has platform_admin role\n";
    } elseif ($user->role === null) {
        echo "❌ User role is null - this is the problem!\n";
    } else {
        echo "❌ User has unexpected role: " . $user->role . "\n";
    }
    
} else {
    echo "User admin@edusaas.com not found\n";
}
