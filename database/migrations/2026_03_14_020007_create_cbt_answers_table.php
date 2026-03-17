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
        Schema::create('cbt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('cbt_questions')->onDelete('cascade');
            $table->longText('answer_text');
            $table->boolean('is_correct')->default(false);
            $table->integer('order_index')->default(0);
            $table->foreignId('match_item_id')->nullable()->constrained('cbt_answers')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['question_id', 'order_index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_answers');
    }
};
