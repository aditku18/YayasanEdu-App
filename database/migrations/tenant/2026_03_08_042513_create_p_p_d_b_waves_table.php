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
        if (!Schema::hasTable('p_p_d_b_waves')) {
            Schema::create('p_p_d_b_waves', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_unit_id');
                $table->unsignedBigInteger('academic_year_id')->nullable();
                $table->string('name');
                $table->date('start_date');
                $table->date('end_date');
                $table->string('status')->default('active'); // active, inactive, closed
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_p_d_b_waves');
    }
};
