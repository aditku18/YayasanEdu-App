<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('grace_period')->default(15); // minutes
            $table->enum('required_method', ['qr_code', 'fingerprint', 'face', 'rfid', 'gps', 'any'])->default('any');
            $table->boolean('is_active')->default(true);
            $table->foreignId('foundation_id')->constrained('foundations')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['foundation_id', 'is_active']);
            $table->index('start_time');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
