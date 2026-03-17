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
            // Add missing columns one by one, checking if they exist first
            if (!Schema::hasColumn('payment_gateways', 'display_name')) {
                $table->string('display_name', 150)->nullable()->after('name');
            }
            
            if (!Schema::hasColumn('payment_gateways', 'type')) {
                $table->enum('type', ['third_party', 'custom'])->default('third_party')->after('display_name');
            }
            
            if (!Schema::hasColumn('payment_gateways', 'supports_recurring')) {
                $table->boolean('supports_recurring')->default(false)->after('is_active');
            }
            
            if (!Schema::hasColumn('payment_gateways', 'supports_split_payment')) {
                $table->boolean('supports_split_payment')->default(false)->after('supports_recurring');
            }
            
            if (!Schema::hasColumn('payment_gateways', 'admin_fee_rate')) {
                $table->decimal('admin_fee_rate', 5, 4)->default(0)->after('fee_fixed');
            }
            
            if (!Schema::hasColumn('payment_gateways', 'fixed_admin_fee')) {
                $table->decimal('fixed_admin_fee', 15, 2)->default(0)->after('admin_fee_rate');
            }
            
            if (!Schema::hasColumn('payment_gateways', 'priority')) {
                $table->integer('priority')->default(1)->after('fixed_admin_fee');
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
