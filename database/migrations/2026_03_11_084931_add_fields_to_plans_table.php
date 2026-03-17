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
        Schema::table('plans', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->text('description')->nullable()->after('slug');
            $table->decimal('price_per_year', 15, 2)->nullable()->after('price_per_month');
            $table->integer('max_users')->nullable()->after('max_students');
            $table->boolean('is_active')->default(true)->after('features');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->integer('sort_order')->default(0)->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'description',
                'price_per_year',
                'max_users',
                'is_active',
                'is_featured',
                'sort_order',
            ]);
        });
    }
};
