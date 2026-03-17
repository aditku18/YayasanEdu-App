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
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('payment_gateways', 'display_name')) {
                $table->string('display_name', 150)->after('name');
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
                $table->decimal('admin_fee_rate', 5, 4)->default(0)->after('max_amount');
            }
            
            if (!Schema::hasColumn('payment_gateways', 'fixed_admin_fee')) {
                $table->decimal('fixed_admin_fee', 15, 2)->default(0)->after('admin_fee_rate');
            }
            
            if (!Schema::hasColumn('payment_gateways', 'priority')) {
                $table->integer('priority')->default(1)->after('fixed_admin_fee');
            }
            
            // Update existing data
            $table->string('name', 100)->change();
            $table->string('display_name', 150)->nullable()->change();
            
            // Drop columns that are no longer needed
            if (Schema::hasColumn('payment_gateways', 'code')) {
                $table->dropColumn('code');
            }
            
            if (Schema::hasColumn('payment_gateways', 'currency')) {
                $table->dropColumn('currency');
            }
            
            if (Schema::hasColumn('payment_gateways', 'fee_percentage')) {
                $table->dropColumn('fee_percentage');
            }
            
            if (Schema::hasColumn('payment_gateways', 'fee_fixed')) {
                $table->dropColumn('fee_fixed');
            }
            
            // Add indexes
            $table->index(['is_active', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_gateways', function (Blueprint $table) {
            // Add back the old columns
            $table->string('code', 100)->after('name');
            $table->string('currency', 3)->default('IDR')->after('supported_methods');
            $table->decimal('fee_percentage', 5, 2)->default(0)->after('max_amount');
            $table->decimal('fee_fixed', 10, 2)->default(0)->after('fee_percentage');
            
            // Drop the new columns
            $table->dropColumn(['display_name', 'type', 'supports_recurring', 'supports_split_payment', 'admin_fee_rate', 'fixed_admin_fee', 'priority']);
            
            // Drop index
            $table->dropIndex(['is_active', 'priority']);
        });
    }
};
