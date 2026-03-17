@extends('cbt.layouts.master')

@section('title', 'Courses Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Course Management</h2>
    <a href="{{ route('cbt.courses.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Create Course
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search courses..." value="{{ request('search') }}">
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

<div class="row">
    @forelse($courses as $course)
    <div class="col-md-4 mb-4">
        <div class="card course-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge {{ $course->is_published ? 'bg-success' : 'bg-secondary' }}">
                        {{ $course->is_published ? 'Published' : 'Draft' }}
                    </span>
                    @if($course->category)
                    <span class="badge bg-info">{{ $course->category->name }}</span>
                    @endif
                </div>
                
                <h5 class="card-title">{{ $course->title }}</h5>
                <p class="card-text text-muted">{{ Str::limit($course->description, 100) }}</p>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-book me-1"></i> {{ $course->modules_count ?? 0 }} Modules
                        </small>
                    </div>
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-user me-1"></i> {{ $course->enrollments_count ?? 0 }} Students
                        </small>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <div class="btn-group w-100">
                    <a href="{{ route('cbt.courses.show', $course->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="{{ route('cbt.courses.edit', $course->id) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('cbt.courses.destroy', $course->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this course?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info">
            No courses found. <a href="{{ route('cbt.courses.create') }}">Create your first course</a>
        </div>
    </div>
    @endforelse
</div>

{{ $courses->links() }}
@endsection
