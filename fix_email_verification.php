<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$user = User::where('email', 'kbhyd@gmail.com')->first();
if ($user) {
    $user->email_verified_at = now();
    $user->save();
    echo "Email verified for: {$user->email}\n";
    echo "Verification time: {$user->email_verified_at}\n";
} else {
    echo "User not found!\n";
}
