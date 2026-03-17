<?php

namespace App\Modules\Attendance\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display settings
     */
    public function index()
    {
        return view('attendance::admin.settings.index');
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        // Implementation
    }
}
