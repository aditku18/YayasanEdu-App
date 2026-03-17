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
        if (Schema::hasTable('p_p_d_b_waves') && !Schema::hasColumn('p_p_d_b_waves', 'registration_fee')) {
            Schema::table('p_p_d_b_waves', function (Blueprint $table) {
                $table->decimal('registration_fee', 15, 2)->default(0)->after('end_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('p_p_d_b_waves', function (Blueprint $table) {
            $table->dropColumn('registration_fee');
        });
    }
};
