<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recurring_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_token_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_template_id')->nullable(); // Template untuk recurring invoice
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'quarterly', 'yearly']);
            $table->integer('frequency_value')->default(1); // Every X days/weeks/months
            $table->date('next_charge_date');
            $table->date('last_charge_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('total_charges')->default(0);
            $table->integer('max_charges')->nullable(); // Unlimited jika null
            $table->enum('status', ['active', 'paused', 'completed', 'cancelled'])->default('active');
            $table->json('last_gateway_response')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['next_charge_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recurring_payments');
    }
};
