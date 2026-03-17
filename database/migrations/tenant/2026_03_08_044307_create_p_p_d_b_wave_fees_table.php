<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('p_p_d_b_wave_fees')) {
            Schema::create('p_p_d_b_wave_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ppdb_wave_id');
            $table->unsignedBigInteger('ppdb_fee_component_id');
            $table->decimal('amount', 15, 2);
            $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_p_d_b_wave_fees');
    }
};
