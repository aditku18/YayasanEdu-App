<?php

if (!function_exists('getSchoolSlug')) {
    /**
     * Get school slug from URL path
     *
     * @return string|null
     */
    function getSchoolSlug() {
        $path = request()->path();
        $segments = explode('/', $path);
        // Check if first segment could be a school slug (not a reserved word)
        $reserved = ['dashboard', 'students', 'teachers', 'classrooms', 'finance', 'attendance', 'ppdb', 'units', 'staff', 'legalitas', 'struktur', 'login', 'register', 'setup-wizard'];
        if (count($segments) > 0 && !in_array($segments[0], $reserved) && !empty($segments[0])) {
            return $segments[0];
        }
        // Fallback to user's assigned school
        if (auth()->check() && auth()->user()->school_unit_id) {
            $school = \App\Models\SchoolUnit::find(auth()->user()->school_unit_id);
            return $school?->slug;
        }
        return null;
    }
}
