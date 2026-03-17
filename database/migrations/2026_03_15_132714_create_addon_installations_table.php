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
        Schema::create('addon_installations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('addon_id');
            $table->unsignedBigInteger('tenant_id');
            $table->datetime('installed_at')->nullable();
            $table->datetime('activated_at')->nullable();
            $table->string('status')->default('pending');
            $table->string('version')->nullable();
            $table->json('settings')->nullable();
            $table->string('license_key')->nullable();
            $table->text('installation_notes')->nullable();
            $table->timestamps();
            
            // $table->foreign('addon_id')->references('id')->on('addons')->onDelete('cascade');
            // $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_installations');
    }
};
