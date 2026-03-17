<?php

namespace App\Modules\CBT\Services;

use App\Modules\CBT\Models\CbtQuiz;
use App\Modules\CBT\Models\CbtQuestion;
use App\Modules\CBT\Models\CbtAnswer;
use App\Modules\CBT\Models\CbtQuizAttempt;
use App\Modules\CBT\Models\CbtQuizAnswer;
use App\Modules\CBT\Models\CbtResult;
use Illuminate\Support\Facades\DB;

class QuizService
{
    /**
     * Create a new quiz.
     */
    public function createQuiz(array $data): CbtQuiz
    {
        return CbtQuiz::create($data);
    }

    /**
     * Update a quiz.
     */
    public function updateQuiz(CbtQuiz $quiz, array $data): CbtQuiz
    {
        $quiz->update($data);
        
        return $quiz->fresh();
    }

    /**
     * Delete a quiz.
     */
    public function deleteQuiz(CbtQuiz $quiz): bool
    {
        return $quiz->delete();
    }

    /**
     * Publish a quiz.
     */
    public function publishQuiz(CbtQuiz $quiz): CbtQuiz
    {
        $quiz->update(['is_published' => true]);
        
        return $quiz->fresh();
    }

    /**
     * Create a question.
     */
    public function createQuestion(CbtQuiz $quiz, array $data): CbtQuestion
    {
        $data['quiz_id'] = $quiz->id;
        $data['order_index'] = $quiz->questions()->max('order_index') + 1;
        
        return CbtQuestion::create($data);
    }

    /**
     * Update a question.
     */
    public function updateQuestion(CbtQuestion $question, array $data): CbtQuestion
    {
        $question->update($data);
        
        return $question->fresh();
    }

    /**
     * Delete a question.
     */
    public function deleteQuestion(CbtQuestion $question): bool
    {
        return $question->delete();
    }

    /**
     * Create an answer for a question.
     */
    public function createAnswer(CbtQuestion $question, array $data): CbtAnswer
    {
        $data['question_id'] = $question->id;
        $data['order_index'] = $question->answers()->max('order_index') + 1;
        
        return CbtAnswer::create($data);
    }

    /**
     * Update an answer.
     */
    public function updateAnswer(CbtAnswer $answer, array $data): CbtAnswer
    {
        $answer->update($data);
        
        return $answer->fresh();
    }

    /**
     * Delete an answer.
     */
    public function deleteAnswer(CbtAnswer $answer): bool
    {
        return $answer->delete();
    }

    /**
     * Set correct answers for a question.
     */
    public function setCorrectAnswers(CbtQuestion $question, array $answerIds): void
    {
        // First, unset all correct answers
        $question->answers()->update(['is_correct' => false]);
        
        // Then set the new correct answers
        $question->answers()->whereIn('id', $answerIds)->update(['is_correct' => true]);
    }

    /**
     * Start a quiz attempt.
     */
    public function startAttempt(int $userId, CbtQuiz $quiz): CbtQuizAttempt
    {
        // Check if user can take the quiz
        if (!$quiz->canTake($userId)) {
            throw new \Exception('Maximum attempts reached for this quiz.');
        }
        
        return CbtQuizAttempt::startAttempt($userId, $quiz);
    }

    /**
     * Save an answer during quiz attempt.
     */
    public function saveAnswer(CbtQuizAttempt $attempt, int $questionId, ?int $answerId = null, ?string $answerText = null): CbtQuizAnswer
    {
        $question = CbtQuestion::findOrFail($questionId);
        
        // Check if answer already exists
        $existingAnswer = $attempt->answers()
            ->where('question_id', $questionId)
            ->first();
        
        if ($existingAnswer) {
            // Update existing answer
            $data = [
                'answer_id' => $answerId,
                'answer_text' => $answerText
            ];
            
            $existingAnswer->update($data);
            
            return $existingAnswer->fresh();
        }
        
        // Create new answer
        return CbtQuizAttempt::answers()->create([
            'attempt_id' => $attempt->id,
            'question_id' => $questionId,
            'answer_id' => $answerId,
            'answer_text' => $answerText
        ]);
    }

    /**
     * Submit a quiz attempt.
     */
    public function submitAttempt(CbtQuizAttempt $attempt): CbtResult
    {
        // Mark attempt as completed
        $attempt->submit();
        
        // Grade the attempt
        $gradingService = app(GradingService::class);
        
        return $gradingService->gradeAttempt($attempt);
    }

    /**
     * Get quiz questions for taking.
     */
    public function getQuestionsForAttempt(CbtQuiz $quiz): \Illuminate\Database\Eloquent\Collection
    {
        return $quiz->getQuestionsForAttempt();
    }

    /**
     * Get user's attempt history.
     */
    public function getUserAttempts(int $userId, CbtQuiz $quiz)
    {
        return $quiz->attempts()
            ->forUser($userId)
            ->with('result')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get the best result for a user.
     */
    public function getUserBestResult(int $userId, CbtQuiz $quiz): ?CbtResult
    {
        return $quiz->userBestResult($userId);
    }

    /**
     * Check if user can take quiz.
     */
    public function canUserTakeQuiz(int $userId, CbtQuiz $quiz): bool
    {
        return $quiz->canTake($userId);
    }

    /**
     * Reorder questions.
     */
    public function reorderQuestions(CbtQuiz $quiz, array $questionIds): void
    {
        CbtQuestion::reorder($quiz->id, $questionIds);
    }

    /**
     * Import questions from array.
     */
    public function importQuestions(CbtQuiz $quiz, array $questions): int
    {
        $count = 0;
        
        DB::transaction(function () use ($quiz, $questions, &$count) {
            foreach ($questions as $questionData) {
                $answers = $questionData['answers'] ?? [];
                unset($questionData['answers']);
                
                $question = $this->createQuestion($quiz, $questionData);
                
                foreach ($answers as $index => $answerData) {
                    $answer = $this->createAnswer($question, $answerData);
                    
                    if (isset($answerData['is_correct']) && $answerData['is_correct']) {
                        $this->setCorrectAnswers($question, [$answer->id]);
                    }
                }
                
                $count++;
            }
        });
        
        return $count;
    }

    /**
     * Export questions to array.
     */
    public function exportQuestions(CbtQuiz $quiz): array
    {
        $questions = $quiz->questions()->with('answers')->get();
        
        return $questions->toArray();
    }
}
