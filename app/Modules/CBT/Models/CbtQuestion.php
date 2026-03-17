<?php

namespace App\Modules\CBT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CbtQuestion extends Model
{
    use SoftDeletes;

    protected $table = 'cbt_questions';
    
    protected $fillable = [
        'quiz_id',
        'question_type',
        'question_text',
        'media_url',
        'media_type',
        'points',
        'explanation',
        'order_index',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer',
        'points' => 'integer'
    ];

    /**
     * Get the quiz that owns the question.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(CbtQuiz::class, 'quiz_id');
    }

    /**
     * Get all answers for the question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(CbtAnswer::class, 'question_id')->orderBy('order_index');
    }

    /**
     * Get correct answers.
     */
    public function correctAnswers()
    {
        return $this->answers()->where('is_correct', true);
    }

    /**
     * Check if question has multiple correct answers.
     */
    public function hasMultipleCorrectAnswers(): bool
    {
        return $this->correctAnswers()->count() > 1;
    }

    /**
     * Check if question is objective (auto-gradable).
     */
    public function isObjective(): bool
    {
        return in_array($this->question_type, ['multiple_choice', 'true_false', 'drag_drop', 'matching']);
    }

    /**
     * Check if question requires manual grading.
     */
    public function requiresManualGrading(): bool
    {
        return $this->question_type === 'essay';
    }

    /**
     * Get shuffled answers if enabled.
     */
    public function getAnswersForAttempt($shuffle = false)
    {
        if ($shuffle) {
            return $this->answers()->inRandomOrder()->get();
        }
        
        return $this->answers()->get();
    }

    /**
     * Scope a query to only include active questions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by question type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('question_type', $type);
    }

    /**
     * Get questions needing manual grading.
     */
    public function scopeNeedsGrading($query)
    {
        return $query->where('question_type', 'essay');
    }

    /**
     * Reorder questions.
     */
    public static function reorder($quizId, $questionIds)
    {
        foreach ($questionIds as $index => $questionId) {
            self::where('id', $questionId)->update(['order_index' => $index]);
        }
    }
}
