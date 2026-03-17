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
        Schema::table('plugin_installations', function (Blueprint $table) {
            $table->string('installation_type')->default('manual')->after('installed_by');
            // Values: 'manual', 'included', 'additional'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plugin_installations', function (Blueprint $table) {
            $table->dropColumn('installation_type');
        });
    }
};
