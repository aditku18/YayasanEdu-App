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
        Schema::create('cbt_certificate_issued', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->constrained('cbt_certificates')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained('cbt_courses')->onDelete('cascade');
            $table->string('certificate_number', 50)->unique();
            $table->timestamp('issued_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->string('download_url', 500)->nullable();
            $table->string('verification_code', 64)->unique();
            $table->timestamps();
            
            $table->index(['user_id', 'course_id']);
            $table->index(['certificate_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_certificate_issued');
    }
};
