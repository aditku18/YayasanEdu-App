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
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'payment_token')) {
                $table->string('payment_token')->nullable()->after('items');
            }
            
            if (!Schema::hasColumn('invoices', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('foundation_id');
            }
            
            if (!Schema::hasColumn('invoices', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable()->after('paid_at');
            }
            
            if (!Schema::hasColumn('invoices', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verified_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'payment_token')) {
                $table->dropColumn('payment_token');
            }
            
            if (Schema::hasColumn('invoices', 'created_by')) {
                $table->dropColumn('created_by');
            }
            
            if (Schema::hasColumn('invoices', 'verified_by')) {
                $table->dropColumn('verified_by');
            }
            
            if (Schema::hasColumn('invoices', 'verified_at')) {
                $table->dropColumn('verified_at');
            }
        });
    }
};
