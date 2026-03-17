<?php

namespace App\Modules\CBT\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Modules\CBT\Models\CbtCourse;
use App\Modules\CBT\Models\CbtEnrollment;
use App\Modules\CBT\Models\CbtCertificateIssued;
use App\Modules\CBT\Services\CourseService;
use App\Modules\CBT\Services\ProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    protected $courseService;
    protected $progressService;

    public function __construct()
    {
        $this->courseService = app(CourseService::class);
        $this->progressService = app(ProgressService::class);
    }

    /**
     * Display all available courses.
     */
    public function index(Request $request)
    {
        $query = CbtCourse::with('category')
            ->published()
            ->active();

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $courses = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('cbt::student.courses.index', compact('courses'));
    }

    /**
     * Display public course catalog.
     */
    public function publicIndex(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Display course details.
     */
    public function show(CbtCourse $course)
    {
        $userId = auth()->id();
        $enrollment = $this->courseService->getEnrollment($course, $userId);
        $progress = $enrollment ? $this->progressService->getCourseProgressPercentage($userId, $course->id) : 0;

        $course->load(['modules.lessons', 'certificate']);

        return view('cbt::student.courses.show', compact('course', 'enrollment', 'progress'));
    }

    /**
     * Preview course (public).
     */
    public function preview(CbtCourse $course)
    {
        $course->load(['modules.lessons', 'certificate']);

        return view('cbt::student.courses.preview', compact('course'));
    }

    /**
     * Enroll in a course.
     */
    public function enroll(CbtCourse $course)
    {
        $userId = auth()->id();

        if ($this->courseService->isEnrolled($course, $userId)) {
            return back()->with('info', 'You are already enrolled in this course.');
        }

        $this->courseService->enrollUser($course, $userId);

        return redirect()->route('cbt.learn.courses.show', $course->id)
            ->with('success', 'Successfully enrolled in the course!');
    }

    /**
     * Display lesson content.
     */
    public function lesson($lessonId)
    {
        $lesson = \App\Modules\CBT\Models\CbtLesson::with(['module.course', 'quiz'])
            ->findOrFail($lessonId);

        $userId = auth()->id();
        $progress = $this->progressService->getLessonProgress($userId, $lessonId);

        // Get next lesson
        $nextLesson = \App\Modules\CBT\Models\CbtLesson::where('module_id', $lesson->module_id)
            ->where('order_index', '>', $lesson->order_index)
            ->orderBy('order_index')
            ->first();

        return view('cbt::student.lessons.show', compact('lesson', 'progress', 'nextLesson'));
    }

    /**
     * Mark lesson as complete.
     */
    public function completeLesson($lessonId)
    {
        $userId = auth()->id();

        $this->progressService->completeLesson($userId, $lessonId);

        // Check for next lesson and redirect
        $lesson = \App\Modules\CBT\Models\CbtLesson::findOrFail($lessonId);
        $nextLesson = \App\Modules\CBT\Models\CbtLesson::where('module_id', $lesson->module_id)
            ->where('order_index', '>', $lesson->order_index)
            ->orderBy('order_index')
            ->first();

        if ($nextLesson) {
            return redirect()->route('cbt.learn.lessons.show', $nextLesson->id)
                ->with('success', 'Lesson completed! Moving to next lesson.');
        }

        return back()->with('success', 'Lesson completed!');
    }

    /**
     * Display my enrolled courses.
     */
    public function myCourses()
    {
        $userId = auth()->id();

        $enrollments = $this->progressService->getUserEnrollments($userId)
            ->with('course.category')
            ->get();

        return view('cbt::student.courses.my', compact('enrollments'));
    }

    /**
     * Display my certificates.
     */
    public function myCertificates()
    {
        $userId = auth()->id();

        $certificates = CbtCertificateIssued::forUser($userId)
            ->with('certificate.course')
            ->orderBy('issued_at', 'desc')
            ->get();

        return view('cbt::student.certificates.index', compact('certificates'));
    }
}
