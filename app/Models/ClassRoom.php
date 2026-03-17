<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'level',
        'teacher_id',
    ];

    public function school()
    {
        return $this->belongsTo(SchoolUnit::class, 'school_id');
    }

    public function homeroomTeacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_class')
                    ->withPivot('academic_year_id')
                    ->withTimestamps();
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
