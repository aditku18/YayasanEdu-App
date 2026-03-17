<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Tenant;
use App\Models\Foundation;

echo "--- SYNCING ALL FOUNDATION ADMINS ---\n";
$foundations = Foundation::whereNotNull('tenant_id')->get();

foreach ($foundations as $f) {
    echo "Processing Foundation: {$f->name} ({$f->email})\n";
    $centralUser = User::where('email', $f->email)->first();
    
    if (!$centralUser) {
        echo "  Central user not found.\n";
        continue;
    }
    
    if (!$centralUser->email_verified_at) {
        echo "  Central user NOT VERIFIED yet.\n";
        continue;
    }

    $tenant = Tenant::find($f->tenant_id);
    if (!$tenant) {
        echo "  Tenant record not found in central DB.\n";
        continue;
    }

    try {
        tenancy()->initialize($tenant);
        
        $tenantUser = User::where('email', $f->email)->first();
        if ($tenantUser) {
            if (!$tenantUser->email_verified_at) {
                echo "  Fixing tenant user verification...\n";
                $tenantUser->forceFill(['email_verified_at' => $centralUser->email_verified_at])->save();
                echo "  Status: SYNCED\n";
            } else {
                echo "  Status: Already Verified\n";
            }
        } else {
            echo "  User NOT FOUND in tenant DB.\n";
        }
        
        tenancy()->end();
    } catch (\Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
    echo "--------------------\n";
}

