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
        Schema::create('majors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('abbreviation')->nullable();
            $table->string('head_of_major')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'nonactive'])->default('active');
            $table->timestamps();
        });

        // Add major_id to class_rooms
        Schema::table('class_rooms', function (Blueprint $table) {
            $table->foreignId('major_id')->nullable()->constrained('majors')->onDelete('set null')->after('school_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_rooms', function (Blueprint $table) {
            $table->dropForeign(['major_id']);
            $table->dropColumn('major_id');
        });
        Schema::dropIfExists('majors');
    }
};
