<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\SchoolUnit;
use App\Models\Major;
use App\Models\AcademicYear;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class AcademicController extends Controller
{
    private function getSchoolSlug(Request $request): ?string
    {
        return $request->route('school');
    }
    
    private function getSchoolId(Request $request): ?int
    {
        $slug = $this->getSchoolSlug($request);
        if ($slug) {
            $school = SchoolUnit::where('slug', $slug)->first();
            return $school?->id;
        }
        return auth()->user()->school_unit_id;
    }
    
    private function getRedirectRoute(Request $request, string $routeName, $params = []): \Illuminate\Http\RedirectResponse
    {
        $schoolSlug = $this->getSchoolSlug($request);
        if ($schoolSlug) {
            return redirect()->route($routeName, array_merge(['school' => $schoolSlug], $params));
        }
        return redirect()->route($routeName, $params);
    }

    public function setup(SchoolUnit $school)
    {
        // Ensure this school is active
        if ($school->status !== 'active') {
            return redirect()->route('tenant.units.index')->with('error', 'Unit sekolah harus diaktifkan terlebih dahulu.');
        }

        $majors = Major::where('school_id', $school->id)->get();
        $academicYears = AcademicYear::all(); // This might need to be filtered by school if specific

        return view('yayasan.units.academic-setup', compact('school', 'majors', 'academicYears'));
    }

    public function subjects(SchoolUnit $school)
    {
        return view('yayasan.units.subjects', compact('school'));
    }
    
    public function subjectsIndex(Request $request)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $schoolId = $this->getSchoolId($request);
        $school = SchoolUnit::find($schoolId);
        $subjects = Subject::where('school_id', $schoolId)->get();
        return view('yayasan.units.subjects', compact('school', 'subjects', 'schoolSlug'));
    }
    
    public function createSubject(Request $request)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $schoolId = $this->getSchoolId($request);
        $school = SchoolUnit::find($schoolId);
        return view('yayasan.units.subjects-create', compact('school', 'schoolSlug'));
    }
    
    public function storeSubject(Request $request)
    {
        $schoolId = $this->getSchoolId($request);
        
        $request->validate([
            'code' => 'required|string|max:10|unique:subjects,code,NULL,id,school_id,' . $schoolId,
            'name' => 'required|string|max:255',
            'type' => 'required|in:theory,practice',
        ]);
        
        Subject::create([
            'school_id' => $schoolId,
            'code' => $request->code,
            'name' => $request->name,
            'type' => $request->type,
        ]);
        
        return $this->getRedirectRoute($request, 'tenant.school.subjects.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }
    
    public function editSubject(Request $request, Subject $subject)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $school = SchoolUnit::find($subject->school_id);
        return view('yayasan.units.subjects-edit', compact('school', 'subject', 'schoolSlug'));
    }
    
    public function updateSubject(Request $request, Subject $subject)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:subjects,code,' . $subject->id . ',id,school_id,' . $subject->school_id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:theory,practice',
        ]);
        
        $subject->update([
            'code' => $request->code,
            'name' => $request->name,
            'type' => $request->type,
        ]);
        
        return $this->getRedirectRoute($request, 'tenant.school.subjects.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }
    
    public function destroySubject(Request $request, Subject $subject)
    {
        $subject->delete();
        return $this->getRedirectRoute($request, 'tenant.school.subjects.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }

    public function schedule(SchoolUnit $school)
    {
        return view('yayasan.units.schedule', compact('school'));
    }
    
    public function scheduleIndex(Request $request)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $schoolId = $this->getSchoolId($request);
        $school = SchoolUnit::find($schoolId);
        $schedules = Schedule::whereHas('classRoom', function($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })->with(['classRoom', 'subject', 'teacher', 'academicYear'])->get();
        $classrooms = ClassRoom::where('school_id', $schoolId)->get();
        return view('yayasan.units.schedule', compact('school', 'schoolSlug', 'schedules', 'classrooms'));
    }
    
    public function createSchedule(Request $request)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $schoolId = $this->getSchoolId($request);
        $school = SchoolUnit::find($schoolId);
        $classrooms = ClassRoom::where('school_id', $schoolId)->get();
        $subjects = Subject::where('school_id', $schoolId)->get();
        $teachers = Teacher::where('school_id', $schoolId)->get();
        $academicYears = AcademicYear::all();
        return view('yayasan.units.schedule-create', compact('school', 'schoolSlug', 'classrooms', 'subjects', 'teachers', 'academicYears'));
    }
    
    public function storeSchedule(Request $request)
    {
        $schoolId = $this->getSchoolId($request);
        
        $request->validate([
            'class_room_id' => 'required|exists:class_rooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);
        
        Schedule::create([
            'class_room_id' => $request->class_room_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'academic_year_id' => $request->academic_year_id,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
        
        return $this->getRedirectRoute($request, 'tenant.school.schedule.index')->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }
}
