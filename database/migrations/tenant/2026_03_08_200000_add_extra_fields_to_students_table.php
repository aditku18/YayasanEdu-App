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
        Schema::table('students', function (Blueprint $table) {
            $table->string('nik')->nullable()->after('nisn');
            $table->string('father_name')->nullable()->after('parent_phone');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->string('guardian_name')->nullable()->after('mother_name');
            $table->unsignedBigInteger('classroom_id')->nullable()->after('guardian_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['nik', 'father_name', 'mother_name', 'guardian_name', 'classroom_id']);
        });
    }
};
