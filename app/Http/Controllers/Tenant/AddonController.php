<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\Request;

class AddonController extends Controller
{
    public function index()
    {
        $addons = Addon::where('is_active', true)->get();
        return view('tenant.addons.index', compact('addons'));
    }

    public function show(Addon $addon)
    {
        return view('tenant.addons.show', compact('addon'));
    }

    public function purchase(Request $request, Addon $addon)
    {
        // Logic for purchasing addon
        return redirect()->back()->with('success', 'Addon purchased successfully');
    }
}
