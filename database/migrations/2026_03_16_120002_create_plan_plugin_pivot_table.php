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
        Schema::create('plan_plugin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plans')->onDelete('cascade');
            $table->foreignId('plugin_id')->constrained('plugins')->onDelete('cascade');
            $table->boolean('auto_install')->default(true); // Whether plugin is auto-installed with plan
            $table->integer('sort_order')->default(0); // Order in plan display
            $table->timestamps();
            
            $table->index(['plan_id', 'plugin_id']);
            $table->unique(['plan_id', 'plugin_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_plugin');
    }
};
