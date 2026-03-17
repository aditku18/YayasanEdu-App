<?php

namespace App\Modules\CBT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CbtModule extends Model
{
    use SoftDeletes;

    protected $table = 'cbt_modules';
    
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order_index',
        'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'order_index' => 'integer'
    ];

    /**
     * Get the course that owns the module.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(CbtCourse::class, 'course_id');
    }

    /**
     * Get all lessons for the module.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(CbtLesson::class, 'module_id')->orderBy('order_index');
    }

    /**
     * Get published lessons.
     */
    public function publishedLessons()
    {
        return $this->lessons()->where('is_published', true);
    }

    /**
     * Get total duration of all lessons in minutes.
     */
    public function getTotalDurationAttribute(): int
    {
        return $this->lessons()->sum('duration_minutes');
    }

    /**
     * Get total lessons count.
     */
    public function getLessonsCountAttribute(): int
    {
        return $this->lessons()->count();
    }

    /**
     * Get total quizzes count.
     */
    public function getQuizzesCountAttribute(): int
    {
        return $this->lessons()->where('content_type', 'quiz')->count();
    }

    /**
     * Scope a query to only include published modules.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Reorder modules.
     */
    public static function reorder($courseId, $moduleIds)
    {
        foreach ($moduleIds as $index => $moduleId) {
            self::where('id', $moduleId)->update(['order_index' => $index]);
        }
    }
}
