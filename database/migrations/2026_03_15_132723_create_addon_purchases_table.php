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
        Schema::create('addon_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('addon_id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->datetime('purchase_date');
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('currency', 3)->default('IDR');
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('transaction_id')->nullable();
            $table->string('license_key')->nullable();
            $table->datetime('expiry_date')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->string('billing_cycle')->nullable();
            $table->datetime('next_billing_date')->nullable();
            $table->timestamps();
            
            // $table->foreign('addon_id')->references('id')->on('addons')->onDelete('cascade');
            // $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_purchases');
    }
};
