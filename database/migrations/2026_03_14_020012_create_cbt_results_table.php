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
        Schema::create('cbt_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('cbt_quiz_attempts')->onDelete('cascade');
            $table->decimal('total_points', 10, 2)->default(0.00);
            $table->decimal('earned_points', 10, 2)->default(0.00);
            $table->decimal('percentage', 5, 2)->default(0.00);
            $table->string('grade', 2)->nullable();
            $table->boolean('is_passed')->default(false);
            $table->foreignId('certificate_id')->nullable()->constrained('cbt_certificates')->onDelete('set null');
            $table->timestamps();
            
            $table->unique(['attempt_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_results');
    }
};
