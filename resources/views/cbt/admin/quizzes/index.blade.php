@extends('cbt.layouts.master')

@section('title', 'Quiz Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Quiz Management</h2>
    <a href="{{ route('cbt.quizzes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Create Quiz
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="course_id" class="form-select">
                    <option value="">All Courses</option>
                    @foreach($courses as $id => $title)
                        <option value="{{ $id }}" {{ request('course_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Course</th>
                        <th>Questions</th>
                        <th>Time Limit</th>
                        <th>Passing Score</th>
                        <th>Attempts</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quizzes as $quiz)
                    <tr>
                        <td>
                            <strong>{{ $quiz->title }}</strong>
                            @if($quiz->lesson)
                                <br><small class="text-muted">{{ $quiz->lesson->title }}</small>
                            @endif
                        </td>
                        <td>{{ $quiz->course->title ?? '-' }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $quiz->questions_count ?? 0 }}</span>
                        </td>
                        <td>
                            @if($quiz->time_limit)
                                {{ $quiz->time_limit }} min
                            @else
                                <span class="text-muted">No limit</span>
                            @endif
                        </td>
                        <td>{{ $quiz->passing_score }}%</td>
                        <td>
                            {{ $quiz->attempts_count ?? 0 }}
                            @if($quiz->max_attempts)
                                / {{ $quiz->max_attempts }}
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $quiz->is_published ? 'bg-success' : 'bg-secondary' }}">
                                {{ $quiz->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('cbt.quizzes.show', $quiz->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('cbt.quizzes.edit', $quiz->id) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('cbt.quizzes.attempts', $quiz->id) }}" class="btn btn-outline-info">
                                    <i class="fas fa-users"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            No quizzes found. <a href="{{ route('cbt.quizzes.create') }}">Create your first quiz</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{ $quizzes->links() }}
@endsection
