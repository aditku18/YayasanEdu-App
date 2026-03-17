<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique(); // midtrans, xendit, custom
            $table->string('display_name', 150); // Midtrans, Xendit, Custom Gateway
            $table->enum('type', ['third_party', 'custom'])->default('third_party');
            $table->json('config'); // API keys, endpoints, etc
            $table->boolean('is_active')->default(true);
            $table->boolean('supports_recurring')->default(false);
            $table->boolean('supports_split_payment')->default(false);
            $table->json('supported_methods'); // ['credit_card', 'bank_transfer', 'ewallet', 'qris']
            $table->decimal('min_amount', 15, 2)->default(0);
            $table->decimal('max_amount', 15, 2)->nullable();
            $table->decimal('admin_fee_rate', 5, 4)->default(0); // 0.0250 = 2.5%
            $table->decimal('fixed_admin_fee', 15, 2)->default(0);
            $table->integer('priority')->default(1); // Untuk fallback
            $table->timestamps();
            
            $table->index(['is_active', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
