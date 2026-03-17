<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

// Check user details
$user = User::where('email', 'aditku17@gmail.Com')->first();
if ($user) {
    echo "User Details:\n";
    echo "- Name: " . $user->name . "\n";
    echo "- Email: " . $user->email . "\n";
    echo "- Created: " . $user->created_at . "\n";
    echo "- Email Verified: " . ($user->email_verified_at ? 'Yes' : 'No') . "\n";
    echo "- Tenant ID: " . ($user->tenant_id ?? 'null') . "\n";
    
    // Check if password is hashed
    echo "- Password Hash: " . substr($user->password, 0, 20) . "...\n";
    
    // Try common passwords for testing
    $commonPasswords = ['password', '123456', 'admin', '12345678', 'qwerty'];
    echo "\nTesting common passwords:\n";
    
    foreach ($commonPasswords as $password) {
        if (\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
            echo "✅ PASSWORD FOUND: " . $password . "\n";
            break;
        } else {
            echo "❌ " . $password . " - incorrect\n";
        }
    }
} else {
    echo "User not found\n";
}
