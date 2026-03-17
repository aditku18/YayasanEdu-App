<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_rfids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('device_id')->nullable()->constrained('attendance_devices')->onDelete('set null');
            $table->string('card_number')->unique();
            $table->string('encrypted_data')->nullable(); // AES-256 encrypted card data
            $table->string('card_type')->nullable(); // 125kHz, 13.56MHz, etc.
            $table->boolean('is_active')->default(true);
            $table->boolean('is_blacklisted')->default(false);
            $table->foreignId('enrolled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('enrolled_at');
            $table->dateTime('last_used_at')->nullable();
            $table->foreignId('foundation_id')->constrained('foundations')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['user_id', 'is_active']);
            $table->index(['card_number', 'is_blacklisted']);
            $table->index('device_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_rfids');
    }
};
