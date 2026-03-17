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
        Schema::table('yayasans', function (Blueprint $table) {
            $table->string('website')->after('email')->nullable();
            $table->text('history')->after('mission')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('yayasans', function (Blueprint $table) {
            $table->dropColumn(['website', 'history']);
        });
    }
};
