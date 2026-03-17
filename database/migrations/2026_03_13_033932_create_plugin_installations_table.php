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
        Schema::create('plugin_installations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plugin_id')->constrained('plugins')->onDelete('cascade');
            $table->foreignId('foundation_id')->constrained('foundations')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamp('installed_at')->nullable();
            $table->foreignId('installed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->json('settings')->nullable();
            $table->timestamp('last_updated_at')->nullable();
            $table->timestamps();
            
            $table->index(['plugin_id', 'foundation_id']);
            $table->index(['foundation_id', 'is_active']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plugin_installations');
    }
};
