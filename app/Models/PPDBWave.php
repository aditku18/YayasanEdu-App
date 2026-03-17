<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PPDBWave extends Model
{
    protected $fillable = [
        'school_unit_id',
        'academic_year_id',
        'major_id',
        'name',
        'start_date',
        'end_date',
        'status',
        'description',
        'registration_fee',
        'quota',
    ];

    public function fees()
    {
        return $this->hasMany(PPDBWaveFee::class, 'ppdb_wave_id');
    }

    public function major()
    {
        return $this->belongsTo(\App\Models\Major::class, 'major_id');
    }
}
