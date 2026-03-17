<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Data Migration: Normalize existing status values
        DB::table('schools')->where('status', 'aktif')->update(['status' => 'active']);
        DB::table('schools')->where('status', 'Aktif')->update(['status' => 'active']);

        Schema::table('schools', function (Blueprint $table) {
            if (!Schema::hasColumn('schools', 'level')) {
                $table->string('level')->nullable()->after('jenjang');
            }
            if (!Schema::hasColumn('schools', 'province')) {
                $table->string('province')->nullable()->after('address');
            }
            if (!Schema::hasColumn('schools', 'city')) {
                $table->string('city')->nullable()->after('province');
            }
            if (!Schema::hasColumn('schools', 'district')) {
                $table->string('district')->nullable()->after('city');
            }
            if (!Schema::hasColumn('schools', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('district');
            }
            if (!Schema::hasColumn('schools', 'principal_email')) {
                $table->string('principal_email')->nullable()->after('principal_name');
            }
            if (!Schema::hasColumn('schools', 'principal_phone')) {
                $table->string('principal_phone')->nullable()->after('principal_email');
            }
            
            // For enum changes, we use modify column logic. 
            // Ensure any existing value not in the list is handled.
            $table->enum('status', ['draft', 'setup', 'active', 'nonactive'])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn([
                'level', 'province', 'city', 'district', 'postal_code', 
                'principal_email', 'principal_phone'
            ]);
            $table->string('status')->default('aktif')->change();
        });
    }
};
