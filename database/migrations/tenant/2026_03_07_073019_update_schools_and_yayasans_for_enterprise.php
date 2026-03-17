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
        Schema::table('schools', function (Blueprint $table) {
            $table->string('jenjang')->nullable()->after('name'); // TK, SD, SMP, SMA, SMK
            $table->string('status')->default('aktif')->after('address');
        });

        Schema::table('yayasans', function (Blueprint $table) {
            $table->text('legalitas')->after('mission')->nullable();
            $table->text('struktur_organisasi')->after('legalitas')->nullable();
            $table->string('branding_logo')->after('logo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['jenjang', 'status']);
        });

        Schema::table('yayasans', function (Blueprint $table) {
            $table->dropColumn(['legalitas', 'struktur_organisasi', 'branding_logo']);
        });
    }
};
