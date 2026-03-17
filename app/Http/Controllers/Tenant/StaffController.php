<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SchoolUnit;
use Illuminate\Http\Request;

class StaffController extends Controller
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
        $school = SchoolUnit::find($schoolId);
        $staffs = User::where('school_unit_id', $schoolId)
                    ->where('role', 'staff')
                    ->paginate(20);
        return view('school.staff.index', compact('staffs', 'school', 'schoolSlug'));
    }
    
    public function create(Request $request)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $schoolId = $this->getSchoolId($request);
        $school = SchoolUnit::find($schoolId);
        return view('school.staff.create', compact('school', 'schoolSlug'));
    }
    
    public function store(Request $request)
    {
        $schoolId = $this->getSchoolId($request);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'school_unit_id' => $schoolId,
            'role' => 'staff',
        ]);
        
        return $this->getRedirectRoute($request, 'tenant.school.staff.index')->with('success', 'Staff berhasil ditambahkan.');
    }
}
