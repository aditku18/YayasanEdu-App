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
        if (!Schema::hasTable('plan_plugin')) {
            Schema::create('plan_plugin', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('plan_id');
                $table->unsignedBigInteger('plugin_id');
                $table->boolean('auto_install')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                
                $table->index(['plan_id', 'plugin_id']);
                $table->unique(['plan_id', 'plugin_id']);
                
                // Add foreign key constraints
                $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
                $table->foreign('plugin_id')->references('id')->on('plugins')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_plugin');
    }
};
