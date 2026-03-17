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
            // Add missing columns without 'after' clause
            if (!Schema::hasColumn('payment_gateways', 'display_name')) {
                $table->string('display_name', 150)->nullable();
            }
            
            if (!Schema::hasColumn('payment_gateways', 'type')) {
                $table->enum('type', ['third_party', 'custom'])->default('third_party');
            }
            
            if (!Schema::hasColumn('payment_gateways', 'supports_recurring')) {
                $table->boolean('supports_recurring')->default(false);
            }
            
            if (!Schema::hasColumn('payment_gateways', 'supports_split_payment')) {
                $table->boolean('supports_split_payment')->default(false);
            }
            
            if (!Schema::hasColumn('payment_gateways', 'admin_fee_rate')) {
                $table->decimal('admin_fee_rate', 5, 4)->default(0);
            }
            
            if (!Schema::hasColumn('payment_gateways', 'fixed_admin_fee')) {
                $table->decimal('fixed_admin_fee', 15, 2)->default(0);
            }
            
            if (!Schema::hasColumn('payment_gateways', 'priority')) {
                $table->integer('priority')->default(1);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_gateways', function (Blueprint $table) {
            // Drop the new columns
            $columnsToDrop = ['display_name', 'type', 'supports_recurring', 'supports_split_payment', 'admin_fee_rate', 'fixed_admin_fee', 'priority'];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('payment_gateways', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
