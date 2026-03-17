<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('api_integrations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('api_key', 255);
            $table->string('api_secret', 255)->nullable();
            $table->string('base_url', 255);
            $table->string('webhook_url', 255)->nullable();
            $table->string('version', 20)->default('v1');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->boolean('is_active')->default(true);
            $table->enum('type', ['payment_gateway', 'notification', 'analytics', 'storage', 'other'])->default('other');
            $table->json('config')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->integer('sync_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->index(['status', 'type']);
            $table->index(['is_active', 'last_sync_at']);
            $table->unique(['api_key']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_integrations');
    }
};
