<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_gateway_id')->constrained()->onDelete('cascade');
            $table->string('webhook_id', 100)->nullable(); // ID dari gateway
            $table->string('event_type', 100); // payment.success, payment.failed, etc
            $table->json('payload'); // Full webhook data
            $table->enum('status', ['received', 'processed', 'failed', 'duplicate'])->default('received');
            $table->text('error_message')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            $table->index(['payment_gateway_id', 'event_type']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
