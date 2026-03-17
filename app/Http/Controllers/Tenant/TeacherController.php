<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\SchoolUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    /**
     * Get school ID from route or auth user.
     */
    private function getSchoolId(Request $request): ?int
    {
        $schoolSlug = $request->route('school');
        if ($schoolSlug) {
            $school = SchoolUnit::where('slug', $schoolSlug)->first();
            return $school?->id;
        }
        return auth()->user()->school_unit_id ?? null;
    }

    /**
     * Get school slug from route.
     */
    private function getSchoolSlug(Request $request): ?string
    {
        return $request->route('school');
    }

    /**
     * Get redirect route based on school slug.
     */
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
        $schoolId = $this->getSchoolId($request);
        $schoolSlug = $this->getSchoolSlug($request);
        
        if ($schoolId) {
            $teachers = Teacher::with('school')->where('school_id', $schoolId)->get();
        } else {
            $teachers = Teacher::with('school')->get();
        }
        $schools = SchoolUnit::all();
        return view('yayasan.guru', compact('teachers', 'schools', 'schoolSlug'));
    }

    public function create(Request $request)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $schoolId = $this->getSchoolId($request);
        
        if ($schoolId) {
            $schools = SchoolUnit::where('id', $schoolId)->get();
        } else {
            $schools = SchoolUnit::all();
        }
        return view('yayasan.teachers.create', compact('schools', 'schoolSlug'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'nip'     => 'nullable|string|max:50',
            'gender'  => 'required|in:L,P',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'school_id' => 'nullable|exists:school_units,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Teacher::create($validated);

        return $this->getRedirectRoute($request, 'tenant.teachers.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function updatePlacement(Request $request, Teacher $teacher)
    {
        $teacher->update(['school_id' => $request->school_id]);
        return back()->with('success', 'Penempatan guru berhasil diperbarui.');
    }
}
