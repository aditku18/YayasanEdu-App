<?php

namespace App\Modules\CBT\Services;

use App\Modules\CBT\Models\CbtCourse;
use App\Modules\CBT\Models\CbtEnrollment;
use App\Modules\CBT\Models\CbtQuiz;
use App\Modules\CBT\Models\CbtQuizAttempt;
use App\Modules\CBT\Models\CbtQuizAnswer;
use App\Modules\CBT\Models\CbtLessonProgress;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get dashboard overview statistics.
     */
    public function getOverview(?int $tenantId = null): array
    {
        $query = function($query) use ($tenantId) {
            if ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }
        };
        
        return [
            'total_courses' => CbtCourse::when($tenantId, $query)->count(),
            'published_courses' => CbtCourse::when($tenantId, $query)->published()->count(),
            'total_students' => CbtEnrollment::distinct()->count('user_id'),
            'total_enrollments' => CbtEnrollment::count(),
            'completed_courses' => CbtEnrollment::completed()->count(),
            'certificates_issued' => \App\Modules\CBT\Models\CbtCertificateIssued::count(),
            'average_progress' => $this->getAverageProgress(),
            'average_score' => $this->getAverageScore()
        ];
    }

    /**
     * Get course analytics.
     */
    public function getCourseAnalytics(int $courseId): array
    {
        $course = CbtCourse::findOrFail($courseId);
        
        $enrollments = CbtEnrollment::forCourse($courseId)->get();
        $quizzes = $course->quizzes;
        
        return [
            'course' => $course,
            'total_enrollments' => $enrollments->count(),
            'active_students' => $enrollments->whereIn('status', ['enrolled', 'in_progress'])->count(),
            'completed_students' => $enrollments->completed()->count(),
            'completion_rate' => $enrollments->count() > 0 
                ? round(($enrollments->completed()->count() / $enrollments->count()) * 100, 2) 
                : 0,
            'average_progress' => $this->getCourseAverageProgress($courseId),
            'quiz_count' => $quizzes->count(),
            'total_attempts' => $quizzes->sum(fn($q) => $q->attempts()->completed()->count()),
            'average_score' => $this->getCourseAverageScore($courseId),
            'enrollment_trend' => $this->getEnrollmentTrend($courseId)
        ];
    }

    /**
     * Get quiz analytics.
     */
    public function getQuizAnalytics(int $quizId): array
    {
        $quiz = CbtQuiz::findOrFail($quizId);
        
        $attempts = $quiz->attempts()->completed()->with('result')->get();
        $results = $attempts->pluck('result')->filter();
        
        $questionStats = $quiz->questions()
            ->withCount(['answers' => function ($query) {
                $query->whereNotNull('is_correct');
            }])
            ->get()
            ->map(function ($question) {
                return [
                    'id' => $question->id,
                    'text' => substr($question->question_text, 0, 50) . '...',
                    'type' => $question->question_type,
                    'correct_rate' => $this->getQuestionCorrectRate($question->id)
                ];
            });
        
        return [
            'quiz' => $quiz,
            'total_attempts' => $attempts->count(),
            'unique_students' => $attempts->pluck('user_id')->unique()->count(),
            'average_score' => round($results->avg('percentage'), 2),
            'highest_score' => round($results->max('percentage'), 2),
            'lowest_score' => round($results->min('percentage'), 2),
            'pass_rate' => $results->count() > 0 
                ? round(($results->where('is_passed', true)->count() / $results->count()) * 100, 2) 
                : 0,
            'average_time_minutes' => round($attempts->avg('time_spent_seconds') / 60, 2),
            'grade_distribution' => $this->getGradeDistribution($results),
            'question_statistics' => $questionStats
        ];
    }

    /**
     * Get user analytics.
     */
    public function getUserAnalytics(int $userId): array
    {
        $enrollments = CbtEnrollment::forUser($userId)->with('course')->get();
        
        $totalLearningTime = CbtLessonProgress::forUser($userId)->sum('time_spent_minutes');
        
        $quizAttempts = CbtQuizAttempt::forUser($userId)->completed()->with('result')->get();
        $quizResults = $quizAttempts->pluck('result')->filter();
        
        return [
            'total_courses' => $enrollments->count(),
            'completed_courses' => $enrollments->completed()->count(),
            'in_progress_courses' => $enrollments->inProgress()->count(),
            'total_learning_hours' => round($totalLearningTime / 60, 2),
            'total_quiz_attempts' => $quizAttempts->count(),
            'average_quiz_score' => round($quizResults->avg('percentage'), 2),
            'certificates_earned' => \App\Modules\CBT\Models\CbtCertificateIssued::forUser($userId)->count(),
            'courses' => $enrollments->map(function ($e) {
                return [
                    'course' => $e->course,
                    'progress' => $e->progress_percentage,
                    'status' => $e->status
                ];
            }),
            'recent_activity' => CbtLessonProgress::forUser($userId)
                ->with('lesson.module.course')
                ->orderBy('last_accessed_at', 'desc')
                ->limit(10)
                ->get()
        ];
    }

    /**
     * Get average progress across all enrollments.
     */
    protected function getAverageProgress(): float
    {
        // This would need to be calculated based on actual lesson completion
        $totalLessons = CbtLessonProgress::distinct('lesson_id')->count('lesson_id');
        $completedLessons = CbtLessonProgress::where('is_completed', true)->count();
        
        if ($totalLessons === 0) {
            return 0;
        }
        
        return round(($completedLessons / $totalLessons) * 100, 2);
    }

    /**
     * Get average quiz score.
     */
    protected function getAverageScore(): float
    {
        $avg = CbtQuizAttempt::completed()
            ->with('result')
            ->get()
            ->pluck('result')
            ->filter()
            ->avg('percentage');
        
        return round($avg ?? 0, 2);
    }

    /**
     * Get course average progress.
     */
    protected function getCourseAverageProgress(int $courseId): float
    {
        $course = CbtCourse::findOrFail($courseId);
        
        $totalLessons = $course->modules()
            ->withCount('lessons')
            ->get()
            ->sum('lessons_count');
        
        if ($totalLessons === 0) {
            return 0;
        }
        
        $enrollments = CbtEnrollment::forCourse($courseId)->get();
        
        $totalProgress = 0;
        foreach ($enrollments as $enrollment) {
            $progressService = app(ProgressService::class);
            $totalProgress += $progressService->getCourseProgressPercentage(
                $enrollment->user_id,
                $courseId
            );
        }
        
        return $enrollments->count() > 0 
            ? round($totalProgress / $enrollments->count(), 2) 
            : 0;
    }

    /**
     * Get course average score.
     */
    protected function getCourseAverageScore(int $courseId): float
    {
        $avg = CbtQuiz::where('course_id', $courseId)
            ->with(['attempts' => function ($query) {
                $query->completed()->with('result');
            }])
            ->get()
            ->pluck('attempts')
            ->flatten()
            ->pluck('result')
            ->filter()
            ->avg('percentage');
        
        return round($avg ?? 0, 2);
    }

    /**
     * Get enrollment trend for a course.
     */
    protected function getEnrollmentTrend(int $courseId): array
    {
        return CbtEnrollment::forCourse($courseId)
            ->selectRaw('DATE(enrolled_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get()
            ->toArray();
    }

    /**
     * Get question correct rate.
     */
    protected function getQuestionCorrectRate(int $questionId): float
    {
        $total = CbtQuizAnswer::where('question_id', $questionId)
            ->whereNotNull('is_correct')
            ->count();
        
        if ($total === 0) {
            return 0;
        }
        
        $correct = CbtQuizAnswer::where('question_id', $questionId)
            ->where('is_correct', true)
            ->count();
        
        return round(($correct / $total) * 100, 2);
    }

    /**
     * Get grade distribution.
     */
    protected function getGradeDistribution($results): array
    {
        return [
            'A' => $results->where('grade', 'A')->count(),
            'B' => $results->where('grade', 'B')->count(),
            'C' => $results->where('grade', 'C')->count(),
            'D' => $results->where('grade', 'D')->count(),
            'E' => $results->where('grade', 'E')->count()
        ];
    }

    /**
     * Get top performing students.
     */
    public function getTopStudents(int $limit = 10): array
    {
        return CbtEnrollment::completed()
            ->with('user')
            ->get()
            ->map(function ($e) {
                $progressService = app(ProgressService::class);
                return [
                    'user' => $e->user,
                    'course' => $e->course,
                    'progress' => $progressService->getCourseProgressPercentage(
                        $e->user_id,
                        $e->course_id
                    ),
                    'completed_at' => $e->completed_at
                ];
            })
            ->sortByDesc('progress')
            ->take($limit)
            ->values()
            ->toArray();
    }

    /**
     * Get popular courses.
     */
    public function getPopularCourses(int $limit = 10): array
    {
        return CbtCourse::withCount('enrollments')
            ->published()
            ->orderBy('enrollments_count', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Export analytics data.
     */
    public function exportCourseAnalytics(int $courseId): array
    {
        $course = CbtCourse::findOrFail($courseId);
        
        $enrollments = CbtEnrollment::forCourse($courseId)
            ->with('user')
            ->get();
        
        $data = [];
        
        foreach ($enrollments as $enrollment) {
            $progressService = app(ProgressService::class);
            
            $userQuizzes = CbtQuizAttempt::forUser($enrollment->user_id)
                ->whereHas('quiz', function ($query) use ($courseId) {
                    $query->where('course_id', $courseId);
                })
                ->completed()
                ->with('result')
                ->get();
            
            $data[] = [
                'user' => $enrollment->user->name,
                'email' => $enrollment->user->email,
                'status' => $enrollment->status,
                'progress' => $progressService->getCourseProgressPercentage(
                    $enrollment->user_id,
                    $courseId
                ),
                'enrolled_at' => $enrollment->enrolled_at,
                'completed_at' => $enrollment->completed_at,
                'best_quiz_score' => $userQuizzes->pluck('result')->filter()->max('percentage') ?? 0,
                'total_attempts' => $userQuizzes->count()
            ];
        }
        
        return $data;
    }
}
