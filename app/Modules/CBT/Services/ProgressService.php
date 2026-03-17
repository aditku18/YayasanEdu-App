<?php

namespace App\Modules\CBT\Services;

use App\Modules\CBT\Models\CbtEnrollment;
use App\Modules\CBT\Models\CbtLessonProgress;
use App\Modules\CBT\Models\CbtCourse;
use App\Modules\CBT\Models\CbtLesson;
use App\Models\User;

class ProgressService
{
    /**
     * Mark a lesson as completed.
     */
    public function completeLesson(int $userId, int $lessonId): CbtLessonProgress
    {
        $lesson = CbtLesson::findOrFail($lessonId);
        
        $progress = CbtLessonProgress::firstOrNew([
            'user_id' => $userId,
            'lesson_id' => $lessonId
        ]);
        
        $progress->is_completed = true;
        $progress->last_accessed_at = now();
        $progress->save();
        
        // Update enrollment progress
        $this->updateEnrollmentProgress($userId, $lesson->course_id);
        
        return $progress;
    }

    /**
     * Update lesson progress with time spent.
     */
    public function updateLessonProgress(int $userId, int $lessonId, int $timeSpentMinutes): CbtLessonProgress
    {
        $progress = CbtLessonProgress::firstOrNew([
            'user_id' => $userId,
            'lesson_id' => $lessonId
        ]);
        
        $progress->time_spent_minutes = $progress->time_spent_minutes + $timeSpentMinutes;
        $progress->last_accessed_at = now();
        $progress->save();
        
        return $progress;
    }

    /**
     * Get lesson progress for a user.
     */
    public function getLessonProgress(int $userId, int $lessonId): ?CbtLessonProgress
    {
        return CbtLessonProgress::where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->first();
    }

    /**
     * Get all lesson progress for a user in a course.
     */
    public function getCourseProgress(int $userId, int $courseId)
    {
        return CbtLessonProgress::forUser($userId)
            ->forCourse($courseId)
            ->with('lesson')
            ->get();
    }

    /**
     * Get course progress percentage.
     */
    public function getCourseProgressPercentage(int $userId, int $courseId): int
    {
        $course = CbtCourse::findOrFail($courseId);
        
        $totalLessons = $course->modules()
            ->withCount('lessons')
            ->get()
            ->sum('lessons_count');
        
        if ($totalLessons === 0) {
            return 0;
        }
        
        $completedLessons = CbtLessonProgress::forUser($userId)
            ->forCourse($courseId)
            ->completed()
            ->count();
        
        return round(($completedLessons / $totalLessons) * 100);
    }

    /**
     * Get user's enrolled courses with progress.
     */
    public function getUserEnrollments(int $userId)
    {
        return CbtEnrollment::forUser($userId)
            ->with('course')
            ->orderBy('enrolled_at', 'desc')
            ->get()
            ->map(function ($enrollment) {
                $enrollment->progress_percentage = $this->getCourseProgressPercentage(
                    $enrollment->user_id,
                    $enrollment->course_id
                );
                
                return $enrollment;
            });
    }

    /**
     * Get user's completed courses.
     */
    public function getCompletedCourses(int $userId)
    {
        return CbtEnrollment::forUser($userId)
            ->completed()
            ->with('course')
            ->get();
    }

    /**
     * Get user's in-progress courses.
     */
    public function getInProgressCourses(int $userId)
    {
        return CbtEnrollment::forUser($userId)
            ->inProgress()
            ->orWhere(function ($query) use ($userId) {
                $query->forUser($userId)->where('status', 'enrolled');
            })
            ->with('course')
            ->get();
    }

    /**
     * Get next lesson in course.
     */
    public function getNextLesson(int $userId, int $courseId): ?CbtLesson
    {
        $course = CbtCourse::findOrFail($courseId);
        
        // Get all lessons in order
        $lessons = $course->modules()
            ->published()
            ->with(['lessons' => function ($query) {
                $query->published();
            }])
            ->get()
            ->pluck('lessons')
            ->flatten();
        
        // Find first uncompleted lesson
        foreach ($lessons as $lesson) {
            $progress = $this->getLessonProgress($userId, $lesson->id);
            
            if (!$progress || !$progress->is_completed) {
                return $lesson;
            }
        }
        
        return null;
    }

    /**
     * Update enrollment progress status.
     */
    protected function updateEnrollmentProgress(int $userId, int $courseId): void
    {
        $enrollment = CbtEnrollment::forUser($userId)
            ->forCourse($courseId)
            ->first();
        
        if (!$enrollment) {
            return;
        }
        
        $progressPercentage = $this->getCourseProgressPercentage($userId, $courseId);
        
        if ($progressPercentage > 0 && $enrollment->status === 'enrolled') {
            $enrollment->update(['status' => 'in_progress']);
        }
        
        if ($progressPercentage >= 100) {
            $enrollment->markAsCompleted();
        }
    }

    /**
     * Get total learning time for a user.
     */
    public function getTotalLearningTime(int $userId): int
    {
        return CbtLessonProgress::forUser($userId)
            ->sum('time_spent_minutes');
    }

    /**
     * Get learning time for a specific course.
     */
    public function getCourseLearningTime(int $userId, int $courseId): int
    {
        return CbtLessonProgress::forUser($userId)
            ->forCourse($courseId)
            ->sum('time_spent_minutes');
    }

    /**
     * Get recent activity for a user.
     */
    public function getRecentActivity(int $userId, int $limit = 10)
    {
        return CbtLessonProgress::forUser($userId)
            ->with(['lesson.module.course'])
            ->orderBy('last_accessed_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Mark course as completed (manual).
     */
    public function markCourseCompleted(int $userId, int $courseId): CbtEnrollment
    {
        $enrollment = CbtEnrollment::forUser($userId)
            ->forCourse($courseId)
            ->firstOrFail();
        
        $enrollment->markAsCompleted();
        
        return $enrollment;
    }

    /**
     * Reset course progress.
     */
    public function resetCourseProgress(int $userId, int $courseId): bool
    {
        CbtLessonProgress::forUser($userId)
            ->forCourse($courseId)
            ->delete();
        
        $enrollment = CbtEnrollment::forUser($userId)
            ->forCourse($courseId)
            ->first();
        
        if ($enrollment) {
            $enrollment->update([
                'status' => 'enrolled',
                'completed_at' => null
            ]);
        }
        
        return true;
    }
}
