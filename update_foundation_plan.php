<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get all foundations without plan_id
$foundations = \App\Models\Foundation::whereNull('plan_id')->get();

echo "Foundations to update:\n";
foreach ($foundations as $foundation) {
    echo "ID: " . $foundation->id . " - " . $foundation->name . " - " . $foundation->subdomain . "\n";
    
    try {
        // Update with Free Trial plan (ID: 1)
        $foundation->update([
            'plan_id' => 1,
            'status' => 'trial',
            'trial_ends_at' => now()->addDays(14),
        ]);
        
        echo "  -> Updated with Free Trial plan\n";
    } catch (\Exception $e) {
        echo "  -> Error: " . $e->getMessage() . "\n";
    }
}

echo "\nUpdate completed!\n";

// Show updated foundations
echo "\nAll foundations:\n";
$allFoundations = \App\Models\Foundation::with('plan')->get();
foreach ($allFoundations as $foundation) {
    $planName = $foundation->plan ? $foundation->plan->name : 'No plan';
    echo "ID: " . $foundation->id . " - " . $foundation->name . " - Plan: " . $planName . " - Status: " . $foundation->status . "\n";
}
