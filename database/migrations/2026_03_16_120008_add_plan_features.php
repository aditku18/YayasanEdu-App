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
            $table->boolean('is_active')->default(true)->after('features');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->integer('sort_order')->default(1)->after('is_featured');
            $table->boolean('has_cbt')->default(false)->after('sort_order');
            $table->boolean('has_online_course')->default(false)->after('has_cbt');
            $table->boolean('has_digital_wallet')->default(false)->after('has_online_course');
            $table->boolean('has_canteen')->default(false)->after('has_digital_wallet');
            $table->boolean('has_custom_domain')->default(false)->after('has_canteen');
            $table->boolean('has_api_access')->default(false)->after('has_custom_domain');
            $table->integer('storage_gb')->default(0)->after('has_api_access');
            $table->boolean('has_email_support')->default(true)->after('storage_gb');
            $table->boolean('has_priority_support')->default(false)->after('has_email_support');
            $table->boolean('has_sms_notification')->default(false)->after('has_priority_support');
            $table->text('highlight_features')->nullable()->after('bundle_savings');
            $table->integer('duration_days')->default(30)->after('highlight_features');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'is_active',
                'is_featured',
                'sort_order',
                'has_cbt',
                'has_online_course',
                'has_digital_wallet',
                'has_canteen',
                'has_custom_domain',
                'has_api_access',
                'storage_gb',
                'has_email_support',
                'has_priority_support',
                'has_sms_notification',
                'highlight_features',
                'duration_days'
            ]);
        });
    }
};
