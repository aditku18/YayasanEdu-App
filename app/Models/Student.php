<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    protected $fillable = [
        'nik',
        'nis',
        'nisn',
        'name',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'father_name',
        'mother_name',
        'guardian_name',
        'parent_name',
        'parent_phone',
        'school_id',
        'classroom_id',
        'status',
    ];

    public function school()
    {
        return $this->belongsTo(SchoolUnit::class, 'school_id');
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'classroom_id');
    }

    public function classes()
    {
        return $this->belongsToMany(ClassRoom::class, 'student_class')
                    ->withPivot('academic_year_id')
                    ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
