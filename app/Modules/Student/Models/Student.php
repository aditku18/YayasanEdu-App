<?php

namespace App\Modules\Student\Models;

use App\Core\Base\BaseModel;
use App\Core\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Student Model
 * 
 * Represents a student in the academic system.
 */
class Student extends BaseModel
{
    use HasAuditLog;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'students';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'school_unit_id',
        'classroom_id',
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
        'parent_phone',
        'status',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the school unit that owns the student.
     */
    public function schoolUnit(): BelongsTo
    {
        return $this->belongsTo(\App\Models\SchoolUnit::class, 'school_unit_id');
    }

    /**
     * Get the classroom that contains the student.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(\App\Models\ClassRoom::class, 'classroom_id');
    }

    /**
     * Get the student's attendances.
     */
    public function attendances()
    {
        return $this->hasMany(\App\Models\Attendance::class);
    }

    /**
     * Get the student's grades.
     */
    public function grades()
    {
        return $this->hasMany(\App\Models\Grade::class);
    }

    /**
     * Get classes through many-to-many relationship.
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\ClassRoom::class,
            'student_class',
            'student_id',
            'class_id'
        )->withPivot('academic_year_id')
         ->withTimestamps();
    }

    /**
     * Get the student's invoices.
     */
    public function invoices()
    {
        return $this->hasMany(\App\Models\Finance\Invoice::class);
    }

    /**
     * Get the student's payments.
     */
    public function payments()
    {
        return $this->hasMany(\App\Models\Finance\Payment::class);
    }

    /**
     * Scope to filter by school unit.
     */
    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where('school_unit_id', $schoolId);
    }

    /**
     * Scope to filter active students.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by gender.
     */
    public function scopeGender($query, string $gender)
    {
        return $query->where('gender', $gender);
    }

    /**
     * Get full name with NIS.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->name} ({$this->nis})";
    }

    /**
     * Check if student has outstanding balance.
     */
    public function hasOutstandingBalance(): bool
    {
        return $this->invoices()
            ->whereIn('status', ['unpaid', 'partial', 'overdue'])
            ->exists();
    }

    /**
     * Get total paid amount.
     */
    public function getTotalPaidAttribute(): float
    {
        return $this->payments()
            ->where('status', 'confirmed')
            ->sum('amount');
    }

    /**
     * Get total invoice amount.
     */
    public function getTotalInvoiceAttribute(): float
    {
        return $this->invoices()->sum('final_amount');
    }

    /**
     * Get balance amount.
     */
    public function getBalanceAttribute(): float
    {
        return $this->total_invoice - $this->total_paid;
    }
}
