<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Foundation;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        $plans = Plan::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Fetch real statistics
        // Note: student_count column doesn't exist in foundations table
        // Using Student::count() for total students across all tenants
        $stats = [
            'foundations' => Foundation::count(),
            'students' => Student::count() ?? 0,
            'users' => User::count(),
        ];

        $activeFoundations = Foundation::active()->latest()->take(3)->get();

        return view('landing.index', compact('plans', 'stats', 'activeFoundations'));
    }
}
