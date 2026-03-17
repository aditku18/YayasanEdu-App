<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

$subdomain = 'pelita-hati2.localhost';
$tenant = Tenant::whereHas('domains', function ($q) use ($subdomain) {
    $q->where('domain', $subdomain);
})->first();

if (!$tenant) {
    echo "Tenant for $subdomain not found.\n";
    exit(1);
}

echo "Initializing tenant: " . $tenant->id . "\n";
tenancy()->initialize($tenant);

// Simulate login request
$request = new Request();
$request->merge([
    'email' => 'aditku02@gmail.com',
    'password' => 'password'
]);

echo "Testing login with Request simulation...\n";

// Test credentials
$credentials = $request->only('email', 'password');
$credentials['email'] = strtolower($credentials['email']);

echo "Credentials: " . json_encode($credentials) . "\n";

// Test Auth::attempt
if (Auth::attempt($credentials)) {
    echo "✓ Login successful!\n";
    $user = Auth::user();
    echo "User: " . $user->name . " (Role: " . $user->role . ")\n";
    Auth::logout();
} else {
    echo "✗ Login failed!\n";
    
    // Check if user exists
    $user = User::where('email', $credentials['email'])->first();
    if ($user) {
        echo "User exists but credentials don't match.\n";
        echo "User ID: " . $user->id . "\n";
        echo "User Email: " . $user->email . "\n";
        echo "User Role: " . $user->role . "\n";
        echo "User Active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
    } else {
        echo "User not found in database.\n";
    }
}

echo "\nDONE.\n";
