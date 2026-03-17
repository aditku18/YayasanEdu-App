<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Courses - CBT Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 60px 0;
        }
        .course-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }
        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .course-image {
            height: 180px;
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .course-image i {
            font-size: 4rem;
            color: #4f46e5;
        }
        .enrolled-badge {
            position: absolute;
            top: 15px;
            right: 15px;
        }
        .category-badge {
            position: absolute;
            top: 15px;
            left: 15px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-graduation-cap text-primary me-2"></i>CBT Learning
            </a>
            <div class="d-flex align-items-center">
                <a href="{{ route('cbt.learn.courses.my') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-book-open me-1"></i> My Courses
                </a>
                @auth
                    <span class="text-muted">{{ auth()->user()->name }}</span>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">Learn at Your Own Pace</h1>
            <p class="lead mb-4">Access high-quality courses and quizzes to enhance your knowledge</p>
            
            <form method="GET" class="row justify-content-center g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control form-control-lg" 
                           placeholder="Search courses..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-light btn-lg w-100">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
            </form>
        </div>
    </section>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Categories</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('cbt.learn.courses.index') }}" 
                           class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                            All Courses
                        </a>
                        <!-- Add dynamic categories here -->
                    </div>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>{{ $courses->total() }} Courses Available</h4>
                </div>
                
                @if($courses->isEmpty())
                    <div class="alert alert-info">
                        No courses available at the moment. Please check back later.
                    </div>
                @else
                    <div class="row">
                        @foreach($courses as $course)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card course-card h-100 position-relative">
                                <div class="course-image">
                                    <i class="fas fa-book"></i>
                                </div>
                                @if($course->category)
                                <span class="badge bg-primary category-badge">{{ $course->category->name }}</span>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $course->title }}</h5>
                                    <p class="card-text text-muted small">{{ Str::limit($course->description, 80) }}</p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-layer-group me-1"></i> {{ $course->modules_count ?? 0 }} modules
                                        </small>
                                        @if($course->certificate)
                                        <small class="text-success">
                                            <i class="fas fa-certificate me-1"></i> Certificate
                                        </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <a href="{{ route('cbt.learn.courses.show', $course->id) }}" 
                                       class="btn btn-primary w-100">
                                        <i class="fas fa-eye me-1"></i> View Course
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    {{ $courses->links() }}
                @endif
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} CBT Learning Platform. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
