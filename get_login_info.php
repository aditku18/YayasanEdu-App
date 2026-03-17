<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$f = App\Models\Foundation::find(6);
echo "Subdomain: " . $f->subdomain . "\n";
echo "Email: " . $f->adminUser->email . "\n";
echo "Status: " . $f->status . "\n";
