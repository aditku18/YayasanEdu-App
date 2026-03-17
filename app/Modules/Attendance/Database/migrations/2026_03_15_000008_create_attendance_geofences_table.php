<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_geofences', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('center_lat', 10, 8);
            $table->decimal('center_long', 11, 8);
            $table->integer('radius_meters')->default(100); // default 100m
            $table->boolean('is_active')->default(true);
            $table->boolean('alert_outside_zone')->default(true);
            $table->foreignId('session_id')->nullable()->constrained('attendance_sessions')->onDelete('set null');
            $table->foreignId('foundation_id')->constrained('foundations')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['foundation_id', 'is_active']);
            $table->index('session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_geofences');
    }
};
