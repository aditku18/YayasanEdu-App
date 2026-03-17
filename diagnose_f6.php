<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Foundation;

$f = Foundation::with('adminUser')->find(6);
if ($f) {
    echo "Foundation ID: " . $f->id . "\n";
    echo "Foundation Name: " . $f->name . "\n";
    echo "Foundation Email: " . $f->email . "\n";
    if ($f->adminUser) {
        echo "Admin User ID: " . $f->adminUser->id . "\n";
        echo "Admin User Email: " . $f->adminUser->email . "\n";
    } else {
        echo "Admin User: NOT FOUND\n";
    }
} else {
    echo "Foundation 6 not found.\n";
}
