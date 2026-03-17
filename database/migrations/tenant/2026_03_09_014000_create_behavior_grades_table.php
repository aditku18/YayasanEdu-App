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
        Schema::create('behavior_grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_unit_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('academic_year_id');
            $table->string('aspect'); // spiritual, social
            $table->string('semester'); // ganjil, genap
            $table->string('grade'); // sangat baik, baik, cukup, kurang
            $table->text('description')->nullable();
            $table->unsignedBigInteger('entered_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('behavior_grades');
    }
};
