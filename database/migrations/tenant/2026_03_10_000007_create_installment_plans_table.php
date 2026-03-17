<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installment_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_number', 50)->unique(); // Nomor rencana cicilan
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 15, 2)->default(0); // Total tagihan
            $table->integer('total_installments')->default(1); // Jumlah cicilan
            $table->decimal('amount_per_installment', 15, 2)->default(0); // Jumlah per cicilan
            $table->date('first_due_date'); // Tanggal jatuh tempo pertama
            $table->enum('status', ['active', 'completed', 'defaulted', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('school_unit_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installment_plans');
    }
};
