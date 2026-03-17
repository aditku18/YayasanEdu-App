<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Plugin;
use App\Models\PluginInstallation;
use Illuminate\Http\Request;

class PluginController extends Controller
{
    public function index(Request $request)
    {
        $plugins = Plugin::when($request->category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        $categories = Plugin::distinct()->pluck('category');
        
        return view('platform.plugins.index', compact('plugins', 'categories'));
    }

    public function show(Plugin $plugin)
    {
        $plugin->load(['installations.foundation']);
        return view('platform.plugins.show', compact('plugin'));
    }

    public function active(Request $request)
    {
        $activePlugins = PluginInstallation::with(['plugin', 'foundation'])
            ->when($request->foundation_id, function ($query, $foundationId) {
                return $query->where('foundation_id', $foundationId);
            })
            ->where('is_active', true)
            ->latest()
            ->paginate(20);

        $foundations = \App\Models\Foundation::pluck('name', 'id');

        // Calculate statistics for the view
        $stats = [
            'total_installations' => PluginInstallation::where('is_active', true)->count(),
            'unique_plugins' => PluginInstallation::where('is_active', true)->distinct('plugin_id')->count('plugin_id'),
            'unique_foundations' => PluginInstallation::where('is_active', true)->distinct('foundation_id')->count('foundation_id'),
            'today_installations' => PluginInstallation::where('is_active', true)->whereDate('installed_at', today())->count(),
        ];
        
        return view('platform.plugins.active', compact('activePlugins', 'foundations', 'stats'));
    }

    public function install(Plugin $plugin, Request $request)
    {
        $request->validate([
            'foundation_id' => 'required|exists:foundations,id'
        ]);

        $installation = PluginInstallation::firstOrCreate([
            'plugin_id' => $plugin->id,
            'foundation_id' => $request->foundation_id
        ], [
            'is_active' => true,
            'installed_at' => now(),
            'installed_by' => auth()->id()
        ]);

        return redirect()->route('platform.plugins.show', $plugin)
            ->with('success', 'Plugin berhasil diinstal.');
    }

    public function uninstall(Plugin $plugin, Request $request)
    {
        $request->validate([
            'foundation_id' => 'required|exists:foundations,id'
        ]);

        $installation = PluginInstallation::where('plugin_id', $plugin->id)
            ->where('foundation_id', $request->foundation_id)
            ->first();

        if ($installation) {
            $installation->delete();
            return redirect()->route('platform.plugins.show', $plugin)
                ->with('success', 'Plugin berhasil diuninstall.');
        }

        return redirect()->back()->with('error', 'Instalasi plugin tidak ditemukan.');
    }

    public function activate(Plugin $plugin, Request $request)
    {
        $request->validate([
            'foundation_id' => 'required|exists:foundations,id'
        ]);

        $installation = PluginInstallation::where('plugin_id', $plugin->id)
            ->where('foundation_id', $request->foundation_id)
            ->first();

        if ($installation) {
            $installation->update(['is_active' => true]);
            return redirect()->route('platform.plugins.show', $plugin)
                ->with('success', 'Plugin berhasil diaktifkan.');
        }

        return redirect()->back()->with('error', 'Instalasi plugin tidak ditemukan.');
    }

    public function deactivate(Plugin $plugin, Request $request)
    {
        $request->validate([
            'foundation_id' => 'required|exists:foundations,id'
        ]);

        $installation = PluginInstallation::where('plugin_id', $plugin->id)
            ->where('foundation_id', $request->foundation_id)
            ->first();

        if ($installation) {
            $installation->update(['is_active' => false]);
            return redirect()->route('platform.plugins.show', $plugin)
                ->with('success', 'Plugin berhasil dinonaktifkan.');
        }

        return redirect()->back()->with('error', 'Instalasi plugin tidak ditemukan.');
    }

    /**
     * Show the form for editing the specified plugin.
     */
    public function edit(Plugin $plugin)
    {
        return view('platform.plugins.edit', compact('plugin'));
    }

    /**
     * Update the specified plugin.
     */
    public function update(Request $request, Plugin $plugin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'version' => 'required|string|max:50',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'developer' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,maintenance',
            'is_available_in_marketplace' => 'boolean'
        ]);

        $plugin->update([
            'name' => $request->name,
            'description' => $request->description,
            'version' => $request->version,
            'category' => $request->category,
            'price' => $request->price,
            'developer' => $request->developer,
            'status' => $request->status,
            'is_available_in_marketplace' => $request->is_available_in_marketplace ?? false
        ]);

        return redirect()->route('platform.plugins.show', $plugin)
            ->with('success', 'Plugin berhasil diperbarui.');
    }

    /**
     * Update plugin price only (quick edit).
     */
    public function updatePrice(Request $request, Plugin $plugin)
    {
        $request->validate([
            'price' => 'required|numeric|min:0'
        ]);

        $plugin->update(['price' => $request->price]);

        return redirect()->back()
            ->with('success', 'Harga plugin berhasil diperbarui.');
    }

    /**
     * Update plugin status.
     */
    public function updateStatus(Request $request, Plugin $plugin)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        $plugin->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Status plugin berhasil diperbarui.');
    }
}
