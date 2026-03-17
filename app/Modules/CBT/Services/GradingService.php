<?php

namespace App\Modules\CBT\Services;

use App\Modules\CBT\Models\CbtQuizAttempt;
use App\Modules\CBT\Models\CbtQuizAnswer;
use App\Modules\CBT\Models\CbtQuestion;
use App\Modules\CBT\Models\CbtResult;
use App\Modules\CBT\Models\CbtEnrollment;

class GradingService
{
    /**
     * Grade an entire quiz attempt.
     */
    public function gradeAttempt(CbtQuizAttempt $attempt): CbtResult
    {
        $quiz = $attempt->quiz;
        $answers = $attempt->answers()->with('question')->get();
        
        // Grade each answer
        foreach ($answers as $answer) {
            $this->gradeAnswer($answer);
        }
        
        // Calculate result
        $result = CbtResult::calculateFromAttempt($attempt);
        
        // Update enrollment if quiz is part of a course
        $this->updateEnrollmentProgress($attempt, $result);
        
        return $result;
    }

    /**
     * Grade a single answer.
     */
    public function gradeAnswer(CbtQuizAnswer $answer): CbtQuizAnswer
    {
        $question = $answer->question;
        
        // Skip if essay (needs manual grading)
        if ($question->requiresManualGrading()) {
            return $answer;
        }
        
        // Auto-grade objective questions
        $answer->gradeAutomatically();
        
        return $answer->fresh();
    }

    /**
     * Manually grade an essay answer.
     */
    public function gradeEssay(int $answerId, int $score, string $feedback): CbtQuizAnswer
    {
        $answer = CbtQuizAnswer::findOrFail($answerId);
        $question = $answer->question;
        
        // Validate score
        if ($score < 0 || $score > $question->points) {
            throw new \InvalidArgumentException('Score must be between 0 and ' . $question->points);
        }
        
        $graderId = auth()->id() ?? 1;
        
        $answer->gradeManually($score, $feedback, $graderId);
        
        // Re-calculate result if the attempt is already submitted
        if ($answer->attempt->is_completed) {
            $this->recalculateResult($answer->attempt);
        }
        
        return $answer->fresh();
    }

    /**
     * Recalculate result for an attempt.
     */
    public function recalculateResult(CbtQuizAttempt $attempt): CbtResult
    {
        // Delete existing result
        $attempt->result()->delete();
        
        // Re-grade all answers
        $answers = $attempt->answers()->with('question')->get();
        
        foreach ($answers as $answer) {
            $this->gradeAnswer($answer);
        }
        
        // Calculate new result
        return CbtResult::calculateFromAttempt($attempt);
    }

    /**
     * Grade all ungraded essay answers for a quiz.
     */
    public function gradePendingEssays(CbtQuiz $quiz): int
    {
        $count = 0;
        
        $ungradedAnswers = CbtQuizAnswer::whereHas('attempt', function ($query) use ($quiz) {
            $query->where('quiz_id', $quiz->id);
        })
        ->whereHas('question', function ($query) {
            $query->where('question_type', 'essay');
        })
        ->ungraded()
        ->get();
        
        foreach ($ungradedAnswers as $answer) {
            // Default grading - can be customized
            $score = 0;
            $feedback = 'Pending review';
            
            $this->gradeEssay($answer->id, $score, $feedback);
            $count++;
        }
        
        return $count;
    }

    /**
     * Get statistics for a quiz.
     */
    public function getQuizStatistics(CbtQuiz $quiz): array
    {
        $attempts = $quiz->attempts()->completed()->with('result')->get();
        
        if ($attempts->isEmpty()) {
            return [
                'total_attempts' => 0,
                'average_score' => 0,
                'highest_score' => 0,
                'lowest_score' => 0,
                'pass_rate' => 0,
                'average_time' => 0
            ];
        }
        
        $results = $attempts->pluck('result')->filter();
        
        return [
            'total_attempts' => $attempts->count(),
            'average_score' => round($results->avg('percentage'), 2),
            'highest_score' => round($results->max('percentage'), 2),
            'lowest_score' => round($results->min('percentage'), 2),
            'pass_rate' => round(($results->where('is_passed', true)->count() / $results->count()) * 100, 2),
            'average_time' => round($attempts->avg('time_spent_seconds') / 60, 2) // in minutes
        ];
    }

    /**
     * Get question statistics.
     */
    public function getQuestionStatistics(CbtQuestion $question): array
    {
        $answers = CbtQuizAnswer::where('question_id', $question->id)
            ->whereNotNull('is_correct')
            ->get();
        
        if ($answers->isEmpty()) {
            return [
                'total_answers' => 0,
                'correct_percentage' => 0,
                'average_score' => 0
            ];
        }
        
        $correctCount = $answers->where('is_correct', true)->count();
        
        return [
            'total_answers' => $answers->count(),
            'correct_percentage' => round(($correctCount / $answers->count()) * 100, 2),
            'average_score' => round($answers->avg('points_earned'), 2)
        ];
    }

    /**
     * Update enrollment progress after quiz completion.
     */
    protected function updateEnrollmentProgress(CbtQuizAttempt $attempt, CbtResult $result): void
    {
        $quiz = $attempt->quiz;
        $user = $attempt->user;
        
        // Check if this is a required quiz for the course
        $enrollment = CbtEnrollment::forUser($user->id)
            ->forCourse($quiz->course_id)
            ->first();
        
        if (!$enrollment) {
            return;
        }
        
        // Update enrollment status to in_progress if not completed
        if ($enrollment->status === 'enrolled') {
            $enrollment->markAsInProgress();
        }
        
        // Check if course is now completed
        $this->checkCourseCompletion($enrollment);
    }

    /**
     * Check if enrollment is now complete.
     */
    protected function checkCourseCompletion(CbtEnrollment $enrollment): void
    {
        $course = $enrollment->course;
        
        // Check if all lessons are completed
        if ($enrollment->progress_percentage >= 100) {
            $enrollment->markAsCompleted();
            
            // Issue certificate if available
            if ($course->certificate) {
                $certificateService = app(CertificateService::class);
                $certificateService->issueCertificate(
                    $enrollment->user_id,
                    $course
                );
            }
        }
    }

    /**
     * Bulk grade multiple essay answers.
     */
    public function bulkGradeEssays(array $grades): int
    {
        $count = 0;
        
        foreach ($grades as $gradeData) {
            if (isset($gradeData['answer_id']) && isset($gradeData['score'])) {
                $this->gradeEssay(
                    $gradeData['answer_id'],
                    $gradeData['score'],
                    $gradeData['feedback'] ?? ''
                );
                $count++;
            }
        }
        
        return $count;
    }
}
