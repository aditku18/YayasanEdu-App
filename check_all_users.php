<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$users = User::where('email', 'kbhyd@gmail.com')
    ->orWhere('email', 'like', '%bhayangkari%')
    ->orWhere('name', 'like', '%kemala%')
    ->get(['id', 'name', 'email', 'email_verified_at', 'tenant_id', 'role']);

echo "Found " . $users->count() . " users:\n\n";

foreach($users as $u) {
    echo "ID: {$u->id}\n";
    echo "Name: {$u->name}\n";
    echo "Email: {$u->email}\n";
    echo "Verified: " . ($u->email_verified_at ? $u->email_verified_at->format('Y-m-d H:i:s') : 'NOT VERIFIED') . "\n";
    echo "Tenant ID: " . ($u->tenant_id ?? 'null') . "\n";
    echo "Role: {$u->role}\n";
    echo "----------------------------------------\n";
}
