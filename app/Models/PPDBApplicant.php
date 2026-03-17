<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPDBApplicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_unit_id',
        'ppdb_wave_id',
        'major_id',
        'registration_number',
        'name',
        'email',
        'phone',
        'nisn',
        'nik',
        'pob',
        'dob',
        'gender',
        'address',
        'previous_school',
        'father_name',
        'mother_name',
        'guardian_name',
        'document_kk',
        'document_akta',
        'document_ijazah',
        'document_foto',
        'payment_proof',
        'final_payment_proof',
        'status',
        'payment_status',
        'total_fee',
        'verified_at',
        'verified_by',
        'final_payment_at',
    ];

    public function wave()
    {
        return $this->belongsTo(PPDBWave::class, 'ppdb_wave_id');
    }

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }
}
