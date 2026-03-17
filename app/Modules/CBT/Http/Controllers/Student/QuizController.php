<?php

namespace App\Modules\CBT\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Modules\CBT\Models\CbtQuiz;
use App\Modules\CBT\Models\CbtQuizAttempt;
use App\Modules\CBT\Services\QuizService;
use App\Modules\CBT\Services\GradingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Start a quiz attempt.
     */
    public function start(CbtQuiz $quiz)
    {
        $userId = auth()->id();

        // Check if user can take the quiz
        if (!$this->quizService->canUserTakeQuiz($userId, $quiz)) {
            return back()->with('error', 'You cannot take this quiz at this time.');
        }

        // Check max attempts
        $attempts = CbtQuizAttempt::where('user_id', $userId)
            ->where('quiz_id', $quiz->id)
            ->where('status', 'completed')
            ->count();

        if ($quiz->max_attempts && $attempts >= $quiz->max_attempts) {
            return back()->with('error', 'You have reached the maximum number of attempts for this quiz.');
        }

        // Start new attempt
        $attempt = $this->quizService->startAttempt($userId, $quiz);

        return redirect()->route('cbt.learn.quizzes.show', $attempt->id);
    }

    /**
     * Display quiz taking page.
     */
    public function show(CbtQuizAttempt $attempt)
    {
        $userId = auth()->id();

        // Verify ownership
        if ($attempt->user_id !== $userId) {
            abort(403);
        }

        $attempt->load(['quiz.questions.answers', 'answers']);

        // Get questions for the attempt
        $questions = $this->quizService->getQuestionsForAttempt($attempt->quiz);

        // Check time limit
        $timeLimit = $attempt->quiz->time_limit;
        $startTime = $attempt->started_at;
        $timeRemaining = null;

        if ($timeLimit) {
            $endTime = $startTime->addMinutes($timeLimit);
            $timeRemaining = now()->diffInMinutes($endTime, false);

            if ($timeRemaining <= 0) {
                // Auto-submit if time is up
                return $this->submit($attempt->id);
            }
        }

        return view('cbt::student.quizzes.take', compact('attempt', 'questions', 'timeRemaining'));
    }

    /**
     * Save answer during quiz.
     */
    public function answer(Request $request, CbtQuizAttempt $attempt)
    {
        $userId = auth()->id();

        // Verify ownership
        if ($attempt->user_id !== $userId) {
            abort(403);
        }

        // Check if quiz is still in progress
        if ($attempt->status !== 'in_progress') {
            return back()->with('error', 'This quiz attempt is no longer active.');
        }

        $validated = $request->validate([
            'question_id' => 'required|exists:cbt_questions,id',
            'answer_id' => 'nullable|exists:cbt_answers,id',
            'answer_text' => 'nullable|string',
        ]);

        $this->quizService->saveAnswer(
            $attempt,
            $validated['question_id'],
            $validated['answer_id'] ?? null,
            $validated['answer_text'] ?? null
        );

        return response()->json(['success' => true]);
    }

    /**
     * Submit quiz attempt.
     */
    public function submit(CbtQuizAttempt $attempt)
    {
        $userId = auth()->id();

        // Verify ownership
        if ($attempt->user_id !== $userId) {
            abort(403);
        }

        // Check if already submitted
        if ($attempt->status === 'completed') {
            return redirect()->route('cbt.learn.results.show', $attempt->id);
        }

        // Grade the attempt
        $result = $this->gradingService->gradeAttempt($attempt);

        return redirect()->route('cbt.learn.results.show', $attempt->id)
            ->with('success', 'Quiz submitted successfully!');
    }

    /**
     * Display quiz result.
     */
    public function result(CbtQuizAttempt $attempt)
    {
        $userId = auth()->id();

        // Verify ownership
        if ($attempt->user_id !== $userId) {
            abort(403);
        }

        $attempt->load([
            'quiz.course',
            'answers.question',
            'answers.answer',
            'result'
        ]);

        return view('cbt::student.quizzes.result', compact('attempt'));
    }
}
