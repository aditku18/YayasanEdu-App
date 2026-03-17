<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Plugin;
use App\Models\PluginInstallation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PluginController extends Controller
{
    /**
     * Display active plugins for the tenant
     */
    public function active()
    {
        $foundationId = $this->getCurrentFoundationId();
        
        // Get active plugin installations for this foundation
        $activeInstallations = PluginInstallation::where('foundation_id', $foundationId)
            ->where('is_active', true)
            ->with('plugin')
            ->get();

        return view('tenant.plugins.active', compact('activeInstallations'));
    }

    /**
     * Display installed plugins for the tenant
     */
    public function installed()
    {
        $foundationId = $this->getCurrentFoundationId();
        
        // Get all plugin installations for this foundation
        $installedPlugins = PluginInstallation::where('foundation_id', $foundationId)
            ->with('plugin')
            ->orderBy('installed_at', 'desc')
            ->get();

        // Get available plugins that are not installed
        $installedPluginIds = $installedPlugins->pluck('plugin_id')->toArray();
        $availablePlugins = Plugin::where('is_available_in_marketplace', true)
            ->whereNotIn('id', $installedPluginIds)
            ->where('status', 'active')
            ->get();

        return view('tenant.plugins.installed', compact('installedPlugins', 'availablePlugins'));
    }

    /**
     * Display plugin purchase/Marketplace page
     */
    public function purchase()
    {
        // Get all available plugins from marketplace
        $plugins = Plugin::where('is_available_in_marketplace', true)
            ->where('status', 'active')
            ->orderBy('featured_label', 'desc')
            ->orderBy('name')
            ->paginate(12);

        // Get categories for filtering
        $categories = Plugin::where('is_available_in_marketplace', true)
            ->where('status', 'active')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort();

        return view('tenant.plugins.purchase', compact('plugins', 'categories'));
    }

    /**
     * Install a plugin for the tenant
     */
    public function install(Request $request, Plugin $plugin)
    {
        $foundationId = $this->getCurrentFoundationId();
        
        try {
            // Check if plugin is already installed
            $existingInstallation = PluginInstallation::where('foundation_id', $foundationId)
                ->where('plugin_id', $plugin->id)
                ->first();

            if ($existingInstallation) {
                return redirect()->back()->with('error', 'Plugin is already installed.');
            }

            // Check if plugin is available for installation
            if (!$plugin->is_available_in_marketplace || $plugin->status !== 'active') {
                return redirect()->back()->with('error', 'Plugin is not available for installation.');
            }

            // Create plugin installation
            $installation = PluginInstallation::create([
                'plugin_id' => $plugin->id,
                'foundation_id' => $foundationId,
                'installed_at' => now(),
                'is_active' => true,
                'installed_by' => Auth::id(),
            ]);

            return redirect()->route('tenant.plugin.installed')
                ->with('success', "Plugin '{$plugin->name}' has been successfully installed.");

        } catch (\Exception $e) {
            \Log::error('Plugin installation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to install plugin. Please try again.');
        }
    }

    /**
     * Uninstall a plugin for the tenant
     */
    public function uninstall(Request $request, Plugin $plugin)
    {
        $foundationId = $this->getCurrentFoundationId();
        
        try {
            // Find the installation
            $installation = PluginInstallation::where('foundation_id', $foundationId)
                ->where('plugin_id', $plugin->id)
                ->first();

            if (!$installation) {
                return redirect()->back()->with('error', 'Plugin installation not found.');
            }

            // Delete the installation
            $installation->delete();

            return redirect()->route('tenant.plugin.installed')
                ->with('success', "Plugin '{$plugin->name}' has been successfully uninstalled.");

        } catch (\Exception $e) {
            \Log::error('Plugin uninstallation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to uninstall plugin. Please try again.');
        }
    }

    /**
     * Activate a plugin
     */
    public function activate(Request $request, Plugin $plugin)
    {
        $foundationId = $this->getCurrentFoundationId();
        
        try {
            $installation = PluginInstallation::where('foundation_id', $foundationId)
                ->where('plugin_id', $plugin->id)
                ->first();

            if (!$installation) {
                return redirect()->back()->with('error', 'Plugin installation not found.');
            }

            $installation->update([
                'is_active' => true,
                'last_updated_at' => now(),
            ]);

            return redirect()->back()->with('success', "Plugin '{$plugin->name}' has been activated.");

        } catch (\Exception $e) {
            \Log::error('Plugin activation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to activate plugin. Please try again.');
        }
    }

    /**
     * Deactivate a plugin
     */
    public function deactivate(Request $request, Plugin $plugin)
    {
        $foundationId = $this->getCurrentFoundationId();
        
        try {
            $installation = PluginInstallation::where('foundation_id', $foundationId)
                ->where('plugin_id', $plugin->id)
                ->first();

            if (!$installation) {
                return redirect()->back()->with('error', 'Plugin installation not found.');
            }

            $installation->update([
                'is_active' => false,
                'last_updated_at' => now(),
            ]);

            return redirect()->back()->with('success', "Plugin '{$plugin->name}' has been deactivated.");

        } catch (\Exception $e) {
            \Log::error('Plugin deactivation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to deactivate plugin. Please try again.');
        }
    }

    /**
     * Show plugin details
     */
    public function show(Plugin $plugin)
    {
        $foundationId = $this->getCurrentFoundationId();
        
        // Check if plugin is installed for this foundation
        $installation = PluginInstallation::where('foundation_id', $foundationId)
            ->where('plugin_id', $plugin->id)
            ->first();

        return view('tenant.plugins.show', compact('plugin', 'installation'));
    }

    /**
     * Update plugin settings
     */
    public function updateSettings(Request $request, Plugin $plugin)
    {
        $foundationId = $this->getCurrentFoundationId();
        
        try {
            $installation = PluginInstallation::where('foundation_id', $foundationId)
                ->where('plugin_id', $plugin->id)
                ->first();

            if (!$installation) {
                return redirect()->back()->with('error', 'Plugin installation not found.');
            }

            // Validate and update settings
            $settings = $request->validate([
                'settings' => 'sometimes|array',
            ]);

            $installation->update([
                'settings' => $settings['settings'] ?? [],
                'last_updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Plugin settings have been updated.');

        } catch (\Exception $e) {
            \Log::error('Plugin settings update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update plugin settings. Please try again.');
        }
    }

    /**
     * Get the current foundation ID for the tenant
     */
    private function getCurrentFoundationId()
    {
        // Try to get foundation ID from tenant context
        if (function_exists('tenant')) {
            $tenantId = tenant('id');
            
            // Get foundation ID from foundations table
            $foundation = \App\Models\Foundation::where('tenant_id', $tenantId)->first();
            if ($foundation) {
                return $foundation->id;
            }
        }
        
        // Fallback: get from authenticated user's school unit
        $user = auth()->user();
        if ($user && $user->school_unit_id) {
            $schoolUnit = $user->schoolUnit;
            if ($schoolUnit && $schoolUnit->foundation_id) {
                return $schoolUnit->foundation_id;
            }
        }
        
        // Default fallback (for testing)
        return 1; // Yayasan Kemala Bhayangkari
    }
}
