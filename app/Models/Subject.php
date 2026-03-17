<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'school_id',
        'code',
        'name',
        'type',
    ];

    public function school()
    {
        return $this->belongsTo(SchoolUnit::class, 'school_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
