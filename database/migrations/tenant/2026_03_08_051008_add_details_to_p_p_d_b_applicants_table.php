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
        Schema::table('p_p_d_b_applicants', function (Blueprint $table) {
            $table->string('nisn')->nullable()->after('registration_number');
            $table->string('nik')->nullable()->after('nisn');
            $table->string('pob')->nullable()->after('name'); // Place of Birth
            $table->date('dob')->nullable()->after('pob');     // Date of Birth
            $table->string('gender')->nullable()->after('dob');
            $table->text('address')->nullable()->after('gender');
            $table->string('previous_school')->nullable()->after('address');
            $table->string('father_name')->nullable()->after('previous_school');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->string('guardian_name')->nullable()->after('mother_name');
            
            // Documents
            $table->string('document_kk')->nullable()->after('guardian_name');
            $table->string('document_akta')->nullable()->after('document_kk');
            $table->string('document_ijazah')->nullable()->after('document_akta');
            $table->string('document_foto')->nullable()->after('document_ijazah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('p_p_d_b_applicants', function (Blueprint $table) {
            $table->dropColumn([
                'nisn', 'nik', 'pob', 'dob', 'gender', 'address', 
                'previous_school', 'father_name', 'mother_name', 'guardian_name',
                'document_kk', 'document_akta', 'document_ijazah', 'document_foto'
            ]);
        });
    }
};
