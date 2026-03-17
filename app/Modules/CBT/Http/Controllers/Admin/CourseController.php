<?php

namespace App\Modules\CBT\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\CBT\Models\CbtCourse;
use App\Modules\CBT\Models\CbtModule;
use App\Modules\CBT\Models\CbtLesson;
use App\Modules\CBT\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Display a listing of courses.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category_id', 'difficulty', 'published']);
        
        $courses = $this->courseService->getCourses($filters);
        
        return view('cbt::admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        return view('cbt::admin.courses.create');
    }

    /**
     * Store a newly created course.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:cbt_course_categories,id',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'duration_hours' => 'nullable|integer|min:0',
            'is_free' => 'boolean',
            'price' => 'nullable|numeric|min:0',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        $course = $this->courseService->createCourse($validated);

        return redirect()->route('cbt.admin.courses.show', $course->id)
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified course.
     */
    public function show(int $id)
    {
        $course = CbtCourse::with(['modules.lessons', 'category', 'creator'])->findOrFail($id);
        
        return view('cbt::admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(int $id)
    {
        $course = CbtCourse::findOrFail($id);
        
        return view('cbt::admin.courses.edit', compact('course'));
    }

    /**
     * Update the specified course.
     */
    public function update(Request $request, int $id)
    {
        $course = CbtCourse::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:cbt_course_categories,id',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'duration_hours' => 'nullable|integer|min:0',
            'is_free' => 'boolean',
            'price' => 'nullable|numeric|min:0',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        $this->courseService->updateCourse($course, $validated);

        return redirect()->route('cbt.admin.courses.show', $course->id)
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course.
     */
    public function destroy(int $id)
    {
        $course = CbtCourse::findOrFail($id);
        
        $this->courseService->deleteCourse($course);

        return redirect()->route('cbt.admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    /**
     * Publish the specified course.
     */
    public function publish(int $id)
    {
        $course = CbtCourse::findOrFail($id);
        
        $this->courseService->publishCourse($course);

        return back()->with('success', 'Course published successfully.');
    }

    /**
     * Unpublish the specified course.
     */
    public function unpublish(int $id)
    {
        $course = CbtCourse::findOrFail($id);
        
        $this->courseService->unpublishCourse($course);

        return back()->with('success', 'Course unpublished successfully.');
    }

    /**
     * Duplicate the specified course.
     */
    public function duplicate(Request $request, int $id)
    {
        $course = CbtCourse::findOrFail($id);
        
        $newTitle = $request->input('title', $course->title . ' (Copy)');
        
        $newCourse = $this->courseService->duplicateCourse($course, $newTitle);

        return redirect()->route('cbt.admin.courses.edit', $newCourse->id)
            ->with('success', 'Course duplicated successfully.');
    }

    /**
     * Store a new module.
     */
    public function storeModule(Request $request, int $courseId)
    {
        $course = CbtCourse::findOrFail($courseId);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $module = $this->courseService->createModule($course, $validated);

        return back()->with('success', 'Module created successfully.');
    }

    /**
     * Update a module.
     */
    public function updateModule(Request $request, int $moduleId)
    {
        $module = CbtModule::findOrFail($moduleId);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $module->update($validated);

        return back()->with('success', 'Module updated successfully.');
    }

    /**
     * Delete a module.
     */
    public function destroyModule(int $moduleId)
    {
        $module = CbtModule::findOrFail($moduleId);
        
        $module->delete();

        return back()->with('success', 'Module deleted successfully.');
    }

    /**
     * Reorder modules.
     */
    public function reorderModules(Request $request, int $courseId)
    {
        $course = CbtCourse::findOrFail($courseId);
        
        $moduleIds = $request->input('module_ids', []);
        
        $this->courseService->reorderModules($course, $moduleIds);

        return back()->with('success', 'Modules reordered successfully.');
    }

    /**
     * Store a new lesson.
     */
    public function storeLesson(Request $request, int $moduleId)
    {
        $module = CbtModule::findOrFail($moduleId);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content_type' => 'required|in:video,text,document,quiz,assignment',
            'content' => 'nullable|string',
            'video_url' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:0'
        ]);

        $lesson = $this->courseService->createLesson($module, $validated);

        return back()->with('success', 'Lesson created successfully.');
    }

    /**
     * Update a lesson.
     */
    public function updateLesson(Request $request, int $lessonId)
    {
        $lesson = CbtLesson::findOrFail($lessonId);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content_type' => 'required|in:video,text,document,quiz,assignment',
            'content' => 'nullable|string',
            'video_url' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:0'
        ]);

        $lesson->update($validated);

        return back()->with('success', 'Lesson updated successfully.');
    }

    /**
     * Delete a lesson.
     */
    public function destroyLesson(int $lessonId)
    {
        $lesson = CbtLesson::findOrFail($lessonId);
        
        $lesson->delete();

        return back()->with('success', 'Lesson deleted successfully.');
    }
}
