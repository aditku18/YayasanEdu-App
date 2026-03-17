<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Foundation;
use App\Services\TenantService;
use App\Models\User;
use Illuminate\Support\Str;

$foundation = Foundation::find(1);
if (!$foundation) {
    echo "Foundation not found!\n";
    exit;
}

echo "Processing foundation: {$foundation->name}\n";
echo "Current status: {$foundation->status}\n";

// Check if email is verified
$user = User::where('email', strtolower($foundation->email))->first();
if (!$user || !$user->hasVerifiedEmail()) {
    echo "Email not verified: {$foundation->email}\n";
    exit;
}

echo "Email verified ✓\n";

// Create tenant
$tenantService = app(TenantService::class);
$expectedTenantId = 'tenant-' . Str::slug($foundation->name);

$existingTenant = \App\Models\Tenant::find($expectedTenantId);
if ($existingTenant) {
    $tenant = $existingTenant;
    $tenantId = $expectedTenantId;
    echo "Using existing tenant: {$tenantId}\n";
} else {
    $tenant = $tenantService->createTenantWithDomain(null, $foundation->subdomain, $foundation->name);
    $tenantId = $tenant ? $tenant->id : null;
    echo "Created new tenant: {$tenantId}\n";
}

// Update foundation
$foundation->update([
    'tenant_id' => $tenantId,
    'status' => 'trial',
    'trial_ends_at' => now()->addDays(14),
    'plan_id' => 1,
]);

echo "Foundation updated to trial status ✓\n";

// Update user
$user->update([
    'tenant_id' => $tenantId,
    'role' => 'foundation_admin'
]);

echo "User updated with tenant_id ✓\n";

// Create user in tenant database
$tenant = \App\Models\Tenant::find($tenantId);
if ($tenant) {
    tenancy()->initialize($tenant);
    
    $tenantUser = User::where('email', strtolower($foundation->email))->first();
    if (!$tenantUser) {
        $userData = $user->toArray();
        unset($userData['tenant_id'], $userData['id']);
        $userData['password'] = $user->password;
        $userData['email_verified_at'] = $user->email_verified_at;
        
        $tenantUser = User::create($userData);
        
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'foundation_admin', 'guard_name' => 'web']);
        $tenantUser->assignRole($role);
        
        echo "User created in tenant database ✓\n";
    }
    
    tenancy()->end();
}

echo "✓ Foundation {$foundation->name} has been approved and tenant database created!\n";
echo "Tenant ID: {$tenantId}\n";
echo "Subdomain: {$foundation->subdomain}\n";
