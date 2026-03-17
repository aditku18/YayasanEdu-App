<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Foundation;

// Check existing foundations
$foundations = Foundation::all();
echo "Total foundations: " . $foundations->count() . "\n";

foreach ($foundations as $foundation) {
    echo "- Foundation ID: " . $foundation->id . "\n";
    echo "  Name: " . $foundation->name . "\n";
    echo "  Subdomain: " . $foundation->subdomain . "\n";
    echo "  Tenant ID: " . ($foundation->tenant_id ?? 'None') . "\n";
    echo "  Status: " . ($foundation->status ?? 'None') . "\n";
    echo "\n";
}
