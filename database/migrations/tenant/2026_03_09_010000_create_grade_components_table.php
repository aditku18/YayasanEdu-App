<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grade_components', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_unit_id');
            $table->string('name'); // Nama komponen: UH, UTS, UAS, Tugas, dll
            $table->string('code')->nullable(); // Kode: UH1, UH2, UTS, UAS
            $table->string('type'); // types: daily, midterm, final, assignment, project
            $table->integer('weight')->default(0); // Bobot nilai (0-100)
            $table->integer('max_score')->default(100); // Nilai maksimal
            $table->string('semester')->nullable(); // Ganjil/Genap
            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('class_room_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['school_unit_id', 'academic_year_id']);
            $table->unique(['school_unit_id', 'code', 'academic_year_id', 'subject_id'], 'unique_grade_component');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_components');
    }
};
