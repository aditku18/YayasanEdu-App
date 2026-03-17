<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\SchoolUnit;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Models\Foundation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     * 
     * @param string|null $schoolSlug - The school slug for school-specific dashboard
     */
    public function index(Request $request, ?string $schoolSlug = null)
    {
        // Redirect to wizard if no schools created yet
        if (SchoolUnit::count() === 0) {
            return redirect()->route('tenant.wizard');
        }

        $user = auth()->user();
        
        // If school slug is provided, use that school
        if ($schoolSlug) {
            $school = SchoolUnit::where('slug', $schoolSlug)->first();
            
            if (!$school) {
                return abort(404, 'Unit sekolah tidak ditemukan.');
            }
            
            // Check access permissions
            if ($user->hasRole('school_admin') || $user->hasRole('staff')) {
                if ($user->school_unit_id != $school->id) {
                    return abort(403, 'Anda tidak memiliki akses ke unit sekolah ini.');
                }
            } elseif ($user->hasRole('teacher')) {
                $teacher = Teacher::where('user_id', $user->id)->first();
                if ($teacher?->school_id != $school->id) {
                    return abort(403, 'Anda tidak memiliki akses ke unit sekolah ini.');
                }
            }
            
            // Check if school is active
            if ($school->status !== 'active') {
                return view('errors.school-inactive', compact('school'));
            }

            // School-specific dashboard (for school_admin, staff, teacher)
            $school->loadCount(['students', 'teachers']);
            
            $stats = [
                'total_students' => $school->students_count,
                'total_teachers' => $school->teachers_count,
                'total_classes' => ClassRoom::where('school_id', $school->id)->count(),
            ];

            return view('school.dashboard', compact('school', 'stats'));
        }

        // IF SCHOOL ADMIN (no slug provided - use their assigned school)
        if ($user->hasRole('school_admin')) {
            $school = SchoolUnit::withCount(['students', 'teachers'])->find($user->school_unit_id);
            
            if (!$school) {
                return abort(403, 'Akun Anda tidak terhubung ke unit sekolah manapun.');
            }

            // Check if school is active
            if ($school->status !== 'active') {
                return view('errors.school-inactive', compact('school'));
            }

            $stats = [
                'total_students' => $school->students_count,
                'total_teachers' => $school->teachers_count,
                'total_classes' => ClassRoom::where('school_id', $school->id)->count(),
            ];

            return view('school.dashboard', compact('school', 'stats'));
        }

        // IF FOUNDATION ADMIN (Default) - Enterprise Mode Dashboard
        // Stats for the foundation (Enterprise Mode)
        $stats = [
            'total_schools' => SchoolUnit::count(),
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_classes' => ClassRoom::count(),
        ];

        // List of all school units for the grid/chart
        $schools = SchoolUnit::withCount(['students', 'teachers'])->get();

        // Get trial info from central Foundation model
        $userEmail = auth()->user()->email;
        $foundation = Foundation::where('email', $userEmail)->first();
        
        $trialDaysLeft = $foundation ? $foundation->daysLeftInTrial() : 0;
        $isTrial = $foundation ? ($foundation->status === 'trial') : false;

        // Recently activity (Enterprise Mock)
        $activities = [
            ['description' => 'Unit sekolah baru "SMA IT Bina Bangsa" dibuat', 'time' => '2 jam yang lalu', 'type' => 'school'],
            ['description' => 'Penempatan guru "Budi Santoso" diperbarui', 'time' => '5 jam yang lalu', 'type' => 'teacher'],
            ['description' => 'Import 50 data siswa baru ke SD Al-Azhar', 'time' => '1 hari yang lalu', 'type' => 'student'],
        ];

        return view('yayasan.dashboard', compact('stats', 'schools', 'trialDaysLeft', 'isTrial', 'activities'));
    }
}
