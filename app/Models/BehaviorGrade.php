<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BehaviorGrade extends Model
{
    protected $table = 'behavior_grades';
    
    protected $fillable = [
        'school_unit_id',
        'student_id',
        'academic_year_id',
        'aspect',
        'semester',
        'grade',
        'description',
        'entered_by',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
