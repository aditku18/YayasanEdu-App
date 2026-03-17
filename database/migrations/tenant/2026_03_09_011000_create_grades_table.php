<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_unit_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('grade_component_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('class_room_id');
            $table->unsignedBigInteger('academic_year_id');
            $table->decimal('score', 5, 2); // Nilai siswa
            $table->text('notes')->nullable(); // Catatan
            $table->unsignedBigInteger('entered_by')->nullable();
            $table->timestamps();
            
            $table->index(['student_id', 'academic_year_id']);
            $table->unique(
                ['student_id', 'grade_component_id', 'subject_id'],
                'unique_student_grade'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
