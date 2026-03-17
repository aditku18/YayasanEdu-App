<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create default plans
$plans = [
    [
        'name' => 'Free Trial',
        'price_per_month' => 0,
        'max_schools' => 1,
        'max_students' => 50,
        'features' => json_encode(['basic_features', 'trial_period'])
    ],
    [
        'name' => 'Basic Plan',
        'price_per_month' => 100000,
        'max_schools' => 1,
        'max_students' => 200,
        'features' => json_encode(['basic_features', 'email_support'])
    ],
    [
        'name' => 'Premium Plan',
        'price_per_month' => 500000,
        'max_schools' => 5,
        'max_students' => 1000,
        'features' => json_encode(['all_features', 'priority_support', 'custom_domain'])
    ]
];

foreach ($plans as $planData) {
    $plan = \App\Models\Plan::firstOrCreate(
        ['name' => $planData['name']],
        $planData
    );
    echo "Plan created/updated: " . $plan->name . " (ID: " . $plan->id . ")\n";
}

echo "\nAvailable plans:\n";
$allPlans = \App\Models\Plan::all();
foreach ($allPlans as $plan) {
    echo "ID: " . $plan->id . " - " . $plan->name . " - Rp " . number_format($plan->price_per_month) . "/bulan\n";
}
