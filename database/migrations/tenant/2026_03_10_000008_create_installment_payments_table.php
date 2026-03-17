<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installment_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installment_plan_id')->constrained('installment_plans')->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('installment_number'); // Nomor cicilan (1, 2, 3, ...)
            $table->decimal('amount_due', 15, 2)->default(0); // Jumlah yang harus dibayar
            $table->decimal('amount_paid', 15, 2)->default(0); // Jumlah yang dibayar
            $table->date('due_date'); // Tanggal jatuh tempo
            $table->date('paid_date')->nullable(); // Tanggal pembayaran
            $table->enum('status', ['pending', 'paid', 'late', 'defaulted'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['installment_plan_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installment_payments');
    }
};
