<?php

use Illuminate\Support\Facades\Route;
use App\Modules\CBT\Http\Controllers\Admin\CourseController;
use App\Modules\CBT\Http\Controllers\Admin\QuizController;
use App\Modules\CBT\Http\Controllers\Admin\QuestionController;
use App\Modules\CBT\Http\Controllers\Admin\CertificateController;
use App\Modules\CBT\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Modules\CBT\Http\Controllers\Student\QuizController as StudentQuizController;

/* |-------------------------------------------------------------------------- | CBT (Computer Based Test) Module Routes |-------------------------------------------------------------------------- | | Routes untuk CBT module yang mencakup: | - Course Management (Admin) | - Quiz Management (Admin) | - Question Bank (Admin) | - Certificate Management (Admin) | - Student Learning Routes | */

// CBT Admin Routes - requires authentication and appropriate permissions
Route::middleware(['auth', 'verified'])->prefix('cbt')->name('cbt.')->group(function () {

    // Admin Routes
    Route::middleware(['role:super_admin|foundation_admin|school_admin'])->group(function () {

            // Dashboard
            Route::get('/dashboard', [CourseController::class , 'dashboard'])->name('dashboard');

            // Course Management
            Route::get('/courses', [CourseController::class , 'index'])->name('courses.index');
            Route::get('/courses/create', [CourseController::class , 'create'])->name('courses.create');
            Route::post('/courses', [CourseController::class , 'store'])->name('courses.store');
            Route::get('/courses/{course}', [CourseController::class , 'show'])->name('courses.show');
            Route::get('/courses/{course}/edit', [CourseController::class , 'edit'])->name('courses.edit');
            Route::put('/courses/{course}', [CourseController::class , 'update'])->name('courses.update');
            Route::delete('/courses/{course}', [CourseController::class , 'destroy'])->name('courses.destroy');
            Route::post('/courses/{course}/publish', [CourseController::class , 'publish'])->name('courses.publish');
            Route::post('/courses/{course}/unpublish', [CourseController::class , 'unpublish'])->name('courses.unpublish');
            Route::post('/courses/{course}/duplicate', [CourseController::class , 'duplicate'])->name('courses.duplicate');

            // Course Categories
            Route::get('/categories', [CourseController::class , 'categories'])->name('categories.index');
            Route::post('/categories', [CourseController::class , 'storeCategory'])->name('categories.store');
            Route::put('/categories/{category}', [CourseController::class , 'updateCategory'])->name('categories.update');
            Route::delete('/categories/{category}', [CourseController::class , 'destroyCategory'])->name('categories.destroy');

            // Module Management (nested under course)
            Route::get('/courses/{course}/modules', [CourseController::class , 'modules'])->name('courses.modules.index');
            Route::post('/courses/{course}/modules', [CourseController::class , 'storeModule'])->name('courses.modules.store');
            Route::put('/modules/{module}', [CourseController::class , 'updateModule'])->name('modules.update');
            Route::delete('/modules/{module}', [CourseController::class , 'destroyModule'])->name('modules.destroy');
            Route::post('/modules/{module}/reorder', [CourseController::class , 'reorderModules'])->name('modules.reorder');

            // Lesson Management (nested under module)
            Route::get('/modules/{module}/lessons', [CourseController::class , 'lessons'])->name('modules.lessons.index');
            Route::post('/modules/{module}/lessons', [CourseController::class , 'storeLesson'])->name('modules.lessons.store');
            Route::put('/lessons/{lesson}', [CourseController::class , 'updateLesson'])->name('lessons.update');
            Route::delete('/lessons/{lesson}', [CourseController::class , 'destroyLesson'])->name('lessons.destroy');
            Route::post('/lessons/{lesson}/reorder', [CourseController::class , 'reorderLessons'])->name('lessons.reorder');

            // Quiz Management
            Route::get('/quizzes', [QuizController::class , 'index'])->name('quizzes.index');
            Route::get('/quizzes/create', [QuizController::class , 'create'])->name('quizzes.create');
            Route::post('/quizzes', [QuizController::class , 'store'])->name('quizzes.store');
            Route::get('/quizzes/{quiz}', [QuizController::class , 'show'])->name('quizzes.show');
            Route::get('/quizzes/{quiz}/edit', [QuizController::class , 'edit'])->name('quizzes.edit');
            Route::put('/quizzes/{quiz}', [QuizController::class , 'update'])->name('quizzes.update');
            Route::delete('/quizzes/{quiz}', [QuizController::class , 'destroy'])->name('quizzes.destroy');
            Route::post('/quizzes/{quiz}/publish', [QuizController::class , 'publish'])->name('quizzes.publish');
            Route::post('/quizzes/{quiz}/unpublish', [QuizController::class , 'unpublish'])->name('quizzes.unpublish');

            // Quiz Attempts & Results
            Route::get('/quizzes/{quiz}/attempts', [QuizController::class , 'attempts'])->name('quizzes.attempts');
            Route::get('/attempts/{attempt}', [QuizController::class , 'showAttempt'])->name('attempts.show');
            Route::post('/attempts/{attempt}/regrade', [QuizController::class , 'regrade'])->name('attempts.regrade');

            // Question Management
            Route::get('/quizzes/{quiz}/questions', [QuestionController::class , 'index'])->name('questions.index');
            Route::get('/questions/create/{quiz?}', [QuestionController::class , 'create'])->name('questions.create');
            Route::post('/questions', [QuestionController::class , 'store'])->name('questions.store');
            Route::get('/questions/{question}/edit', [QuestionController::class , 'edit'])->name('questions.edit');
            Route::put('/questions/{question}', [QuestionController::class , 'update'])->name('questions.update');
            Route::delete('/questions/{question}', [QuestionController::class , 'destroy'])->name('questions.destroy');
            Route::post('/questions/reorder', [QuestionController::class , 'reorder'])->name('questions.reorder');

            // Question Bank (shared across quizzes)
            Route::get('/question-bank', [QuestionController::class , 'bank'])->name('questions.bank');
            Route::post('/question-bank/import', [QuestionController::class , 'import'])->name('questions.import');
            Route::get('/question-bank/export', [QuestionController::class , 'export'])->name('questions.export');

            // Certificate Management
            Route::get('/certificates', [CertificateController::class , 'index'])->name('certificates.index');
            Route::get('/certificates/create', [CertificateController::class , 'create'])->name('certificates.create');
            Route::post('/certificates', [CertificateController::class , 'store'])->name('certificates.store');
            Route::get('/certificates/{certificate}', [CertificateController::class , 'show'])->name('certificates.show');
            Route::get('/certificates/{certificate}/edit', [CertificateController::class , 'edit'])->name('certificates.edit');
            Route::put('/certificates/{certificate}', [CertificateController::class , 'update'])->name('certificates.update');
            Route::delete('/certificates/{certificate}', [CertificateController::class , 'destroy'])->name('certificates.destroy');

            // Issued Certificates
            Route::get('/certificates/issued', [CertificateController::class , 'issued'])->name('certificates.issued');
            Route::get('/certificates/issued/{issued}', [CertificateController::class , 'showIssued'])->name('certificates.issued.show');
            Route::get('/certificates/issued/{issued}/download', [CertificateController::class , 'download'])->name('certificates.issued.download');
            Route::post('/certificates/issued/{issued}/revoke', [CertificateController::class , 'revoke'])->name('certificates.issued.revoke');

            // Analytics & Reports
            Route::get('/analytics', [CourseController::class , 'analytics'])->name('analytics.index');
            Route::get('/analytics/courses', [CourseController::class , 'courseAnalytics'])->name('analytics.courses');
            Route::get('/analytics/quizzes', [CourseController::class , 'quizAnalytics'])->name('analytics.quizzes');
            Route::get('/analytics/students', [CourseController::class , 'studentAnalytics'])->name('analytics.students');
        }
        );

        // Student Routes (for enrolled students)
        Route::prefix('learn')->name('learn.')->group(function () {
            Route::get('/courses', [StudentCourseController::class , 'index'])->name('courses.index');
            Route::get('/courses/{course}', [StudentCourseController::class , 'show'])->name('courses.show');
            Route::post('/courses/{course}/enroll', [StudentCourseController::class , 'enroll'])->name('courses.enroll');

            Route::get('/lessons/{lesson}', [StudentCourseController::class , 'lesson'])->name('lessons.show');
            Route::post('/lessons/{lesson}/complete', [StudentCourseController::class , 'completeLesson'])->name('lessons.complete');

            // Quiz Taking
            Route::get('/quizzes/{quiz}/start', [StudentQuizController::class , 'start'])->name('quizzes.start');
            Route::get('/quizzes/{attempt}', [StudentQuizController::class , 'show'])->name('quizzes.show');
            Route::post('/quizzes/{attempt}/answer', [StudentQuizController::class , 'answer'])->name('quizzes.answer');
            Route::post('/quizzes/{attempt}/submit', [StudentQuizController::class , 'submit'])->name('quizzes.submit');

            // Results
            Route::get('/results/{attempt}', [StudentQuizController::class , 'result'])->name('results.show');
            Route::get('/my-courses', [StudentCourseController::class , 'myCourses'])->name('courses.my');
            Route::get('/my-certificates', [StudentCourseController::class , 'myCertificates'])->name('certificates.my');
        }
        );    });

// Public Routes
Route::prefix('cbt')->name('cbt.')->group(function () {
    // Course catalog (public view)
    Route::get('/courses', [StudentCourseController::class , 'publicIndex'])->name('courses.public');
    Route::get('/courses/{course}/preview', [StudentCourseController::class , 'preview'])->name('courses.preview');

    // Certificate verification
    Route::get('/certificates/verify/{code}', [CertificateController::class , 'verify'])->name('certificates.verify');
});
