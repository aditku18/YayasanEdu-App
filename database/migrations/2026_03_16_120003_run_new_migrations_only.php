<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only run our new migrations
        $migrations = [
            '2026_03_16_120000_add_plugin_management_to_plans_table',
            '2026_03_16_120001_add_plugin_tracking_to_foundations_table', 
            '2026_03_16_120002_create_plan_plugin_pivot_table',
        ];

        foreach ($migrations as $migration) {
            $migrationPath = database_path("migrations/{$migration}.php");
            
            if (file_exists($migrationPath)) {
                require_once $migrationPath;
                
                $migrationClass = class_basename($migration, '.php');
                $migrationInstance = new $migrationClass;
                
                if (method_exists($migrationInstance, 'up')) {
                    echo "Running migration: {$migration}\n";
                    $migrationInstance->up();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $migrations = [
            '2026_03_16_120002_create_plan_plugin_pivot_table',
            '2026_03_16_120001_add_plugin_tracking_to_foundations_table', 
            '2026_03_16_120000_add_plugin_management_to_plans_table',
        ];

        foreach ($migrations as $migration) {
            $migrationPath = database_path("migrations/{$migration}.php");
            
            if (file_exists($migrationPath)) {
                require_once $migrationPath;
                
                $migrationClass = class_basename($migration, '.php');
                $migrationInstance = new $migrationClass;
                
                if (method_exists($migrationInstance, 'down')) {
                    echo "Rolling back migration: {$migration}\n";
                    $migrationInstance->down();
                }
            }
        }
    }
};
