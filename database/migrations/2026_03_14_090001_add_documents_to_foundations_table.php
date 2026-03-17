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
            $table->string('address')->nullable()->after('phone');
            $table->string('province')->nullable()->after('address');
            $table->string('regency')->nullable()->after('province');
            $table->string('npsn')->nullable()->after('regency');
            $table->string('website')->nullable()->after('npsn');
            $table->string('sk_pendirian_path')->nullable()->after('website');
            $table->string('npsn_izin_path')->nullable()->after('sk_pendirian_path');
            $table->string('logo_path')->nullable()->after('npsn_izin_path');
            $table->string('gedung_path')->nullable()->after('logo_path');
            $table->string('ktp_path')->nullable()->after('gedung_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('foundations', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'province',
                'regency',
                'npsn',
                'website',
                'sk_pendirian_path',
                'npsn_izin_path',
                'logo_path',
                'gedung_path',
                'ktp_path'
            ]);
        });
    }
};
