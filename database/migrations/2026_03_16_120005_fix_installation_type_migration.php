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
        // Check if table exists and add column if not exists
        if (Schema::hasTable('plugin_installations')) {
            Schema::table('plugin_installations', function (Blueprint $table) {
                $table->string('installation_type')->default('manual')->after('installed_by');
                // Values: 'manual', 'included', 'additional'
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('plugin_installations')) {
            Schema::table('plugin_installations', function (Blueprint $table) {
                $table->dropColumn('installation_type');
            });
        }
    }
};
