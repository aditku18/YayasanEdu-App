<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Plugin;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $plugins = Plugin::where('is_available_in_marketplace', true)
            ->when($request->category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($request->price_type, function ($query, $priceType) {
                if ($priceType === 'free') {
                    return $query->where('price', 0);
                } elseif ($priceType === 'paid') {
                    return $query->where('price', '>', 0);
                }
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20);

        $categories = Plugin::where('is_available_in_marketplace', true)
            ->distinct()
            ->pluck('category');
        
        return view('platform.marketplace.index', compact('plugins', 'categories'));
    }

    public function show(Plugin $plugin)
    {
        if (!$plugin->is_available_in_marketplace) {
            return redirect()->route('platform.marketplace.index')
                ->with('error', 'Plugin tidak tersedia di marketplace.');
        }

        $plugin->load(['installations']);
        $relatedPlugins = Plugin::where('category', $plugin->category)
            ->where('id', '!=', $plugin->id)
            ->where('is_available_in_marketplace', true)
            ->take(4)
            ->get();
        
        return view('platform.marketplace.show', compact('plugin', 'relatedPlugins'));
    }

    public function purchase(Plugin $plugin, Request $request)
    {
        $request->validate([
            'foundation_id' => 'required|exists:foundations,id'
        ]);

        if (!$plugin->is_available_in_marketplace) {
            return redirect()->back()->with('error', 'Plugin tidak tersedia untuk pembelian.');
        }

        // Check if already purchased
        $existingInstallation = \App\Models\PluginInstallation::where('plugin_id', $plugin->id)
            ->where('foundation_id', $request->foundation_id)
            ->first();

        if ($existingInstallation) {
            return redirect()->back()->with('error', 'Plugin sudah dibeli untuk yayasan ini.');
        }

        // Create purchase record
        // TODO: Implement payment processing and purchase record

        return redirect()->route('platform.marketplace.show', $plugin)
            ->with('success', 'Plugin berhasil dibeli. Silakan install untuk mulai menggunakannya.');
    }

    public function install(Plugin $plugin, Request $request)
    {
        $request->validate([
            'foundation_id' => 'required|exists:foundations,id'
        ]);

        // Check if already installed
        $existingInstallation = \App\Models\PluginInstallation::where('plugin_id', $plugin->id)
            ->where('foundation_id', $request->foundation_id)
            ->first();

        if ($existingInstallation) {
            return redirect()->back()->with('error', 'Plugin sudah diinstal untuk yayasan ini.');
        }

        // Install plugin
        \App\Models\PluginInstallation::create([
            'plugin_id' => $plugin->id,
            'foundation_id' => $request->foundation_id,
            'is_active' => true,
            'installed_at' => now(),
            'installed_by' => auth()->id()
        ]);

        return redirect()->route('platform.marketplace.show', $plugin)
            ->with('success', 'Plugin berhasil diinstal.');
    }
}
