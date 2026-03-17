<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->json('included_plugins')->nullable(); // Array of plugin slugs
            $table->integer('plugin_slots')->default(0); // Max additional plugins allowed
            $table->json('plugin_categories')->nullable(); // Allowed plugin categories
            $table->decimal('bundle_savings', 8, 2)->default(0); // Savings compared to individual plugin prices
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['included_plugins', 'plugin_slots', 'plugin_categories', 'bundle_savings']);
        });
    }
};
