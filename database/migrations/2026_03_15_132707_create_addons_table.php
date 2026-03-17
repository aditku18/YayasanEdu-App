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
        Schema::create('addons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('category')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_recurring')->default(false);
            $table->json('features')->nullable();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->string('addon_code')->unique();
            $table->string('version')->nullable();
            $table->string('developer')->nullable();
            $table->string('documentation_url')->nullable();
            $table->string('support_url')->nullable();
            $table->json('requirements')->nullable();
            $table->datetime('installation_date')->nullable();
            $table->datetime('expiry_date')->nullable();
            $table->integer('max_users')->nullable();
            $table->integer('max_storage')->nullable();
            $table->json('custom_settings')->nullable();
            $table->timestamps();
            
            // $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addons');
    }
};
