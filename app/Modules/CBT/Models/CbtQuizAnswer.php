<?php

namespace App\Modules\CBT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CbtQuizAnswer extends Model
{
    use SoftDeletes;

    protected $table = 'cbt_quiz_answers';
    
    protected $fillable = [
        'attempt_id',
        'question_id',
        'answer_id',
        'answer_text',
        'is_correct',
        'points_earned',
        'graded_at',
        'graded_by',
        'feedback'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'points_earned' => 'decimal:2',
        'graded_at' => 'datetime'
    ];

    /**
     * Get the attempt that owns the answer.
     */
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(CbtQuizAttempt::class, 'attempt_id');
    }

    /**
     * Get the question that owns the answer.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(CbtQuestion::class, 'question_id');
    }

    /**
     * Get the selected answer.
     */
    public function answer(): BelongsTo
    {
        return $this->belongsTo(CbtAnswer::class, 'answer_id');
    }

    /**
     * Get the grader.
     */
    public function grader(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'graded_by');
    }

    /**
     * Grade the answer automatically (for objective questions).
     */
    public function gradeAutomatically(): void
    {
        $question = $this->question;
        $correctAnswers = $question->correctAnswers()->pluck('id')->toArray();
        
        $isCorrect = in_array($this->answer_id, $correctAnswers);
        $pointsEarned = $isCorrect ? $question->points : 0;
        
        $this->update([
            'is_correct' => $isCorrect,
            'points_earned' => $pointsEarned
        ]);
    }

    /**
     * Manually grade the answer (for essay questions).
     */
    public function gradeManually(int $score, string $feedback, int $graderId): void
    {
        $this->update([
            'points_earned' => $score,
            'feedback' => $feedback,
            'graded_at' => now(),
            'graded_by' => $graderId,
            'is_correct' => $score > 0
        ]);
    }

    /**
     * Check if answer has been graded.
     */
    public function isGraded(): bool
    {
        if ($this->question->requiresManualGrading()) {
            return $this->graded_at !== null;
        }
        
        return $this->is_correct !== null;
    }

    /**
     * Scope a query to filter by attempt.
     */
    public function scopeForAttempt($query, $attemptId)
    {
        return $query->where('attempt_id', $attemptId);
    }

    /**
     * Scope a query to filter by question.
     */
    public function scopeForQuestion($query, $questionId)
    {
        return $query->where('question_id', $questionId);
    }

    /**
     * Scope a query to get ungraded answers.
     */
    public function scopeUngraded($query)
    {
        return $query->whereNull('graded_at');
    }

    /**
     * Scope a query to get correct answers.
     */
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }
}
