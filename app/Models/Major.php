<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $fillable = [
        'school_id',
        'code',
        'name',
        'abbreviation',
        'head_of_major',
        'description',
        'capacity',
        'status',
    ];

    public function school()
    {
        return $this->belongsTo(SchoolUnit::class, 'school_id');
    }

    public function classRooms()
    {
        return $this->hasMany(ClassRoom::class);
    }
}
