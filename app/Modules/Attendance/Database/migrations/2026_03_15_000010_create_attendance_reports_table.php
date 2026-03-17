<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['daily', 'weekly', 'monthly', 'custom', 'summary', 'late_arrivals', 'early_departures', 'overtime', 'absences']);
            $table->date('date_from');
            $table->date('date_to');
            $table->json('filters')->nullable(); // user_ids, departments, status, etc.
            $table->foreignId('generated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('file_path')->nullable();
            $table->string('file_format')->nullable(); // pdf, excel, csv
            $table->boolean('is_completed')->default(false);
            $table->foreignId('foundation_id')->constrained('foundations')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['foundation_id', 'generated_by']);
            $table->index(['type', 'date_from']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_reports');
    }
};
