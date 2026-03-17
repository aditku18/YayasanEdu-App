<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Foundation;

class FoundationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Foundation::latest();

        if ($status && in_array($status, ['pending', 'trial', 'active', 'expired', 'rejected'])) {
            $query->where('status', $status);
        }

        $foundations = $query->paginate(10)->withQueryString();

        // Simple stats
        $stats = [
            'total'    => Foundation::count(),
            'pending'  => Foundation::where('status', 'pending')->count(),
            'trial'    => Foundation::where('status', 'trial')->count(),
            'active'   => Foundation::where('status', 'active')->count(),
            'expired'  => Foundation::where('status', 'expired')->count(),
            'rejected' => Foundation::where('status', 'rejected')->count(),
        ];

        return view('admin.foundations.index', compact('foundations', 'stats', 'status'));
    }

    public function show(Foundation $foundation)
    {
        $foundation->load(['plan', 'users']);
        
        return view('admin.foundations.show', compact('foundation'));
    }

    public function reject(Request $request, Foundation $foundation)
    {
        if ($foundation->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya yayasan berstatus pending yang bisa ditolak.');
        }

        $foundation->update(['status' => 'rejected']);

        return redirect()->route('admin.foundations.index')->with('success', "Yayasan {$foundation->name} telah ditolak.");
    }
}

