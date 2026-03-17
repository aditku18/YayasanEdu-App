<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Foundation;
use App\Models\Tenant;

// Get user
$user = User::where('email', 'aditku17@gmail.Com')->first();
if (!$user) {
    echo "User not found\n";
    exit;
}

echo "Current user status:\n";
echo "- Name: " . $user->name . "\n";
echo "- Email: " . $user->email . "\n";
echo "- Tenant ID: " . ($user->tenant_id ?? 'null') . "\n";

// Check if user has tenant
if ($user->tenant_id) {
    $tenant = Tenant::find($user->tenant_id);
    if ($tenant) {
        $domain = $tenant->domains()->first();
        echo "- Tenant exists: " . $tenant->id . "\n";
        echo "- Domain: " . ($domain ? $domain->domain : 'No domain') . "\n";
        
        if ($domain) {
            echo "\n✅ User should be redirected to: http://" . $domain->domain . ":8000/dashboard\n";
            echo "   Current access: http://127.0.0.1:8001/dashboard (central domain)\n";
            echo "\n❌ ISSUE: User accessing central domain instead of tenant domain!\n";
            echo "   Solution: Access via tenant domain: http://" . $domain->domain . ":8000\n";
        }
    } else {
        echo "- Tenant not found\n";
    }
} else {
    echo "- No tenant assigned\n";
}
