<?php

namespace App\Modules\CBT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CbtQuizAttempt extends Model
{
    use SoftDeletes;

    protected $table = 'cbt_quiz_attempts';
    
    protected $fillable = [
        'user_id',
        'quiz_id',
        'attempt_number',
        'started_at',
        'submitted_at',
        'time_spent_seconds',
        'ip_address',
        'user_agent',
        'is_completed'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'is_completed' => 'boolean',
        'time_spent_seconds' => 'integer',
        'attempt_number' => 'integer'
    ];

    /**
     * Get the user that owns the attempt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Get the quiz that owns the attempt.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(CbtQuiz::class, 'quiz_id');
    }

    /**
     * Get all answers for this attempt.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(CbtQuizAnswer::class, 'attempt_id');
    }

    /**
     * Get the result for this attempt.
     */
    public function result(): HasOne
    {
        return $this->hasOne(CbtResult::class, 'attempt_id');
    }

    /**
     * Start a new attempt.
     */
    public static function startAttempt($userId, $quiz): self
    {
        $attemptNumber = $quiz->userAttemptCount($userId) + 1;
        
        return self::create([
            'user_id' => $userId,
            'quiz_id' => $quiz->id,
            'attempt_number' => $attemptNumber,
            'started_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'is_completed' => false
        ]);
    }

    /**
     * Submit the attempt.
     */
    public function submit(): void
    {
        $this->update([
            'submitted_at' => now(),
            'time_spent_seconds' => $this->started_at->diffInSeconds(now()),
            'is_completed' => true
        ]);
    }

    /**
     * Check if time limit has expired.
     */
    public function isTimeExpired(): bool
    {
        if ($this->quiz->time_limit_minutes === 0) {
            return false;
        }

        $timeLimitSeconds = $this->quiz->time_limit_minutes * 60;
        $elapsedSeconds = $this->started_at->diffInSeconds(now());

        return $elapsedSeconds >= $timeLimitSeconds;
    }

    /**
     * Get remaining time in seconds.
     */
    public function getRemainingSeconds(): int
    {
        if ($this->quiz->time_limit_minutes === 0) {
            return -1; // No limit
        }

        $timeLimitSeconds = $this->quiz->time_limit_minutes * 60;
        $elapsedSeconds = $this->started_at->diffInSeconds(now());
        $remaining = $timeLimitSeconds - $elapsedSeconds;

        return max(0, $remaining);
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to get completed attempts.
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope a query to get in-progress attempts.
     */
    public function scopeInProgress($query)
    {
        return $query->where('is_completed', false);
    }
}
