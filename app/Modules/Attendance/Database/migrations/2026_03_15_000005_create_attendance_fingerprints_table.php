<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_fingerprints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('device_id')->nullable()->constrained('attendance_devices')->onDelete('set null');
            $table->text('template_data'); // encrypted biometric template
            $table->enum('finger_position', ['thumb', 'index', 'middle', 'ring', 'little', 'left_thumb', 'left_index', 'left_middle', 'left_ring', 'left_little']);
            $table->boolean('is_active')->default(true);
            $table->boolean('liveness_enabled')->default(true);
            $table->foreignId('enrolled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('enrolled_at');
            $table->dateTime('last_verified_at')->nullable();
            $table->foreignId('foundation_id')->constrained('foundations')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['user_id', 'is_active']);
            $table->index(['device_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_fingerprints');
    }
};
