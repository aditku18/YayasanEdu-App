<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradeComponent extends Model
{
    protected $table = 'grade_components';

    const TYPE_DAILY = 'daily'; // UH (Ulangan Harian)
    const TYPE_ASSIGNMENT = 'assignment'; // Tugas
    const TYPE_MIDTERM = 'midterm'; // UTS
    const TYPE_FINAL = 'final'; // UAS
    const TYPE_PROJECT = 'project'; // Proyek

    protected $fillable = [
        'school_unit_id',
        'name',
        'code',
        'type',
        'weight',
        'max_score',
        'semester',
        'academic_year_id',
        'subject_id',
        'class_room_id',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'integer',
        'max_score' => 'integer',
        'is_active' => 'boolean',
    ];

    public function schoolUnit(): BelongsTo
    {
        return $this->belongsTo(SchoolUnit::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_room_id');
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'grade_component_id');
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_DAILY => 'Ulangan Harian (UH)',
            self::TYPE_ASSIGNMENT => 'Tugas',
            self::TYPE_MIDTERM => 'UTS (Ujian Tengah Semester)',
            self::TYPE_FINAL => 'UAS (Ujian Akhir Semester)',
            self::TYPE_PROJECT => 'Proyek',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return self::getTypes()[$this->type] ?? $this->type;
    }
}
