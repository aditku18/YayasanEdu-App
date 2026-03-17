<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Foundation;

echo "--- USERS ---\n";
foreach (User::all(['id', 'email', 'role', 'tenant_id']) as $user) {
    echo "ID: {$user->id} | Email: {$user->email} | Role: {$user->role} | Tenant: " . ($user->tenant_id ?: 'NULL') . "\n";
}

echo "\n--- FOUNDATIONS ---\n";
foreach (Foundation::all(['id', 'name', 'email', 'status', 'tenant_id']) as $f) {
    echo "ID: {$f->id} | Name: {$f->name} | Email: {$f->email} | Status: {$f->status} | Tenant: " . ($f->tenant_id ?: 'NULL') . "\n";
}
