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
        Schema::create('cbt_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('cbt_modules')->onDelete('cascade');
            $table->string('title');
            $table->enum('content_type', ['video', 'text', 'document', 'quiz', 'assignment'])->default('text');
            $table->longText('content')->nullable();
            $table->string('video_url', 500)->nullable();
            $table->string('attachment_url', 500)->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->integer('order_index')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            
            $table->index(['module_id', 'order_index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_lessons');
    }
};
