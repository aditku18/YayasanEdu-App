<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CBT - Learning Management')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #6b7280;
            --success-color: #10b981;
            --danger-color: #ef4444;
        }
        
        .sidebar {
            min-height: 100vh;
            background: #1f2937;
            color: white;
        }
        
        .sidebar a {
            color: #d1d5db;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            border-radius: 8px;
            margin: 4px 8px;
            transition: all 0.3s;
        }
        
        .sidebar a:hover, .sidebar a.active {
            background: var(--primary-color);
            color: white;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: var(--primary-color);
            border: none;
        }
        
        .course-card {
            transition: transform 0.3s;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
        }
        
        .progress-ring {
            transform: rotate(-90deg);
        }
        
        .quiz-timer {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            z-index: 1000;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @auth
            <div class="col-md-2 sidebar p-0">
                <div class="p-4">
                    <h5 class="mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>CBT Admin
                    </h5>
                </div>
                <nav>
                    <a href="{{ route('cbt.dashboard') }}" class="{{ request()->routeIs('cbt.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                    <a href="{{ route('cbt.courses.index') }}" class="{{ request()->routeIs('cbt.courses.*') ? 'active' : '' }}">
                        <i class="fas fa-book me-2"></i> Courses
                    </a>
                    <a href="{{ route('cbt.quizzes.index') }}" class="{{ request()->routeIs('cbt.quizzes.*') ? 'active' : '' }}">
                        <i class="fas fa-question-circle me-2"></i> Quizzes
                    </a>
                    <a href="{{ route('cbt.questions.bank') }}" class="{{ request()->routeIs('cbt.questions.*') ? 'active' : '' }}">
                        <i class="fas fa-database me-2"></i> Question Bank
                    </a>
                    <a href="{{ route('cbt.certificates.index') }}" class="{{ request()->routeIs('cbt.certificates.*') ? 'active' : '' }}">
                        <i class="fas fa-certificate me-2"></i> Certificates
                    </a>
                    <a href="{{ route('cbt.analytics.index') }}" class="{{ request()->routeIs('cbt.analytics.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar me-2"></i> Analytics
                    </a>
                    <hr class="border-secondary">
                    <a href="{{ route('cbt.learn.courses.index') }}">
                        <i class="fas fa-play-circle me-2"></i> My Learning
                    </a>
                </nav>
            </div>
            @endauth
            
            <div class="@auth col-md-10 @else col-12 @endauth p-4" style="background: #f3f4f6; min-height: 100vh;">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
