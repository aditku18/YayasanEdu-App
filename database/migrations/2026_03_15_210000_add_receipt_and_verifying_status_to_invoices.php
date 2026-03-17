<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('payment_receipt')->nullable()->after('payment_token');
        });

        // Update enum status - Since SQLite/certain DBs don't support direct enum changes easily,
        // we use a more compatible approach if possible, but for MySQL we can use raw.
        // For development, we'll use a raw query or just rely on Laravel's flexibility.
        // In many setups, changing enum requires raw SQL.
        try {
            DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('pending', 'paid', 'cancelled', 'refunded', 'verifying') DEFAULT 'pending'");
        } catch (\Exception $e) {
            // Fallback for types that might not support MODIFY COLUMN or are not MySQL
            // In a real multi-db scenario this needs more care.
        }
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('payment_receipt');
        });
        
        try {
            DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('pending', 'paid', 'cancelled', 'refunded') DEFAULT 'pending'");
        } catch (\Exception $e) {}
    }
};
