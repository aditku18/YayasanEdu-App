<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SchoolUnit;
use App\Models\AcademicYear;
use App\Models\Teacher;
use App\Models\Student;

class WizardController extends Controller
{
    public function index()
    {
        // Jika sudah ada sekolah, asumsikan wizard sudah selesai.
        if (SchoolUnit::count() > 0) {
            return redirect()->route('tenant.dashboard');
        }

        // Ambil data yayasan dari database sentral untuk pre-populate
        // Gunakan tenant('id') karena lebih spesifik dan akurat daripada email
        $centralFoundation = \App\Models\Foundation::where('tenant_id', tenant('id'))->first();

        return view('tenant.wizard', [
            'centralFoundation' => $centralFoundation
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            // Step 1: Foundation
            'foundation_name' => 'required|string|max:255',
            'foundation_address' => 'nullable|string',
            'foundation_phone' => 'nullable|string|max:20',
            
            // Step 2: Schools
            'school_name' => 'required|string|max:255',
        ]);

        // 1. Create/Update Foundation Profile in Tenant DB
        \App\Models\Yayasan::updateOrCreate(
            ['id' => 1], // Usually just one foundation per tenant
            [
                'name' => $request->foundation_name,
                'address' => $request->foundation_address,
                'phone' => $request->foundation_phone,
            ]
        );

        // 2. Create the first School Unit
        SchoolUnit::create([
            'name' => $request->school_name,
            'address' => $request->foundation_address, // Use foundation address as default
        ]);

        return redirect()->route('tenant.dashboard')->with('success', 'Setup Yayasan berhasil! Sekarang Anda dapat mengelola unit sekolah.');
    }
}
