<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\SchoolUnit;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function store(Request $request, SchoolUnit $school)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:majors,code',
            'name' => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:50',
            'head_of_major' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $validated['school_id'] = $school->id;
        $validated['status'] = 'active';

        Major::create($validated);

        return back()->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function update(Request $request, Major $major)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:majors,code,' . $major->id,
            'name' => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:50',
            'head_of_major' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:active,nonactive',
        ]);

        $major->update($validated);

        return back()->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Major $major)
    {
        $major->delete();
        return back()->with('success', 'Jurusan berhasil dihapus.');
    }
}
