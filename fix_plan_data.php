<?php
/**
 * Fix script to synchronize plan data with database schema
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Plan;

echo "=== FIXING PLAN DATA ===\n\n";

// First, let's also fix the Plan model - remove monthly_price from fillable since it doesn't exist in DB
$modelFile = __DIR__ . '/app/Models/Plan.php';
$modelContent = file_get_contents($modelFile);

// Check if monthly_price is in fillable
if (strpos($modelContent, "'monthly_price'") !== false) {
    echo "Found 'monthly_price' in model fillable - removing since column doesn't exist in database\n";
    $modelContent = str_replace("\n        'monthly_price',", "", $modelContent);
    file_put_contents($modelFile, $modelContent);
    echo "Model fixed!\n\n";
}

// Now fix the plan data
echo "Fixing plan data...\n";

// Fix Plan 6 - Free Trial
$plan6 = Plan::find(6);
if ($plan6) {
    $plan6->update([
        'price_per_year' => 0,
        'max_users' => 50,
        'description' => 'Paket uji coba gratis untuk mencoba fitur dasar',
        'is_active' => true,
        'sort_order' => 0
    ]);
    echo "Fixed Plan 6 (Free Trial)\n";
}

// Fix Plan 7 - Basic Plan
$plan7 = Plan::find(7);
if ($plan7) {
    $plan7->update([
        'price_per_year' => 1000000, // 10 months price for yearly
        'max_users' => 200,
        'description' => 'Paket basic dengan fitur dasar dan dukungan email',
        'is_active' => true,
        'sort_order' => 1
    ]);
    echo "Fixed Plan 7 (Basic Plan)\n";
}

// Fix Plan 8 - Premium Plan
$plan8 = Plan::find(8);
if ($plan8) {
    $plan8->update([
        'price_per_year' => 5000000, // 10 months price for yearly
        'max_users' => 1000,
        'description' => 'Paket premium dengan semua fitur dan dukungan prioritas',
        'is_active' => true,
        'sort_order' => 2
    ]);
    echo "Fixed Plan 8 (Premium Plan)\n";
}

echo "\n=== VERIFICATION ===\n";
$plans = Plan::orderBy('id')->get();
foreach ($plans as $plan) {
    echo "\n--- Plan ID: {$plan->id} ---\n";
    echo "Name: {$plan->name}\n";
    echo "Slug: {$plan->slug}\n";
    echo "Description: " . ($plan->description ?? 'NULL') . "\n";
    echo "Price per month: {$plan->price_per_month}\n";
    echo "Price per year: " . ($plan->price_per_year ?? 'NULL') . "\n";
    echo "Max schools: {$plan->max_schools}\n";
    echo "Max users: " . ($plan->max_users ?? 'NULL') . "\n";
    echo "Max students: {$plan->max_students}\n";
    echo "Is active: " . ($plan->is_active ? 'true' : 'false') . "\n";
    echo "Sort order: {$plan->sort_order}\n";
}

echo "\n=== ALL FIXES COMPLETED ===\n";
