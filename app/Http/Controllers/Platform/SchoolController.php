<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolUnit;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = SchoolUnit::with('foundation')->latest();

        if ($status && in_array($status, [
            SchoolUnit::STATUS_DRAFT,
            SchoolUnit::STATUS_SETUP,
            SchoolUnit::STATUS_ACTIVE,
            SchoolUnit::STATUS_SUSPENDED,
            SchoolUnit::STATUS_EXPIRED,
        ])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('npsn', 'like', "%{$search}%")
                  ->orWhereHas('foundation', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $schools = $query->paginate(15)->withQueryString();

        $stats = [
            'total'     => SchoolUnit::count(),
            'draft'     => SchoolUnit::where('status', SchoolUnit::STATUS_DRAFT)->count(),
            'setup'     => SchoolUnit::where('status', SchoolUnit::STATUS_SETUP)->count(),
            'active'    => SchoolUnit::where('status', SchoolUnit::STATUS_ACTIVE)->count(),
            'suspended' => SchoolUnit::where('status', SchoolUnit::STATUS_SUSPENDED)->count(),
            'expired'   => SchoolUnit::where('status', SchoolUnit::STATUS_EXPIRED)->count(),
        ];

        return view('admin.schools.index', compact('schools', 'stats', 'status', 'search'));
    }
}
