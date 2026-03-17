<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bill_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama jenis tagihan (SPP, Buku, Kegiatan, dll)
            $table->string('code', 50)->unique(); // Kode (SPP, BOOK, ACT)
            $table->text('description')->nullable();
            $table->enum('type', ['monthly', 'one_time', 'recurring']); // Jenis tagihan
            $table->decimal('default_amount', 15, 2)->nullable()->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('school_unit_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['school_unit_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_types');
    }
};
