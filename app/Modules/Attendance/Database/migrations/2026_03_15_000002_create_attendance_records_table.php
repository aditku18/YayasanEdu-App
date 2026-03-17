<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('session_id')->nullable()->constrained('attendance_sessions')->onDelete('set null');
            $table->foreignId('device_id')->nullable()->constrained('attendance_devices')->onDelete('set null');
            $table->dateTime('check_in_time')->nullable();
            $table->dateTime('check_out_time')->nullable();
            $table->enum('method', ['qr_code', 'fingerprint', 'face', 'rfid', 'gps', 'manual']);
            $table->enum('status', ['present', 'late', 'absent', 'excused', 'on_leave'])->default('present');
            $table->decimal('location_lat', 10, 8)->nullable();
            $table->decimal('location_long', 11, 8)->nullable();
            $table->string('verification_data')->nullable(); // encrypted verification data
            $table->text('notes')->nullable();
            $table->boolean('is_duplicate')->default(false);
            $table->foreignId('foundation_id')->constrained('foundations')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['user_id', 'check_in_time']);
            $table->index(['session_id', 'date']);
            $table->index(['foundation_id', 'status']);
            $table->index('method');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
