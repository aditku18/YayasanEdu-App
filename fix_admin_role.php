<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

// Find and fix admin user
$user = User::where('email', 'admin@edusaas.com')->first();
if ($user) {
    echo "Found admin user:\n";
    echo "- Name: " . $user->name . "\n";
    echo "- Current role: " . ($user->role ?? 'null') . "\n";
    
    // Update the role to platform_admin
    $user->role = 'platform_admin';
    $user->save();
    
    echo "\n✅ Updated user role to: platform_admin\n";
    
    // Verify the update
    $updatedUser = User::where('email', 'admin@edusaas.com')->first();
    echo "- New role: " . ($updatedUser->role ?? 'null') . "\n";
    
} else {
    echo "❌ User admin@edusaas.com not found\n";
}
