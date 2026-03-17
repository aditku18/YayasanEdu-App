<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\SchoolUnit;
use Illuminate\Http\Request;

class UnitSelectionController extends Controller
{
    public function select(SchoolUnit $school)
    {
        session(['active_school_id' => $school->id]);
        session(['active_school_name' => $school->name]);

        return redirect()->route('tenant.academic-setup', $school->id)
            ->with('success', "Anda sekarang mengelola unit: {$school->name}");
    }
}
