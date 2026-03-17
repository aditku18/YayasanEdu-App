<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique(); // Nomor tagihan
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('bill_type_id')->constrained('bill_types')->onDelete('restrict');
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('set null');
            $table->foreignId('classroom_id')->nullable()->constrained('class_rooms')->onDelete('set null');
            $table->string('month', 7)->nullable(); // Format: 2026-01 (untuk SPP)
            $table->string('description')->nullable(); // Keterangan tambahan
            $table->decimal('amount', 15, 2)->default(0); // Jumlah tagihan
            $table->decimal('discount', 15, 2)->default(0); // Diskon
            $table->decimal('final_amount', 15, 2)->default(0); // Jumlah setelah diskon
            $table->decimal('paid_amount', 15, 2)->default(0); // Jumlah yang sudah dibayar
            $table->decimal('remaining_amount', 15, 2)->default(0); // Sisa tagihan
            $table->date('due_date')->nullable(); // Tanggal jatuh tempo
            $table->enum('status', ['unpaid', 'partial', 'paid', 'overdue', 'cancelled'])->default('unpaid');
            $table->enum('payment_method', ['cash', 'transfer', 'virtual_account', 'qris', 'other'])->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('school_unit_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['student_id', 'status']);
            $table->index(['academic_year_id', 'status']);
            $table->index(['due_date', 'status']);
            $table->index('school_unit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
