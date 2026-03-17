<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Foundation;
use App\Models\User;

$foundation = Foundation::find(1);
echo "Foundation ID: {$foundation->id}\n";
echo "Foundation name: {$foundation->name}\n";
echo "Foundation email: {$foundation->email}\n";
echo "Admin user ID: {$foundation->admin_user_id}\n\n";

if ($foundation->admin_user_id) {
    $admin = User::find($foundation->admin_user_id);
    if ($admin) {
        echo "Admin user found:\n";
        echo "ID: {$admin->id}\n";
        echo "Name: {$admin->name}\n";
        echo "Email: {$admin->email}\n";
        echo "Verified: " . ($admin->email_verified_at ? $admin->email_verified_at->format('Y-m-d H:i:s') : 'NOT VERIFIED') . "\n";
        echo "Tenant ID: " . ($admin->tenant_id ?? 'null') . "\n";
        echo "Role: {$admin->role}\n";
    } else {
        echo "Admin user not found with ID: {$foundation->admin_user_id}\n";
    }
} else {
    echo "No admin user ID set for foundation\n";
}
