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
        Schema::table('foundations', function (Blueprint $table) {
            $table->string('institution_type')->nullable()->after('website');
            $table->json('education_levels')->nullable()->after('institution_type');
            $table->integer('student_count')->nullable()->after('education_levels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('foundations', function (Blueprint $table) {
            $table->dropColumn(['institution_type', 'education_levels', 'student_count']);
        });
    }
};
