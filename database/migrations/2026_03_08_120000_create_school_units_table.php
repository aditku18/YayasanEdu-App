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
        Schema::create('school_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('foundation_id')->nullable()->constrained('foundations')->nullOnDelete();
            $table->string('name');
            $table->string('jenjang')->nullable();
            $table->string('level')->nullable();
            $table->string('npsn')->nullable();
            $table->string('principal_name')->nullable();
            $table->string('principal_email')->nullable();
            $table->string('principal_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('subdomain')->nullable();
            $table->string('status')->default('draft');
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_units');
    }
};
