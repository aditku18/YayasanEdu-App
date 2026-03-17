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
        Schema::table('p_p_d_b_applicants', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->after('document_foto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('p_p_d_b_applicants', function (Blueprint $table) {
            $table->dropColumn('payment_proof');
        });
    }
};
