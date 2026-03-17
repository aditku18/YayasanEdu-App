<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{

    protected $fillable = [
        'school_id',
        'nip',
        'name',
        'gender',
        'phone',
        'email',
        'address',
        'is_active',
    ];

    public function school()
    {
        return $this->belongsTo(SchoolUnit::class, 'school_id');
    }

    public function homeroomClasses()
    {
        return $this->hasMany(ClassRoom::class, 'teacher_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
