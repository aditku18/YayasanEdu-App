<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Foundation;

$foundations = Foundation::where('name', 'like', '%kemala%')
    ->orWhere('name', 'like', '%bhayangkari%')
    ->get(['id', 'name', 'status', 'tenant_id', 'trial_ends_at', 'documents_verified_at', 'created_at']);

foreach($foundations as $f) {
    echo "ID: {$f->id}\n";
    echo "Name: {$f->name}\n";
    echo "Status: {$f->status}\n";
    echo "Tenant ID: " . ($f->tenant_id ?? 'null') . "\n";
    echo "Trial Ends: " . ($f->trial_ends_at ? $f->trial_ends_at->format('Y-m-d H:i:s') : 'null') . "\n";
    echo "Doc Verified: " . ($f->documents_verified_at ? $f->documents_verified_at->format('Y-m-d H:i:s') : 'null') . "\n";
    echo "Created: " . $f->created_at->format('Y-m-d H:i:s') . "\n";
    echo "----------------------------------------\n";
}
