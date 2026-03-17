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
        if (!Schema::hasTable('p_p_d_b_applicants')) {
            Schema::create('p_p_d_b_applicants', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_unit_id');
                $table->unsignedBigInteger('ppdb_wave_id');
                $table->string('registration_number')->unique();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('status')->default('pending'); // pending, verified, accepted, rejected
                $table->string('payment_status')->default('unpaid'); // unpaid, partial, paid
                $table->decimal('total_fee', 15, 2)->default(0);
                $table->timestamp('verified_at')->nullable();
                $table->unsignedBigInteger('verified_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_p_d_b_applicants');
    }
};
