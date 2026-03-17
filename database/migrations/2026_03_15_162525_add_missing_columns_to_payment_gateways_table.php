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
            if (!Schema::hasColumn('payment_gateways', 'display_name')) {
                $table->string('display_name', 150)->after('name')->nullable();
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
            if (!Schema::hasColumn('payment_gateways', 'priority')) {
                $table->integer('priority')->default(1)->after('fee_fixed');
            }
            if (!Schema::hasColumn('payment_gateways', 'admin_fee_rate')) {
                $table->decimal('admin_fee_rate', 5, 2)->default(0)->after('priority');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_gateways', function (Blueprint $table) {
            $table->dropColumn([
                'display_name',
                'type',
                'supports_recurring',
                'supports_split_payment',
                'priority',
                'admin_fee_rate'
            ]);
        });
    }
};
