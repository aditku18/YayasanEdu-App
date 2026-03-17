<?php

namespace App\Modules\CBT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CbtLesson extends Model
{
    use SoftDeletes;

    protected $table = 'cbt_lessons';
    
    protected $fillable = [
        'module_id',
        'title',
        'content_type',
        'content',
        'video_url',
        'attachment_url',
        'duration_minutes',
        'order_index',
        'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'order_index' => 'integer',
        'duration_minutes' => 'integer'
    ];

    /**
     * Get the module that owns the lesson.
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(CbtModule::class, 'module_id');
    }

    /**
     * Get the course that owns the lesson.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(CbtCourse::class, 'course_id');
    }

    /**
     * Get the quiz associated with this lesson.
     */
    public function quiz(): HasOne
    {
        return $this->hasOne(CbtQuiz::class, 'lesson_id');
    }

    /**
     * Get all progress records for this lesson.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(CbtLessonProgress::class, 'lesson_id');
    }

    /**
     * Check if this lesson has a quiz.
     */
    public function hasQuiz(): bool
    {
        return $this->content_type === 'quiz';
    }

    /**
     * Scope a query to only include published lessons.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to filter by content type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('content_type', $type);
    }

    /**
     * Reorder lessons.
     */
    public static function reorder($moduleId, $lessonIds)
    {
        foreach ($lessonIds as $index => $lessonId) {
            self::where('id', $lessonId)->update(['order_index' => $index]);
        }
    }

    /**
     * Get the next lesson in the module.
     */
    public function getNextLesson()
    {
        return self::where('module_id', $this->module_id)
            ->where('order_index', '>', $this->order_index)
            ->published()
            ->orderBy('order_index')
            ->first();
    }

    /**
     * Get the previous lesson in the module.
     */
    public function getPreviousLesson()
    {
        return self::where('module_id', $this->module_id)
            ->where('order_index', '<', $this->order_index)
            ->published()
            ->orderBy('order_index', 'desc')
            ->first();
    }
}
