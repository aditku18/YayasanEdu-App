<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'aditku02@gmail.Com';
$tenantId = '7d1f6bb9-2c9f-4074-9247-5af14829a5a1';

try {
    $t = App\Models\Tenant::find($tenantId);
    tenancy()->initialize($t);
    $user = App\Models\User::where('email', $email)->first();
    if ($user) {
        echo "Email: {$user->email}\n";
        echo "Verified At: " . ($user->email_verified_at ?? 'NULL') . "\n";
    } else {
        echo "User not found in tenant {$tenantId}\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
