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
        Schema::create('cbt_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained('cbt_courses')->onDelete('cascade');
            $table->string('template_name');
            $table->longText('template_html')->nullable();
            $table->string('background_image', 500)->nullable();
            $table->string('issued_by', 255)->nullable();
            $table->string('signature_url', 500)->nullable();
            $table->string('seal_url', 500)->nullable();
            $table->timestamps();
            
            $table->index(['tenant_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_certificates');
    }
};
