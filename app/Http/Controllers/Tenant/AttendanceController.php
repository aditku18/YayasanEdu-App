<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_unit_id;
        $classrooms = ClassRoom::where('school_id', $schoolId)->get();
        return view('school.attendance.index', compact('classrooms'));
    }

    public function create(ClassRoom $classroom)
    {
        $students = $classroom->students;
        return view('school.attendance.create', compact('classroom', 'students'));
    }

    public function store(Request $request)
    {
        // Logic for storing attendance
        return redirect()->route('tenant.attendance.index')->with('success', 'Presensi berhasil disimpan.');
    }
}
