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
        Schema::create('plugins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('version');
            $table->string('category')->nullable();
            $table->string('developer')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->boolean('is_available_in_marketplace')->default(true);
            $table->string('status')->default('active'); // active, inactive, deprecated
            $table->json('features')->nullable();
            $table->json('requirements')->nullable();
            $table->string('documentation_url')->nullable();
            $table->timestamps();
            
            $table->index(['category', 'status']);
            $table->index('is_available_in_marketplace');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plugins');
    }
};
