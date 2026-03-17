<?php

namespace App\Modules\CBT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CbtResult extends Model
{
    use SoftDeletes;

    protected $table = 'cbt_results';
    
    protected $fillable = [
        'attempt_id',
        'total_points',
        'earned_points',
        'percentage',
        'grade',
        'is_passed',
        'certificate_id'
    ];

    protected $casts = [
        'total_points' => 'decimal:2',
        'earned_points' => 'decimal:2',
        'percentage' => 'decimal:2',
        'is_passed' => 'boolean'
    ];

    /**
     * Get the attempt that owns the result.
     */
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(CbtQuizAttempt::class, 'attempt_id');
    }

    /**
     * Get the certificate (if issued).
     */
    public function certificate(): HasOne
    {
        return $this->hasOne(CbtCertificateIssued::class, 'certificate_id');
    }

    /**
     * Calculate and save result from attempt.
     */
    public static function calculateFromAttempt(CbtQuizAttempt $attempt): self
    {
        $quiz = $attempt->quiz;
        $answers = $attempt->answers()->with('question')->get();
        
        $totalPoints = $answers->sum('question.points');
        $earnedPoints = $answers->sum('points_earned');
        
        $percentage = $totalPoints > 0 
            ? round(($earnedPoints / $totalPoints) * 100, 2) 
            : 0;
        
        $grade = self::calculateGrade($percentage);
        $isPassed = $percentage >= $quiz->passing_score;
        
        return self::create([
            'attempt_id' => $attempt->id,
            'total_points' => $totalPoints,
            'earned_points' => $earnedPoints,
            'percentage' => $percentage,
            'grade' => $grade,
            'is_passed' => $isPassed
        ]);
    }

    /**
     * Calculate grade from percentage.
     */
    public static function calculateGrade(float $percentage): string
    {
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'E';
    }

    /**
     * Check if result is passing.
     */
    public function isPassing(): bool
    {
        return $this->is_passed;
    }

    /**
     * Get grade color for UI.
     */
    public function getGradeColor(): string
    {
        $colors = [
            'A' => 'green',
            'B' => 'blue',
            'C' => 'yellow',
            'D' => 'orange',
            'E' => 'red'
        ];
        
        return $colors[$this->grade] ?? 'gray';
    }

    /**
     * Scope a query to filter by passed status.
     */
    public function scopePassed($query)
    {
        return $query->where('is_passed', true);
    }

    /**
     * Scope a query to filter by failed status.
     */
    public function scopeFailed($query)
    {
        return $query->where('is_passed', false);
    }

    /**
     * Scope a query to filter by grade.
     */
    public function scopeByGrade($query, $grade)
    {
        return $query->where('grade', $grade);
    }
}
