<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Foundation;
use App\Models\Tenant;

// Check user status
$user = User::where('email', 'admin@edusaas.com')->first();
if ($user) {
    echo "User found:\n";
    echo "- Name: " . $user->name . "\n";
    echo "- Email: " . $user->email . "\n";
    echo "- Tenant ID: " . ($user->tenant_id ?? 'null') . "\n";
    echo "- Email Verified: " . ($user->email_verified_at ? 'Yes' : 'No') . "\n";
    echo "- Has Role platform_admin: " . ($user->hasRole('platform_admin') ? 'Yes' : 'No') . "\n";
} else {
    echo "User not found\n";
}

echo "\n";

// Check foundation status
$foundation = Foundation::where('subdomain', 'pelita-hati2.localhost')->first();
if ($foundation) {
    echo "Foundation found:\n";
    echo "- Name: " . $foundation->name . "\n";
    echo "- Status: " . $foundation->status . "\n";
    echo "- Tenant ID: " . ($foundation->tenant_id ?? 'null') . "\n";
    echo "- Trial ends at: " . ($foundation->trial_ends_at ?? 'null') . "\n";
    echo "- Plan ID: " . ($foundation->plan_id ?? 'null') . "\n";
} else {
    echo "Foundation not found\n";
}

echo "\n";

// Check if user tenant matches foundation tenant
if ($user && $foundation) {
    if ($user->tenant_id === $foundation->tenant_id) {
        echo "✅ User tenant ID matches foundation tenant ID\n";
    } else {
        echo "❌ User tenant ID does NOT match foundation tenant ID\n";
        echo "   User tenant: " . ($user->tenant_id ?? 'null') . "\n";
        echo "   Foundation tenant: " . ($foundation->tenant_id ?? 'null') . "\n";
    }
}
