<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\SchoolUnit;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
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

    public function index(Request $request)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $schoolId = $this->getSchoolId($request);
        $classrooms = ClassRoom::where('school_id', $schoolId)->with('homeroomTeacher')->get();
        return view('school.classroom.index', compact('classrooms', 'schoolSlug'));
    }

    public function create(Request $request)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $schoolId = $this->getSchoolId($request);
        $teachers = Teacher::where('school_id', $schoolId)->get();
        return view('school.classroom.create', compact('teachers', 'schoolSlug'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        $schoolId = $this->getSchoolId($request);
        ClassRoom::create([
            'school_id' => $schoolId,
            'name' => $request->name,
            'level' => $request->level,
            'teacher_id' => $request->teacher_id,
        ]);

        return $this->getRedirectRoute($request, 'tenant.classrooms.index')->with('success', 'Kelas berhasil dibuat.');
    }

    public function show(Request $request, ClassRoom $classroom)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $classroom->load(['homeroomTeacher', 'students']);
        return view('school.classroom.show', compact('classroom', 'schoolSlug'));
    }
    
    public function edit(Request $request, ClassRoom $classroom)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $schoolId = $this->getSchoolId($request);
        $teachers = Teacher::where('school_id', $schoolId)->get();
        return view('school.classroom.edit', compact('classroom', 'teachers', 'schoolSlug'));
    }
    
    public function update(Request $request, ClassRoom $classroom)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        $classroom->update([
            'name' => $request->name,
            'level' => $request->level,
            'teacher_id' => $request->teacher_id,
        ]);

        return $this->getRedirectRoute($request, 'tenant.classrooms.show', $classroom)->with('success', 'Kelas berhasil diperbarui.');
    }
    
    public function destroy(Request $request, ClassRoom $classroom)
    {
        $classroom->delete();
        return $this->getRedirectRoute($request, 'tenant.classrooms.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
