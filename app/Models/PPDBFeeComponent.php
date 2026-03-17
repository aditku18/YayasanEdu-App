<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PPDBFeeComponent extends Model
{
    protected $fillable = [
        'school_unit_id',
        'name',
        'slug',
        'description',
        'is_mandatory',
    ];
}
