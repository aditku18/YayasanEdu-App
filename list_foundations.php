<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$foundations = \App\Models\Foundation::all();

echo "Foundations:\n";
foreach($foundations as $foundation) {
    echo 'ID: ' . $foundation->id . ', Name: ' . $foundation->name . ', Subdomain: ' . $foundation->subdomain . ', Status: ' . $foundation->status . PHP_EOL;
}
