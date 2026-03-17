<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_gateway_id')->constrained()->onDelete('cascade');
            $table->string('gateway_token', 255); // Token dari gateway
            $table->enum('payment_method', ['credit_card', 'bank_account', 'ewallet', 'qris']);
            $table->string('method_identifier', 100)->nullable(); // Last 4 digits, account number, etc
            $table->string('method_display_name', 150); // User-friendly name
            $table->json('method_metadata')->nullable(); // Additional method data
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'is_active']);
            $table->index(['payment_gateway_id', 'gateway_token']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_tokens');
    }
};
