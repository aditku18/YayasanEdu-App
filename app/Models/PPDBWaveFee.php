<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PPDBWaveFee extends Model
{
    protected $fillable = [
        'ppdb_wave_id',
        'ppdb_fee_component_id',
        'amount',
    ];

    public function component()
    {
        return $this->belongsTo(PPDBFeeComponent::class, 'ppdb_fee_component_id');
    }
}
