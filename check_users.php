<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Foundation;

$foundation = Foundation::find(1);
echo "Foundation email: {$foundation->email}\n";

$users = User::where('email', 'like', '%kbhyd%')
    ->orWhere('email', 'like', '%kemala%')
    ->get(['id', 'name', 'email', 'email_verified_at', 'tenant_id', 'role']);

foreach($users as $u) {
    echo "ID: {$u->id}, Name: {$u->name}, Email: {$u->email}\n";
    echo "Verified: " . ($u->email_verified_at ? $u->email_verified_at->format('Y-m-d H:i:s') : 'null') . "\n";
    echo "Tenant ID: " . ($u->tenant_id ?? 'null') . "\n";
    echo "Role: {$u->role}\n";
    echo "----------------------------------------\n";
}
