<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama kategori pengeluaran
            $table->string('code', 50)->unique(); // Kode kategori
            $table->text('description')->nullable();
            $table->boolean('requires_approval')->default(false); // Memerlukan persetujuan
            $table->decimal('max_amount_without_approval', 15, 2)->nullable(); // Maksimum tanpa persetujuan
            $table->boolean('is_active')->default(true);
            $table->foreignId('school_unit_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['school_unit_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_categories');
    }
};
