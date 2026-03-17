<?php

namespace App\Modules\CBT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CbtQuiz extends Model
{
    use SoftDeletes;

    protected $table = 'cbt_quizzes';
    
    protected $fillable = [
        'lesson_id',
        'course_id',
        'title',
        'description',
        'quiz_type',
        'time_limit_minutes',
        'attempt_limit',
        'shuffle_questions',
        'shuffle_answers',
        'show_correct_answers',
        'passing_score',
        'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'shuffle_questions' => 'boolean',
        'shuffle_answers' => 'boolean',
        'show_correct_answers' => 'boolean',
        'time_limit_minutes' => 'integer',
        'attempt_limit' => 'integer',
        'passing_score' => 'integer'
    ];

    /**
     * Get the course that owns the quiz.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(CbtCourse::class, 'course_id');
    }

    /**
     * Get the lesson associated with this quiz.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CbtLesson::class, 'lesson_id');
    }

    /**
     * Get all questions for the quiz.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(CbtQuestion::class, 'quiz_id')->orderBy('order_index');
    }

    /**
     * Get active questions only.
     */
    public function activeQuestions()
    {
        return $this->questions()->where('is_active', true);
    }

    /**
     * Get all attempts for this quiz.
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(CbtQuizAttempt::class, 'quiz_id');
    }

    /**
     * Get the best result for a user.
     */
    public function userBestResult($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->where('is_completed', true)
            ->with('result')
            ->get()
            ->pluck('result')
            ->filter()
            ->sortByDesc('percentage')
            ->first();
    }

    /**
     * Get user's attempt count.
     */
    public function userAttemptCount($userId): int
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->count();
    }

    /**
     * Check if user can take the quiz.
     */
    public function canTake($userId): bool
    {
        if ($this->attempt_limit === 0) {
            return true;
        }
        
        return $this->userAttemptCount($userId) < $this->attempt_limit;
    }

    /**
     * Get total points of all questions.
     */
    public function getTotalPointsAttribute(): int
    {
        return $this->questions()->sum('points');
    }

    /**
     * Scope a query to only include published quizzes.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to filter by quiz type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('quiz_type', $type);
    }

    /**
     * Get shuffled questions if enabled.
     */
    public function getQuestionsForAttempt()
    {
        $query = $this->activeQuestions();
        
        if ($this->shuffle_questions) {
            return $query->inRandomOrder()->get();
        }
        
        return $query->get();
    }
}
