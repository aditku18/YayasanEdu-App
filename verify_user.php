<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Find user with aditku023 in tenant database
echo "Searching for user aditku023...\n";

$users = DB::table('users')
    ->where('email', 'like', '%aditku023%')
    ->orWhere('name', 'like', '%aditku%')
    ->get();

if ($users->isEmpty()) {
    echo "No users found in tenant database\n";
} else {
    foreach ($users as $user) {
        echo "Found user:\n";
        echo "- ID: " . $user->id . "\n";
        echo "- Name: " . $user->name . "\n";
        echo "- Email: " . $user->email . "\n";
        echo "- Email Verified At: " . ($user->email_verified_at ?? 'NOT VERIFIED') . "\n";
        echo "- Role: " . ($user->role ?? 'none') . "\n";
        
        // Mark email as verified
        if (!$user->email_verified_at) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['email_verified_at' => now()]);
            echo "✅ Email verified!\n";
        }
    }
}
