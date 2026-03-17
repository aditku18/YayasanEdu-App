<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_splits', function (Blueprint $table) {
            $table->foreignId('foundation_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('payment_splits', function (Blueprint $table) {
            $table->dropForeign(['foundation_id']);
            $table->dropColumn('foundation_id');
        });
    }
};
