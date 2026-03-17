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
        // Add school_unit_id to subjects table
        Schema::table('subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('school_unit_id')->nullable()->after('name');
        });

        // Add school_unit_id to class_rooms table
        Schema::table('class_rooms', function (Blueprint $table) {
            $table->unsignedBigInteger('school_unit_id')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['school_unit_id']);
        });

        Schema::table('class_rooms', function (Blueprint $table) {
            $table->dropColumn(['school_unit_id']);
        });
    }
};
