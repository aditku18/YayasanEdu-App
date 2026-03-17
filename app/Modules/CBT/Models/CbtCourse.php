<?php

namespace App\Modules\CBT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CbtCourse extends Model
{
    use SoftDeletes;

    protected $table = 'cbt_courses';
    
    protected $fillable = [
        'tenant_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'category_id',
        'difficulty_level',
        'duration_hours',
        'is_published',
        'is_free',
        'price',
        'certificate_id',
        'passing_score',
        'created_by'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_free' => 'boolean',
        'price' => 'decimal:2',
        'duration_hours' => 'integer',
        'passing_score' => 'integer'
    ];

    /**
     * Get the category that owns the course.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CbtCourseCategory::class, 'category_id');
    }

    /**
     * Get the certificate for the course.
     */
    public function certificate(): HasOne
    {
        return $this->hasOne(CbtCertificate::class, 'course_id');
    }

    /**
     * Get the creator of the course.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Get all modules for the course.
     */
    public function modules(): HasMany
    {
        return $this->hasMany(CbtModule::class, 'course_id')->orderBy('order_index');
    }

    /**
     * Get all enrollments for the course.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(CbtEnrollment::class, 'course_id');
    }

    /**
     * Get all quizzes for the course.
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(CbtQuiz::class, 'course_id');
    }

    /**
     * Get total lessons count.
     */
    public function getTotalLessonsAttribute(): int
    {
        return $this->modules()->withCount('lessons')->get()->sum('lessons_count');
    }

    /**
     * Get published modules.
     */
    public function publishedModules()
    {
        return $this->modules()->where('is_published', true)->with(['lessons' => function($query) {
            $query->where('is_published', true);
        }]);
    }

    /**
     * Scope a query to only include published courses.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to only include free courses.
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope a query to filter by difficulty.
     */
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    /**
     * Generate unique slug.
     */
    public static function generateSlug($title)
    {
        $slug = \Str::slug($title);
        $count = self::where('slug', 'like', "{$slug}%")->count();
        
        if ($count > 0) {
            $slug = "{$slug}-" . ($count + 1);
        }
        
        return $slug;
    }
}
