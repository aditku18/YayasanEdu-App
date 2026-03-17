<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\SchoolUnit;

class StudentController extends Controller
{
    public function index()
    {
        $search = request('search');
        $school = request('school_id');
        $status = request('status');

        $query = Student::with('school')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        if ($school) {
            $query->where('school_id', $school);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $students = $query->paginate(15)->withQueryString();

        $schools = SchoolUnit::orderBy('name')->get();

        $stats = [
            'total' => Student::count(),
            'active' => Student::where('status', 'active')->count(),
            'inactive' => Student::where('status', 'inactive')->count(),
        ];

        return view('admin.students.index', compact('students', 'schools', 'stats', 'search', 'school', 'status'));
    }
}
