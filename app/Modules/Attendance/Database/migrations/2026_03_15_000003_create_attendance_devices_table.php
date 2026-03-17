<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['qr_scanner', 'fingerprint', 'face_scanner', 'rfid_reader', 'gps_mobile', 'kiosk']);
            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->dateTime('last_sync')->nullable();
            $table->json('config')->nullable();
            $table->foreignId('foundation_id')->constrained('foundations')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['foundation_id', 'type']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_devices');
    }
};
