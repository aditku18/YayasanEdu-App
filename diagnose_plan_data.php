<?php
/**
 * Diagnostic script to check plan data inconsistencies
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Plan;
use Illuminate\Support\Facades\Schema;

echo "=== DIAGNOSIS: PLAN DATA CONSISTENCY ===\n\n";

// Check database columns
$columns = Schema::getColumnListing('plans');
echo "Database columns in 'plans' table:\n";
print_r($columns);

// Check model fillable fields
echo "\nModel Plan fillable fields:\n";
$model = new Plan();
print_r($model->getFillable());

echo "\n=== PLAN DATA IN DATABASE ===\n";
$plans = Plan::all();

foreach ($plans as $plan) {
    echo "\n--- Plan ID: {$plan->id} ---\n";
    echo "Name: {$plan->name}\n";
    echo "Slug: " . ($plan->slug ?? 'NULL') . "\n";
    echo "Description: " . ($plan->description ?? 'NULL') . "\n";
    echo "Price per month: {$plan->price_per_month}\n";
    echo "Price per year: " . ($plan->price_per_year ?? 'NULL') . "\n";
    echo "Monthly price (model): " . ($plan->monthly_price ?? 'NULL') . "\n";
    echo "Max schools: {$plan->max_schools}\n";
    echo "Max users: " . ($plan->max_users ?? 'NULL') . "\n";
    echo "Max students: {$plan->max_students}\n";
    echo "Features: " . ($plan->features ? json_encode($plan->features) : 'NULL') . "\n";
    echo "Is active: " . ($plan->is_active ? 'true' : 'false') . "\n";
    echo "Is featured: " . ($plan->is_featured ? 'true' : 'false') . "\n";
    echo "Sort order: {$plan->sort_order}\n";
    echo "Created: {$plan->created_at}\n";
    echo "Updated: {$plan->updated_at}\n";
}

echo "\n=== INCONSISTENCIES FOUND ===\n";
$issues = [];

// Check for plans without slug
foreach ($plans as $plan) {
    if (empty($plan->slug)) {
        $issues[] = "Plan ID {$plan->id} ({$plan->name}): Missing slug";
    }
    if (is_null($plan->max_users)) {
        $issues[] = "Plan ID {$plan->id} ({$plan->name}): Missing max_users";
    }
    if (is_null($plan->price_per_year)) {
        $issues[] = "Plan ID {$plan->id} ({$plan->name}): Missing price_per_year";
    }
}

if (empty($issues)) {
    echo "No issues found!\n";
} else {
    foreach ($issues as $issue) {
        echo "- {$issue}\n";
    }
}

echo "\n=== TOTAL PLANS: " . $plans->count() . " ===\n";
