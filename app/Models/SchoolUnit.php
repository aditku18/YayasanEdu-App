<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SchoolUnit extends Model
{
    protected $table = 'school_units';

    const STATUS_DRAFT = 'draft';
    const STATUS_SETUP = 'setup';
    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'foundation_id',
        'name',
        'slug',
        'jenjang',
        'level',
        'npsn',
        'principal_name',
        'principal_email',
        'principal_phone',
        'email',
        'phone',
        'address',
        'province',
        'city',
        'district',
        'postal_code',
        'status',
        'logo',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug on creating
        static::creating(function ($school) {
            if (empty($school->slug)) {
                $school->slug = static::generateUniqueSlug($school->name);
            }
        });

        // Regenerate slug on updating if name changes
        static::updating(function ($school) {
            if ($school->isDirty('name')) {
                $school->slug = static::generateUniqueSlug($school->name, $school->id);
            }
        });
    }

    /**
     * Generate a unique slug from the school name.
     */
    public static function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        $query = static::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $query = static::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'school_id');
    }

    public function majors()
    {
        return $this->hasMany(Major::class, 'school_id');
    }

    public function classRooms()
    {
        return $this->hasMany(ClassRoom::class, 'school_id');
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'school_id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'school_id');
    }
}
