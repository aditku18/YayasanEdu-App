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
        // 1. Rename schools table to school_units
        if (Schema::hasTable('schools') && !Schema::hasTable('school_units')) {
            Schema::rename('schools', 'school_units');
        }

        // 2. Update school_units table structure
        Schema::table('school_units', function (Blueprint $table) {
            if (!Schema::hasColumn('school_units', 'foundation_id')) {
                $table->foreignId('foundation_id')->nullable()->after('id');
            }
            
            // Adjust status enum to include requested values
            // draft, setup, active, suspended, expired
            $table->string('status')->default('draft')->change();
        });

        // 3. Update users table: rename school_id to school_unit_id
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'school_id')) {
                // We drop the old foreign key first to avoid issues during rename
                try {
                    $table->dropForeign(['school_id']);
                } catch (\Exception $e) {
                    // Ignore if foreign key doesn't exist
                }
                
                $table->renameColumn('school_id', 'school_unit_id');
            }
        });

        // 4. Re-add foreign key constraint with the new name
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'school_unit_id')) {
                $table->foreign('school_unit_id')
                    ->references('id')
                    ->on('school_units')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'school_unit_id')) {
                try {
                    $table->dropForeign(['school_unit_id']);
                } catch (\Exception $e) {}
                
                $table->renameColumn('school_unit_id', 'school_id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
             if (Schema::hasColumn('users', 'school_id')) {
                $table->foreign('school_id')
                    ->references('id')
                    ->on('schools')
                    ->onDelete('set null');
            }
        });

        if (Schema::hasTable('school_units')) {
            Schema::rename('school_units', 'schools');
        }
    }
};
