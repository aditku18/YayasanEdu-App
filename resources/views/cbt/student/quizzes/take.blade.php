<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taking Quiz - {{ $attempt->quiz->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .quiz-header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .question-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .question-number {
            background: #4f46e5;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
        .answer-option {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .answer-option:hover {
            border-color: #4f46e5;
            background: #f5f3ff;
        }
        .answer-option.selected {
            border-color: #4f46e5;
            background: #e0e7ff;
        }
        .answer-option input {
            display: none;
        }
        .timer-warning {
            animation: pulse 1s infinite;
            background: #fef3c7 !important;
            border-color: #f59e0b !important;
        }
        .timer-danger {
            animation: pulse 0.5s infinite;
            background: #fee2e2 !important;
            border-color: #ef4444 !important;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body>
    @if($timeRemaining !== null)
    <div class="quiz-timer" id="timerDisplay">
        <div class="d-flex align-items-center">
            <i class="fas fa-clock me-2"></i>
            <span id="timerText">{{ $timeRemaining }} min remaining</span>
        </div>
    </div>
    @endif

    <div class="quiz-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">{{ $attempt->quiz->title }}</h5>
                    <small class="text-muted">
                        Question {{ $attempt->answers_count ?? 0 }} of {{ $questions->count() }}
                    </small>
                </div>
                <div>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#submitModal">
                        <i class="fas fa-check me-1"></i> Submit Quiz
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <form id="quizForm">
            @csrf
            @foreach($questions as $index => $question)
            <div class="question-card" id="question-{{ $question->id }}">
                <div class="d-flex align-items-start mb-3">
                    <span class="question-number">{{ $index + 1 }}</span>
                    <div class="flex-grow-1">
                        <p class="mb-2 fw-medium">{{ $question->question_text }}</p>
                        <small class="text-muted">{{ $question->points }} points</small>
                    </div>
                </div>

                @if(in_array($question->question_type, ['multiple_choice', 'true_false']))
                <div class="ms-4">
                    @foreach($question->answers as $answer)
                    <label class="answer-option d-block" for="answer-{{ $answer->id }}">
                        <input type="radio" name="answers[{{ $question->id }}]" 
                               value="{{ $answer->id }}" id="answer-{{ $answer->id }}"
                               {{ isset($attempt->answers[$question->id]) && $attempt->answers[$question->id]->answer_id == $answer->id ? 'checked' : '' }}>
                        <span class="ms-2">{{ $answer->answer_text }}</span>
                    </label>
                    @endforeach
                </div>
                @elseif($question->question_type === 'essay')
                <div class="ms-4">
                    <textarea name="answers[{{ $question->id }}][text]" class="form-control" rows="4" 
                              placeholder="Write your answer here...">{{ isset($attempt->answers[$question->id]) ? $attempt->answers[$question->id]->answer_text : '' }}</textarea>
                </div>
                @endif
            </div>
            @endforeach

            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i> Save Answers
                </button>
            </div>
        </form>
    </div>

    <!-- Submit Confirmation Modal -->
    <div class="modal fade" id="submitModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Submit Quiz?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to submit your quiz?</p>
                    <p class="text-muted">You have answered {{ $attempt->answers_count ?? 0 }} out of {{ $questions->count() }} questions.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue Quiz</button>
                    <form action="{{ route('cbt.learn.quizzes.submit', $attempt->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Yes, Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-save answers every 30 seconds
        setInterval(function() {
            const formData = new FormData(document.getElementById('quizForm'));
            fetch('{{ route('cbt.learn.quizzes.answer', $attempt->id) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => response.json())
              .then(data => {
                  console.log('Auto-saved:', data.success);
              });
        }, 30000);

        // Timer countdown
        @if($timeRemaining !== null)
        let remainingMinutes = {{ $timeRemaining }};
        setInterval(function() {
            remainingMinutes--;
            const display = document.getElementById('timerDisplay');
            const text = document.getElementById('timerText');
            
            if (remainingMinutes <= 5) {
                display.classList.add('timer-danger');
            } else if (remainingMinutes <= 10) {
                display.classList.add('timer-warning');
            }
            
            if (remainingMinutes <= 0) {
                document.getElementById('quizForm').submit();
            } else {
                text.textContent = remainingMinutes + ' min remaining';
            }
        }, 60000);
        @endif

        // Answer selection highlight
        document.querySelectorAll('.answer-option').forEach(option => {
            option.addEventListener('click', function() {
                const parent = this.closest('.question-card');
                parent.querySelectorAll('.answer-option').forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                this.querySelector('input').checked = true;
            });
        });
    </script>
</body>
</html>
