<?php

namespace App\Modules\CBT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CbtLessonProgress extends Model
{
    use SoftDeletes;

    protected $table = 'cbt_lesson_progress';
    
    protected $fillable = [
        'user_id',
        'lesson_id',
        'is_completed',
        'time_spent_minutes',
        'last_accessed_at'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'time_spent_minutes' => 'integer',
        'last_accessed_at' => 'datetime'
    ];

    /**
     * Get the user that owns the progress.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Get the lesson that owns the progress.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CbtLesson::class, 'lesson_id');
    }

    /**
     * Mark lesson as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'is_completed' => true,
            'last_accessed_at' => now()
        ]);
    }

    /**
     * Update time spent.
     */
    public function addTimeSpent(int $minutes): void
    {
        $this->update([
            'time_spent_minutes' => $this->time_spent_minutes + $minutes,
            'last_accessed_at' => now()
        ]);
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to get completed lessons.
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope a query to filter by course.
     */
    public function scopeForCourse($query, $courseId)
    {
        return $query->whereHas('lesson', function($q) use ($courseId) {
            $q->where('course_id', $courseId);
        });
    }
}
