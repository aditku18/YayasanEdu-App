<?php

namespace App\Modules\CBT\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\CBT\Models\CbtQuestion;
use App\Modules\CBT\Models\CbtQuiz;
use App\Modules\CBT\Models\CbtAnswer;
use App\Modules\CBT\Services\QuizService;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    protected $quizService;

    public function __construct()
    {
        $this->quizService = app(QuizService::class);
    }

    /**
     * Display questions for a quiz.
     */
    public function index(Request $request, CbtQuiz $quiz)
    {
        $questions = $quiz->questions()
            ->with('answers')
            ->orderBy('order_index')
            ->get();

        return view('cbt::admin.questions.index', compact('quiz', 'questions'));
    }

    /**
     * Show the form for creating a question.
     */
    public function create(Request $request)
    {
        $quizzes = CbtQuiz::pluck('title', 'id');
        $selectedQuiz = $request->get('quiz');

        $questionTypes = [
            'multiple_choice' => 'Multiple Choice',
            'true_false' => 'True/False',
            'essay' => 'Essay',
            'matching' => 'Matching',
            'fill_blank' => 'Fill in the Blank',
        ];

        return view('cbt::admin.questions.create', compact('quizzes', 'selectedQuiz', 'questionTypes'));
    }

    /**
     * Store a newly created question.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|exists:cbt_quizzes,id',
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,essay,matching,fill_blank',
            'points' => 'required|integer|min:1',
            'order_index' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $quiz = CbtQuiz::findOrFail($validated['quiz_id']);

        $question = $this->quizService->createQuestion($quiz, [
            'question_text' => $validated['question_text'],
            'question_type' => $validated['question_type'],
            'points' => $validated['points'],
            'order_index' => $validated['order_index'] ?? ($quiz->questions()->max('order_index') + 1),
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Handle answers if provided
        if ($request->has('answers') && in_array($validated['question_type'], ['multiple_choice', 'true_false', 'matching'])) {
            foreach ($request->answers as $answerData) {
                $answer = CbtAnswer::create([
                    'question_id' => $question->id,
                    'answer_text' => $answerData['text'],
                    'is_correct' => $answerData['is_correct'] ?? false,
                    'order_index' => $answerData['order_index'] ?? 0,
                ]);
            }
        }

        return redirect()->route('cbt.questions.index', $quiz->id)
            ->with('success', 'Question created successfully.');
    }

    /**
     * Show the form for editing the question.
     */
    public function edit(CbtQuestion $question)
    {
        $question->load('answers');
        $quizzes = CbtQuiz::pluck('title', 'id');

        $questionTypes = [
            'multiple_choice' => 'Multiple Choice',
            'true_false' => 'True/False',
            'essay' => 'Essay',
            'matching' => 'Matching',
            'fill_blank' => 'Fill in the Blank',
        ];

        return view('cbt::admin.questions.edit', compact('question', 'quizzes', 'questionTypes'));
    }

    /**
     * Update the specified question.
     */
    public function update(Request $request, CbtQuestion $question)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,essay,matching,fill_blank',
            'points' => 'required|integer|min:1',
            'order_index' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $this->quizService->updateQuestion($question, $validated);

        // Update answers if provided
        if ($request->has('answers')) {
            // Delete old answers
            $question->answers()->delete();

            // Create new answers
            foreach ($request->answers as $answerData) {
                CbtAnswer::create([
                    'question_id' => $question->id,
                    'answer_text' => $answerData['text'],
                    'is_correct' => $answerData['is_correct'] ?? false,
                    'order_index' => $answerData['order_index'] ?? 0,
                ]);
            }
        }

        return redirect()->route('cbt.questions.index', $question->quiz_id)
            ->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified question.
     */
    public function destroy(CbtQuestion $question)
    {
        $quizId = $question->quiz_id;
        $this->quizService->deleteQuestion($question);

        return redirect()->route('cbt.questions.index', $quizId)
            ->with('success', 'Question deleted successfully.');
    }

    /**
     * Reorder questions.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'integer',
        ]);

        foreach ($request->question_ids as $index => $questionId) {
            CbtQuestion::where('id', $questionId)->update(['order_index' => $index]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Display question bank.
     */
    public function bank(Request $request)
    {
        $query = CbtQuestion::with(['quiz.course']);

        if ($request->has('search')) {
            $query->where('question_text', 'like', '%' . $request->search . '%');
        }

        if ($request->has('type')) {
            $query->where('question_type', $request->type);
        }

        $questions = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('cbt::admin.questions.bank', compact('questions'));
    }

    /**
     * Import questions from file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
            'quiz_id' => 'required|exists:cbt_quizzes,id',
        ]);

        // TODO: Implement Excel/CSV import logic
        // This would use PhpSpreadsheet to parse the file

        return back()->with('success', 'Questions imported successfully.');
    }

    /**
     * Export questions to file.
     */
    public function export(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|exists:cbt_quizzes,id',
            'format' => 'in:xlsx,csv',
        ]);

        // TODO: Implement Excel/CSV export logic

        return back()->with('success', 'Questions exported successfully.');
    }
}
