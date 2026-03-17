<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $search = request()->query('search');

        $plans = Plan::when($search, function ($q, $search) {
            $q->where('name', 'like', "%{$search}%");
        })->orderBy('sort_order', 'asc')
          ->paginate(12)
          ->withQueryString();

        $stats = [
            'total' => Plan::count(),
            'active' => Plan::where('is_active', true)->count(),
            'featured' => Plan::where('is_featured', true)->count(),
        ];

        return view('platform.plans.index', compact('plans', 'search', 'stats'));
    }

    public function create()
    {
        return view('platform.plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_month' => 'required|numeric|min:0',
            'price_per_year' => 'nullable|numeric|min:0',
            'max_schools' => 'required|integer|min:1',
            'max_users' => 'required|integer|min:1',
            'max_students' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        
        // Convert features_text to features array
        if (!empty($request->features_text)) {
            $features = array_filter(array_map('trim', explode("\n", $request->features_text)));
            $data['features'] = array_values($features);
        } else {
            $data['features'] = [];
        }
        
        Plan::create($data);

        return redirect()->route('platform.plans.index')
            ->with('success', 'Paket berhasil dibuat!');
    }

    public function edit(Plan $plan)
    {
        return view('platform.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_month' => 'required|numeric|min:0',
            'price_per_year' => 'nullable|numeric|min:0',
            'max_schools' => 'required|integer|min:1',
            'max_users' => 'required|integer|min:1',
            'max_students' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->all();
        
        // Update slug only if name changed
        if ($plan->name !== $request->name) {
            $data['slug'] = Str::slug($request->name);
        }
        
        // Convert features_text to features array
        if (!empty($request->features_text)) {
            $features = array_filter(array_map('trim', explode("\n", $request->features_text)));
            $data['features'] = array_values($features);
        } else {
            $data['features'] = [];
        }

        $plan->update($data);

        return redirect()->route('platform.plans.index')
            ->with('success', 'Paket berhasil diperbarui!');
    }

    public function destroy(Plan $plan)
    {
        // Check if plan is being used by any foundation
        if ($plan->foundations()->count() > 0) {
            return redirect()->route('platform.plans.index')
                ->with('error', 'Paket tidak dapat dihapus karena sedang digunakan oleh yayasan!');
        }

        $plan->delete();

        return redirect()->route('platform.plans.index')
            ->with('success', 'Paket berhasil dihapus!');
    }
}
