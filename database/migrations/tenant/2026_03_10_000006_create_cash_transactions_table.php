<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number', 50)->unique(); // Nomor transaksi
            $table->enum('type', ['cash_in', 'cash_out']); // Jenis: Kas Masuk / Kas Keluar
            $table->string('category'); // Kategori (Pembayaran SPP, Pembelian ATK, dll)
            $table->foreignId('reference_id')->nullable(); // ID referensi (invoice_id, expense_id)
            $table->string('reference_type')->nullable(); // Tipe referensi (App\Models\Invoice, App\Models\Expense)
            $table->decimal('amount', 15, 2)->default(0); // Jumlah
            $table->date('transaction_date'); // Tanggal transaksi
            $table->text('description')->nullable(); // Keterangan
            $table->enum('payment_method', ['cash', 'transfer', 'other'])->default('cash');
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('recipient_name')->nullable(); // Nama penerima/penyetor
            $table->string('attachment')->nullable(); // Lampiran
            $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('school_unit_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['type', 'transaction_date']);
            $table->index(['school_unit_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_transactions');
    }
};
