<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number', 50)->unique(); // Nomor pembayaran
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2)->default(0); // Jumlah pembayaran
            $table->decimal('admin_fee', 15, 2)->default(0); // Biaya admin
            $table->decimal('total_amount', 15, 2)->default(0); // Total termasuk biaya admin
            $table->date('payment_date'); // Tanggal pembayaran
            $table->enum('payment_method', ['cash', 'transfer', 'virtual_account', 'qris', 'other']);
            $table->string('bank_name')->nullable(); // Nama bank (jika transfer)
            $table->string('account_number')->nullable(); // Nomor rekening
            $table->string('account_name')->nullable(); // Nama rekening
            $table->string('reference_number')->nullable(); // Nomor referensi/validasi
            $table->string('payment_proof')->nullable(); // Bukti pembayaran
            $table->enum('status', ['pending', 'confirmed', 'rejected', 'refunded'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('school_unit_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['student_id', 'payment_date']);
            $table->index(['invoice_id', 'status']);
            $table->index(['payment_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
