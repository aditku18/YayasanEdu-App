<?php

namespace App\Modules\CBT\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\CBT\Models\CbtQuiz;
use App\Modules\CBT\Models\CbtCourse;
use App\Modules\CBT\Services\QuizService;
use App\Modules\CBT\Services\GradingService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    protected $quizService;
    protected $gradingService;

    public function __construct()
    {
        $this->quizService = app(QuizService::class);
        $this->gradingService = app(GradingService::class);
    }

    /**
     * Display a listing of quizzes.
     */
    public function index(Request $request)
    {
        $query = CbtQuiz::with(['course', 'lesson']);

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        $quizzes = $query->orderBy('created_at', 'desc')->paginate(20);
        $courses = CbtCourse::pluck('title', 'id');

        return view('cbt::admin.quizzes.index', compact('quizzes', 'courses'));
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function create(Request $request)
    {
        $courses = CbtCourse::pluck('title', 'id');
        $selectedCourse = $request->get('course_id');

        return view('cbt::admin.quizzes.create', compact('courses', 'selectedCourse'));
    }

    /**
     * Store a newly created quiz.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:cbt_courses,id',
            'lesson_id' => 'nullable|exists:cbt_lessons,id',
            'time_limit' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'max_attempts' => 'nullable|integer|min:1',
            'shuffle_questions' => 'boolean',
            'show_results' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $quiz = $this->quizService->createQuiz($validated);

        return redirect()->route('cbt.quizzes.show', $quiz->id)
            ->with('success', 'Quiz created successfully.');
    }

    /**
     * Display the specified quiz.
     */
    public function show(CbtQuiz $quiz)
    {
        $quiz->load(['course', 'lesson', 'questions', 'attempts.user']);

        return view('cbt::admin.quizzes.show', compact('quiz'));
    }

    /**
     * Show the form for editing the quiz.
     */
    public function edit(CbtQuiz $quiz)
    {
        $courses = CbtCourse::pluck('title', 'id');

        return view('cbt::admin.quizzes.edit', compact('quiz', 'courses'));
    }

    /**
     * Update the specified quiz.
     */
    public function update(Request $request, CbtQuiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:cbt_courses,id',
            'lesson_id' => 'nullable|exists:cbt_lessons,id',
            'time_limit' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'max_attempts' => 'nullable|integer|min:1',
            'shuffle_questions' => 'boolean',
            'show_results' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $this->quizService->updateQuiz($quiz, $validated);

        return redirect()->route('cbt.quizzes.show', $quiz->id)
            ->with('success', 'Quiz updated successfully.');
    }

    /**
     * Remove the specified quiz.
     */
    public function destroy(CbtQuiz $quiz)
    {
        $this->quizService->deleteQuiz($quiz);

        return redirect()->route('cbt.quizzes.index')
            ->with('success', 'Quiz deleted successfully.');
    }

    /**
     * Publish the quiz.
     */
    public function publish(CbtQuiz $quiz)
    {
        $quiz = $this->quizService->publishQuiz($quiz);

        return back()->with('success', 'Quiz published successfully.');
    }

    /**
     * Unpublish the quiz.
     */
    public function unpublish(CbtQuiz $quiz)
    {
        $quiz->update(['is_published' => false]);

        return back()->with('success', 'Quiz unpublished successfully.');
    }

    /**
     * Display quiz attempts.
     */
    public function attempts(CbtQuiz $quiz)
    {
        $attempts = $quiz->attempts()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('cbt::admin.quizzes.attempts', compact('quiz', 'attempts'));
    }

    /**
     * Display attempt details.
     */
    public function showAttempt($attemptId)
    {
        $attempt = \App\Modules\CBT\Models\CbtQuizAttempt::with([
            'user',
            'quiz',
            'answers.question',
            'answers.answer',
            'result'
        ])->findOrFail($attemptId);

        return view('cbt::admin.quizzes.attempt-show', compact('attempt'));
    }

    /**
     * Regrade an attempt.
     */
    public function regrade($attemptId)
    {
        $attempt = \App\Modules\CBT\Models\CbtQuizAttempt::findOrFail($attemptId);
        $result = $this->gradingService->gradeAttempt($attempt);

        return back()->with('success', 'Attempt regraded successfully.');
    }
}
