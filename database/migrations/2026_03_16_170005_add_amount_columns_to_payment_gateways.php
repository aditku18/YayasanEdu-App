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
        Schema::table('payment_gateways', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_gateways', 'min_amount')) {
                $table->decimal('min_amount', 15, 2)->default(0);
            }
            
            if (!Schema::hasColumn('payment_gateways', 'max_amount')) {
                $table->decimal('max_amount', 15, 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_gateways', function (Blueprint $table) {
            if (Schema::hasColumn('payment_gateways', 'min_amount')) {
                $table->dropColumn('min_amount');
            }
            
            if (Schema::hasColumn('payment_gateways', 'max_amount')) {
                $table->dropColumn('max_amount');
            }
        });
    }
};
