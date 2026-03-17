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
        Schema::table('foundations', function (Blueprint $table) {
            $table->json('included_plugins')->nullable(); // Auto-installed plugins from plan
            $table->json('additional_plugins')->nullable(); // User-selected additional plugins
            $table->integer('plugin_slots')->default(0); // Max additional plugins allowed
            $table->timestamp('plugins_installed_at')->nullable(); // When plugins were installed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('foundations', function (Blueprint $table) {
            $table->dropColumn(['included_plugins', 'additional_plugins', 'plugin_slots', 'plugins_installed_at']);
        });
    }
};
