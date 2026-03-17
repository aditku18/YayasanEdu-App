<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_number', 50)->unique(); // Nomor pengeluaran
            $table->foreignId('expense_category_id')->constrained('expense_categories')->onDelete('restrict');
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('set null');
            $table->string('description'); // Keterangan pengeluaran
            $table->decimal('amount', 15, 2)->default(0); // Jumlah pengeluaran
            $table->date('expense_date'); // Tanggal pengeluaran
            $table->enum('payment_method', ['cash', 'transfer', 'other'])->default('cash');
            $table->string('vendor_name')->nullable(); // Nama vendor/penjual
            $table->string('vendor_phone')->nullable(); // Telepon vendor
            $table->string('invoice_number')->nullable(); // Nomor invoice dari vendor
            $table->string('receipt')->nullable(); // Bukti kwitansi
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'paid'])->default('draft');
            $table->enum('approval_level', ['staff', 'manager', 'director'])->nullable(); // Level persetujuan
            $table->foreignId('requested_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('school_unit_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['expense_category_id', 'status']);
            $table->index(['expense_date', 'status']);
            $table->index(['requested_by', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
