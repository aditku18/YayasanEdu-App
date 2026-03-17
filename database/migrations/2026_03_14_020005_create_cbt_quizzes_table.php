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
        Schema::create('cbt_quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->nullable()->constrained('cbt_lessons')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('cbt_courses')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('quiz_type', ['assignment', 'exam', 'practice'])->default('exam');
            $table->integer('time_limit_minutes')->default(0);
            $table->integer('attempt_limit')->default(0);
            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('shuffle_answers')->default(false);
            $table->boolean('show_correct_answers')->default(true);
            $table->integer('passing_score')->default(70);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            
            $table->index(['course_id', 'is_published']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_quizzes');
    }
};
