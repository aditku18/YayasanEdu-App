<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolUnit;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    /**
     * Display a listing of parents/guardians.
     */
    public function index(Request $request)
    {
        $schoolId = $request->route('school');
        
        if ($schoolId) {
            $school = SchoolUnit::where('slug', $schoolId)->first();
            $students = Student::with('school')
                ->when($school, function($query) use ($school) {
                    return $query->where('school_id', $school->id);
                })
                ->where(function($query) {
                    $query->whereNotNull('father_name')
                        ->orWhereNotNull('mother_name')
                        ->orWhereNotNull('guardian_name')
                        ->orWhereNotNull('parent_name');
                })
                ->get();
        } else {
            // Global - all parents across all schools
            $students = Student::with('school')
                ->where(function($query) {
                    $query->whereNotNull('father_name')
                        ->orWhereNotNull('mother_name')
                        ->orWhereNotNull('guardian_name')
                        ->orWhereNotNull('parent_name');
                })
                ->get();
        }
        
        $schools = SchoolUnit::all();
        $schoolSlug = $schoolId;
        
        return view('yayasan.parents', compact('students', 'schools', 'schoolSlug'));
    }
}
