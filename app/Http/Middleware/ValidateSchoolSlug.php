<?php

namespace App\Http\Middleware;

use App\Models\SchoolUnit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateSchoolSlug
{
    /**
     * Handle an incoming request.
     * Validates the school slug and ensures user has access.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $schoolSlug = $request->route('school');
        
        // Skip if no school slug in route
        if (!$schoolSlug) {
            return $next($request);
        }

        // Find school by slug
        $school = SchoolUnit::where('slug', $schoolSlug)->first();
        
        if (!$school) {
            return response()->view('errors.404', [], 404);
        }

        // Check if school is active (unless user is foundation_admin)
        $user = $request->user();
        
        if ($user) {
            // Foundation admin can access all schools
            if ($user->hasRole('foundation_admin') || $user->hasRole('super_admin')) {
                // Allow access
            }
            // School admin/staff can only access their assigned school
            elseif ($user->hasRole('school_admin') || $user->hasRole('staff')) {
                if ($user->school_unit_id != $school->id) {
                    return response()->view('errors.403', [
                        'message' => 'Anda tidak memiliki akses ke unit sekolah ini.'
                    ], 403);
                }
            }
            // Teacher can only access their assigned school
            elseif ($user->hasRole('teacher')) {
                $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
                if ($teacher?->school_id != $school->id) {
                    return response()->view('errors.403', [
                        'message' => 'Anda tidak memiliki akses ke unit sekolah ini.'
                    ], 403);
                }
            }
            
            // Check school status for non-foundation admins
            if (!$user->hasRole('foundation_admin') && !$user->hasRole('super_admin')) {
                if ($school->status !== 'active') {
                    return response()->view('errors.school-inactive', compact('school'), 403);
                }
            }
        }

        // Share school with all views
        view()->share('currentSchool', $school);
        
        return $next($request);
    }
}
