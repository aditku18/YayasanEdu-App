<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('record_id')->nullable()->constrained('attendance_records')->onDelete('set null');
            $table->string('action'); // clock_in, clock_out, failed_attempt, etc.
            $table->string('method'); // qr_code, fingerprint, face, rfid, gps
            $table->json('details')->nullable(); // additional details
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('device_info')->nullable();
            $table->boolean('is_successful')->default(true);
            $table->string('failure_reason')->nullable();
            $table->foreignId('foundation_id')->constrained('foundations')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index(['foundation_id', 'action']);
            $table->index(['record_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_audit_logs');
    }
};
