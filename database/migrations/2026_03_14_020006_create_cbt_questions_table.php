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
        Schema::create('cbt_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('cbt_quizzes')->onDelete('cascade');
            $table->enum('question_type', ['multiple_choice', 'true_false', 'essay', 'drag_drop', 'matching']);
            $table->longText('question_text');
            $table->string('media_url', 500)->nullable();
            $table->enum('media_type', ['image', 'audio', 'video', 'none'])->default('none');
            $table->integer('points')->default(1);
            $table->text('explanation')->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['quiz_id', 'order_index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_questions');
    }
};
