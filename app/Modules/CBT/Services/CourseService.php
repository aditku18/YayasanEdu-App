<?php

namespace App\Modules\CBT\Services;

use App\Modules\CBT\Models\CbtCourse;
use App\Modules\CBT\Models\CbtModule;
use App\Modules\CBT\Models\CbtLesson;
use App\Modules\CBT\Models\CbtEnrollment;
use App\Modules\CBT\Models\CbtCourseCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseService
{
    /**
     * Create a new course.
     */
    public function createCourse(array $data): CbtCourse
    {
        $data['slug'] = CbtCourse::generateSlug($data['title']);
        $data['created_by'] = auth()->id() ?? 1;
        
        return CbtCourse::create($data);
    }

    /**
     * Update an existing course.
     */
    public function updateCourse(CbtCourse $course, array $data): CbtCourse
    {
        if (isset($data['title']) && $data['title'] !== $course->title) {
            $data['slug'] = CbtCourse::generateSlug($data['title']);
        }
        
        $course->update($data);
        
        return $course->fresh();
    }

    /**
     * Delete a course.
     */
    public function deleteCourse(CbtCourse $course): bool
    {
        return $course->delete();
    }

    /**
     * Publish a course.
     */
    public function publishCourse(CbtCourse $course): CbtCourse
    {
        $course->update(['is_published' => true]);
        
        return $course->fresh();
    }

    /**
     * Unpublish a course.
     */
    public function unpublishCourse(CbtCourse $course): CbtCourse
    {
        $course->update(['is_published' => false]);
        
        return $course->fresh();
    }

    /**
     * Create a new module.
     */
    public function createModule(CbtCourse $course, array $data): CbtModule
    {
        $data['course_id'] = $course->id;
        $data['order_index'] = $course->modules()->max('order_index') + 1;
        
        return CbtModule::create($data);
    }

    /**
     * Update module order.
     */
    public function reorderModules(CbtCourse $course, array $moduleIds): void
    {
        CbtModule::reorder($course->id, $moduleIds);
    }

    /**
     * Create a new lesson.
     */
    public function createLesson(CbtModule $module, array $data): CbtLesson
    {
        $data['module_id'] = $module->id;
        $data['course_id'] = $module->course_id;
        $data['order_index'] = $module->lessons()->max('order_index') + 1;
        
        return CbtLesson::create($data);
    }

    /**
     * Update lesson order.
     */
    public function reorderLessons(CbtModule $module, array $lessonIds): void
    {
        CbtLesson::reorder($module->id, $lessonIds);
    }

    /**
     * Enroll a user in a course.
     */
    public function enrollUser(CbtCourse $course, int $userId): CbtEnrollment
    {
        return CbtEnrollment::firstOrCreate(
            ['user_id' => $userId, 'course_id' => $course->id],
            ['enrolled_at' => now(), 'status' => 'enrolled']
        );
    }

    /**
     * Unenroll a user from a course.
     */
    public function unenrollUser(CbtCourse $course, int $userId): bool
    {
        return CbtEnrollment::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->delete();
    }

    /**
     * Check if user is enrolled in a course.
     */
    public function isEnrolled(CbtCourse $course, int $userId): bool
    {
        return CbtEnrollment::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->exists();
    }

    /**
     * Get user's enrollment for a course.
     */
    public function getEnrollment(CbtCourse $course, int $userId): ?CbtEnrollment
    {
        return CbtEnrollment::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->first();
    }

    /**
     * Get course progress for a user.
     */
    public function getCourseProgress(CbtCourse $course, int $userId): int
    {
        $enrollment = $this->getEnrollment($course, $userId);
        
        if (!$enrollment) {
            return 0;
        }
        
        return $enrollment->progress_percentage;
    }

    /**
     * Get all courses with filters.
     */
    public function getCourses(array $filters = [])
    {
        $query = CbtCourse::with(['category', 'creator']);
        
        if (isset($filters['published'])) {
            $query->published();
        }
        
        if (isset($filters['category_id'])) {
            $query->byCategory($filters['category_id']);
        }
        
        if (isset($filters['difficulty'])) {
            $query->byDifficulty($filters['difficulty']);
        }
        
        if (isset($filters['free'])) {
            $query->free();
        }
        
        if (isset($filters['search'])) {
            $query->where('title', 'like', "%{$filters['search']}%");
        }
        
        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    /**
     * Get course with all relations.
     */
    public function getCourseWithRelations(CbtCourse $course)
    {
        return $course->load(['modules.lessons', 'category', 'certificate']);
    }

    /**
     * Create a course category.
     */
    public function createCategory(array $data): CbtCourseCategory
    {
        $data['slug'] = CbtCourseCategory::generateSlug($data['name'] ?? $data['title']);
        
        return CbtCourseCategory::create($data);
    }

    /**
     * Get all categories.
     */
    public function getCategories(?int $tenantId = null)
    {
        $query = CbtCourseCategory::root()->with('subcategories');
        
        if ($tenantId) {
            $query->forTenant($tenantId);
        }
        
        return $query->ordered()->get();
    }

    /**
     * Duplicate a course.
     */
    public function duplicateCourse(CbtCourse $course, string $newTitle): CbtCourse
    {
        return DB::transaction(function () use ($course, $newTitle) {
            // Create new course
            $newCourse = $course->replicate();
            $newCourse->title = $newTitle;
            $newCourse->slug = CbtCourse::generateSlug($newTitle);
            $newCourse->is_published = false;
            $newCourse->save();
            
            // Copy modules and lessons
            foreach ($course->modules as $module) {
                $newModule = $module->replicate();
                $newModule->course_id = $newCourse->id;
                $newModule->save();
                
                foreach ($module->lessons as $lesson) {
                    $newLesson = $lesson->replicate();
                    $newLesson->module_id = $newModule->id;
                    $newLesson->course_id = $newCourse->id;
                    $newLesson->save();
                }
            }
            
            return $newCourse;
        });
    }
}
