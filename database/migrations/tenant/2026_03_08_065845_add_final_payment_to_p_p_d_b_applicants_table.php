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
            $table->string('final_payment_proof')->nullable()->after('payment_proof');
            $table->timestamp('final_payment_at')->nullable()->after('final_payment_proof');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('p_p_d_b_applicants', function (Blueprint $table) {
            $table->dropColumn(['final_payment_proof', 'final_payment_at']);
        });
    }
};
