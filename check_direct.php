<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

$tenantId = 'd0a4efae-59de-4d2d-9232-a56ad4b682a1';
$tenant = Tenant::find($tenantId);

if (!$tenant) {
    die("Tenant not found\n");
}

tenancy()->initialize($tenant);

use App\Models\User;

$user = User::where('email', 'amin3@gmail.com')->first();

if ($user) {
    echo "Email: " . $user->email . "\n";
    echo "Verified At: " . ($user->email_verified_at ?? 'NULL') . "\n";
    echo "Has Verified Email: " . ($user->hasVerifiedEmail() ? 'YES' : 'NO') . "\n";
} else {
    echo "User not found in tenant DB\n";
}

tenancy()->end();
