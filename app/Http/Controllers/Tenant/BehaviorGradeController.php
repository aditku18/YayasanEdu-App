<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\BehaviorGrade;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class BehaviorGradeController extends Controller
{
    /**
     * Display behavior grades dashboard
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $schoolId = $user->school_unit_id;
        $schoolSlug = $user->schoolUnit?->slug;
        
        $academicYearId = $request->filled('academic_year') 
            ? $request->academic_year 
            : AcademicYear::where('is_active', true)->first()?->id;
        
        $semester = $request->filled('semester') ? $request->semester : 'ganjil';
        
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        
        // Get students with their behavior grades
        $students = Student::where('school_unit_id', $schoolId)
            ->where('status', 'active')
            ->with(['classRoom'])
            ->get();
        
        $behaviorGrades = BehaviorGrade::where('school_unit_id', $schoolId)
            ->when($academicYearId, function($q) use ($academicYearId) {
                $q->where('academic_year_id', $academicYearId);
            })
            ->when($semester, function($q) use ($semester) {
                $q->where('semester', $semester);
            })
            ->get()
            ->groupBy('student_id');
        
        $selectedYearId = $academicYearId;
        
        return view('yayasan.penilaian.sikap', compact(
            'students',
            'behaviorGrades',
            'academicYears',
            'selectedYearId',
            'semester',
            'schoolSlug'
        ));
    }

    /**
     * Store behavior grade
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $schoolId = $user->school_unit_id;
        
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'aspect' => 'required|in:spiritual,social',
            'semester' => 'required|in:ganjil,genap',
            'grade' => 'required|in:sangat_baik,baik,cukup,kurang',
            'description' => 'nullable|string',
        ]);
        
        $validated['school_unit_id'] = $schoolId;
        $validated['entered_by'] = $user->id;
        
        BehaviorGrade::updateOrCreate(
            [
                'student_id' => $validated['student_id'],
                'academic_year_id' => $validated['academic_year_id'],
                'aspect' => $validated['aspect'],
                'semester' => $validated['semester'],
            ],
            $validated
        );
        
        return back()->with('success', 'Penilaian sikap berhasil disimpan.');
    }

    /**
     * Get behavior grades for a student
     */
    public function getStudentGrades(Request $request, Student $student)
    {
        $user = auth()->user();
        $schoolId = $user->school_unit_id;
        
        if ($student->school_unit_id != $schoolId) {
            abort(403);
        }
        
        $academicYearId = $request->filled('academic_year') 
            ? $request->academic_year 
            : AcademicYear::where('is_active', true)->first()?->id;
        
        $grades = BehaviorGrade::where('student_id', $student->id)
            ->when($academicYearId, function($q) use ($academicYearId) {
                $q->where('academic_year_id', $academicYearId);
            })
            ->get();
        
        return response()->json($grades);
    }
}
