<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\SchoolUnit;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $schoolStats = SchoolUnit::withCount(['students', 'teachers'])->get();
        return view('yayasan.laporan', compact('schoolStats'));
    }

    /**
     * School report for tenant level
     */
    public function school(Request $request)
    {
        $schoolId = auth()->user()->school_unit_id;
        
        // If user has a school unit, redirect to school-specific reports
        if ($schoolId && auth()->user()->schoolUnit?->slug) {
            return redirect()->route('tenant.school.finance.reports.index', ['school' => auth()->user()->schoolUnit->slug]);
        }
        
        // For yayasan users without school unit, show foundation-level school report
        if (auth()->user()->hasRole('foundation_admin') || auth()->user()->hasRole('yayasan_admin')) {
            $schoolStats = SchoolUnit::withCount(['students', 'teachers'])->get();
            return view('tenant.report.school', compact('schoolStats'));
        }
        
        // Fallback - access denied
        abort(403, 'Unauthorized access to school report');
    }

    /**
     * System report for tenant level
     */
    public function system(Request $request)
    {
        // For yayasan users, show system-level report
        if (auth()->user()->hasRole('foundation_admin') || auth()->user()->hasRole('yayasan_admin')) {
            return view('tenant.report.system');
        }
        
        // For school users, redirect to school-specific reports
        if (auth()->user()->school_unit_id && auth()->user()->schoolUnit?->slug) {
            return redirect()->route('tenant.school.finance.reports.index', ['school' => auth()->user()->schoolUnit->slug]);
        }
        
        // Fallback - access denied
        abort(403, 'Unauthorized access to system report');
    }
}
