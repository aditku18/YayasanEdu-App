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
        Schema::table('foundations', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_user_id')->nullable()->after('plan_id');
            $table->timestamp('documents_verified_at')->nullable()->after('admin_user_id');
            $table->unsignedBigInteger('documents_verified_by')->nullable()->after('documents_verified_at');

            $table->foreign('admin_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('documents_verified_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('foundations', function (Blueprint $table) {
            $table->dropForeign(['admin_user_id']);
            $table->dropForeign(['documents_verified_by']);
            $table->dropColumn(['admin_user_id', 'documents_verified_at', 'documents_verified_by']);
        });
    }
};
