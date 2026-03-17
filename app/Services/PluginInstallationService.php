<?php

namespace App\Services;

use App\Models\Foundation;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\Plugin;
use App\Models\PluginInstallation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PluginInstallationService
{
    /**
     * Install plugin after successful payment
     */
    public function installAfterPayment(Invoice $invoice): bool
    {
        try {
            DB::beginTransaction();
            
            // Get plugin details from invoice items
            $items = json_decode($invoice->items, true);
            $pluginId = $items['plugin_id'] ?? null;
            
            if (!$pluginId) {
                throw new \Exception('Plugin ID not found in invoice items');
            }
            
            $plugin = Plugin::findOrFail($pluginId);
            $foundation = $invoice->foundation;
            
            // Check if already installed
            $existingInstallation = PluginInstallation::where('foundation_id', $foundation->id)
                ->where('plugin_id', $plugin->id)
                ->first();
            
            if ($existingInstallation && $existingInstallation->is_active) {
                throw new \Exception('Plugin is already installed and active');
            }
            
            // Create or update plugin installation
            $installation = PluginInstallation::updateOrCreate(
                [
                    'plugin_id' => $plugin->id,
                    'foundation_id' => $foundation->id,
                ],
                [
                    'is_active' => true,
                    'installed_at' => now(),
                    'installed_by' => $invoice->created_by ?? 1,
                    'last_updated_at' => now(),
                    'installation_type' => 'paid',
                    'subscription_type' => 'paid',
                    'subscription_amount' => $invoice->amount,
                    'subscription_invoice_id' => $invoice->id,
                    'settings' => $this->getDefaultPluginSettings($plugin),
                ]
            );
            
            // Update invoice with plugin installation reference
            $invoice->update([
                'status' => 'completed',
                'paid_at' => now(),
                'items' => json_encode(array_merge($items, [
                    'installed_at' => now()->toISOString(),
                    'installation_id' => $installation->id,
                ])),
            ]);
            
            // Run plugin-specific installation logic
            $this->runPluginSpecificInstallation($plugin, $foundation);
            
            // Clear cache
            $this->clearPluginCache($foundation);
            
            // Log installation
            Log::info('Plugin installed after payment', [
                'plugin_id' => $plugin->id,
                'plugin_name' => $plugin->name,
                'foundation_id' => $foundation->id,
                'invoice_id' => $invoice->id,
                'installation_id' => $installation->id,
                'amount' => $invoice->amount,
            ]);
            
            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to install plugin after payment', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Install plugins for a foundation based on their plan
     */
    public function installPackagePlugins(Foundation $foundation, Plan $plan): bool
    {
        try {
            DB::beginTransaction();

            // 1. Install included plugins automatically
            $includedPlugins = $this->installIncludedPlugins($foundation, $plan);
            
            // 2. Install additional plugins if any
            $additionalPlugins = $this->installAdditionalPlugins($foundation);
            
            // 3. Update foundation with plugin info
            $foundation->update([
                'included_plugins' => $plan->included_plugins ?? [],
                'plugin_slots' => $plan->plugin_slots ?? 0,
                'plugins_installed_at' => now(),
            ]);

            // 4. Clear cache and notify
            $this->clearPluginCache($foundation);
            $this->notifyPluginInstallation($foundation, $plan, $includedPlugins, $additionalPlugins);

            DB::commit();

            Log::info('Package plugins installed successfully', [
                'foundation_id' => $foundation->id,
                'plan_id' => $plan->id,
                'included_count' => $includedPlugins->count(),
                'additional_count' => $additionalPlugins->count(),
            ]);

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Package plugin installation failed', [
                'foundation_id' => $foundation->id,
                'plan_id' => $plan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    /**
     * Install included plugins from plan
     */
    private function installIncludedPlugins(Foundation $foundation, Plan $plan): \Illuminate\Database\Eloquent\Collection
    {
        $installedPlugins = collect();
        
        if (empty($plan->included_plugins)) {
            return $installedPlugins;
        }

        foreach ($plan->included_plugins as $pluginSlug) {
            $plugin = Plugin::where('slug', $pluginSlug)->first();
            
            if ($plugin) {
                $installation = $this->installPlugin($foundation, $plugin, 'included');
                if ($installation) {
                    $installedPlugins->push($plugin);
                }
            } else {
                Log::warning('Plugin not found for installation', [
                    'plugin_slug' => $pluginSlug,
                    'foundation_id' => $foundation->id,
                ]);
            }
        }

        return $installedPlugins;
    }

    /**
     * Install additional plugins selected by user
     */
    private function installAdditionalPlugins(Foundation $foundation): \Illuminate\Database\Eloquent\Collection
    {
        $installedPlugins = collect();
        
        if (empty($foundation->additional_plugins)) {
            return $installedPlugins;
        }

        foreach ($foundation->additional_plugins as $pluginId) {
            $plugin = Plugin::find($pluginId);
            
            if ($plugin) {
                // Check if foundation has available slots
                if ($this->hasAvailablePluginSlots($foundation)) {
                    $installation = $this->installPlugin($foundation, $plugin, 'additional');
                    if ($installation) {
                        $installedPlugins->push($plugin);
                    }
                } else {
                    Log::warning('No available plugin slots', [
                        'foundation_id' => $foundation->id,
                        'plugin_id' => $pluginId,
                    ]);
                }
            } else {
                Log::warning('Plugin slots exceeded', [
                    'foundation_id' => $foundation->id,
                    'plugin_id' => $pluginId,
                    'available_slots' => $foundation->available_plugin_slots,
                ]);
            }
        }

        return $installedPlugins;
    }

    /**
     * Install a single plugin for foundation
     */
    private function installPlugin(Foundation $foundation, Plugin $plugin, string $installationType): ?PluginInstallation
    {
        try {
            // Check if already installed
            $existingInstallation = PluginInstallation::where('foundation_id', $foundation->id)
                ->where('plugin_id', $plugin->id)
                ->first();

            if ($existingInstallation) {
                // Update existing installation
                $existingInstallation->update([
                    'is_active' => true,
                    'installed_at' => now(),
                    'installation_type' => $installationType,
                ]);
                
                return $existingInstallation;
            }

            // Create new installation
            $installation = PluginInstallation::create([
                'plugin_id' => $plugin->id,
                'foundation_id' => $foundation->id,
                'is_active' => true,
                'installed_at' => now(),
                'installed_by' => 1, // System user
                'installation_type' => $installationType,
                'settings' => $this->getDefaultPluginSettings($plugin),
                'last_updated_at' => now(),
            ]);

            // Run plugin-specific installation logic if available
            $this->runPluginSpecificInstallation($plugin, $foundation);

            return $installation;

        } catch (\Exception $e) {
            Log::error('Failed to install plugin', [
                'plugin_id' => $plugin->id,
                'foundation_id' => $foundation->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Check if foundation has available plugin slots
     */
    private function hasAvailablePluginSlots(Foundation $foundation): bool
    {
        $totalSlots = $foundation->plugin_slots ?? 0;
        $usedSlots = $foundation->total_plugins;
        
        return $usedSlots < $totalSlots;
    }

    /**
     * Get default settings for plugin installation
     */
    private function getDefaultPluginSettings(Plugin $plugin): array
    {
        $defaultSettings = [
            'version' => $plugin->version,
            'installed_at' => now()->toISOString(),
            'auto_updated' => true,
        ];

        // Plugin-specific default settings
        switch ($plugin->slug) {
            case 'ppdb':
                return array_merge($defaultSettings, [
                    'public_registration' => true,
                    'auto_approve' => false,
                    'require_documents' => true,
                    'email_notifications' => true,
                ]);
                
            case 'cbt':
                return array_merge($defaultSettings, [
                    'enable_timer' => true,
                    'shuffle_questions' => false,
                    'show_results_immediately' => false,
                    'email_notifications' => true,
                ]);
                
            case 'attendance':
                return array_merge($defaultSettings, [
                    'auto_check_in' => false,
                    'require_location' => false,
                    'email_notifications' => true,
                ]);
                
            default:
                return $defaultSettings;
        }
    }

    /**
     * Run plugin-specific installation logic
     */
    private function runPluginSpecificInstallation(Plugin $plugin, Foundation $foundation): void
    {
        // This would call plugin-specific installation hooks
        // For now, just log the installation
        Log::info('Running plugin-specific installation', [
            'plugin_slug' => $plugin->slug,
            'foundation_id' => $foundation->id,
        ]);

        // Example: Run migrations for specific plugin
        $this->runPluginMigrations($plugin);
        
        // Example: Create default data for plugin
        $this->createPluginDefaultData($plugin, $foundation);
    }

    /**
     * Run plugin migrations
     */
    private function runPluginMigrations(Plugin $plugin): void
    {
        try {
            $migrationPath = base_path("app/Plugins/" . ucfirst($plugin->slug) . "/Database/Migrations");
            
            if (is_dir($migrationPath)) {
                // This would run plugin-specific migrations
                Log::info('Running plugin migrations', [
                    'plugin_slug' => $plugin->slug,
                    'migration_path' => $migrationPath,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to run plugin migrations', [
                'plugin_slug' => $plugin->slug,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Create default data for plugin
     */
    private function createPluginDefaultData(Plugin $plugin, Foundation $foundation): void
    {
        try {
            switch ($plugin->slug) {
                case 'ppdb':
                    $this->createPPDBDefaultData($foundation);
                    break;
                    
                case 'cbt':
                    $this->createCBTDefaultData($foundation);
                    break;
                    
                case 'attendance':
                    $this->createAttendanceDefaultData($foundation);
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Failed to create plugin default data', [
                'plugin_slug' => $plugin->slug,
                'foundation_id' => $foundation->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Create PPDB default data
     */
    private function createPPDBDefaultData(Foundation $foundation): void
    {
        // Create default fee components for PPDB
        $defaultFeeComponents = [
            [
                'name' => 'Biaya Pendaftaran',
                'description' => 'Biaya pendaftaran awal',
                'amount' => 250000,
                'type' => 'mandatory',
                'is_active' => true,
            ],
            [
                'name' => 'Biaya Tes',
                'description' => 'Biaya tes masuk',
                'amount' => 150000,
                'type' => 'mandatory',
                'is_active' => true,
            ],
        ];

        foreach ($defaultFeeComponents as $component) {
            // Check if foundation has school units
            $schoolUnits = $foundation->schoolUnits;
            
            foreach ($schoolUnits as $school) {
                \App\Models\PPDBFeeComponent::updateOrCreate(
                    [
                        'name' => $component['name'],
                        'school_unit_id' => $school->id,
                    ],
                    array_merge($component, [
                        'created_by' => 1,
                    ])
                );
            }
        }
    }

    /**
     * Create CBT default data
     */
    private function createCBTDefaultData(Foundation $foundation): void
    {
        // Create default CBT settings
        Log::info('Creating CBT default data', [
            'foundation_id' => $foundation->id,
        ]);
    }

    /**
     * Create Attendance default data
     */
    private function createAttendanceDefaultData(Foundation $foundation): void
    {
        // Create default attendance settings
        Log::info('Creating Attendance default data', [
            'foundation_id' => $foundation->id,
        ]);
    }

    /**
     * Clear plugin cache for foundation
     */
    private function clearPluginCache(Foundation $foundation): void
    {
        $cacheKeys = [
            "foundation_plugins_{$foundation->id}",
            "plugin_installations_{$foundation->id}",
            "active_plugins_{$foundation->id}",
        ];

        foreach ($cacheKeys as $key) {
            cache()->forget($key);
        }
    }

    /**
     * Send notification about plugin installation
     */
    private function notifyPluginInstallation(
        Foundation $foundation, 
        Plan $plan, 
        \Illuminate\Database\Eloquent\Collection $includedPlugins,
        \Illuminate\Database\Eloquent\Collection $additionalPlugins
    ): void {
        try {
            // Log the installation
            Log::info('Plugin installation notification sent', [
                'foundation_id' => $foundation->id,
                'plan_name' => $plan->name,
                'included_count' => $includedPlugins->count(),
                'additional_count' => $additionalPlugins->count(),
            ]);

            // Here you could send email notifications, create activity logs, etc.
            // For now, just log it
            
        } catch (\Exception $e) {
            Log::error('Failed to send plugin installation notification', [
                'foundation_id' => $foundation->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update plugin slots for foundation
     */
    public function updatePluginSlots(Foundation $foundation, int $newSlots): bool
    {
        try {
            $foundation->update(['plugin_slots' => $newSlots]);
            
            Log::info('Plugin slots updated', [
                'foundation_id' => $foundation->id,
                'new_slots' => $newSlots,
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to update plugin slots', [
                'foundation_id' => $foundation->id,
                'new_slots' => $newSlots,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }

    /**
     * Get plugin installation summary for foundation
     */
    public function getInstallationSummary(Foundation $foundation): array
    {
        $includedPlugins = $foundation->includedPlugins;
        $additionalPlugins = $foundation->additionalPlugins;
        $totalSlots = $foundation->plugin_slots ?? 0;
        $usedSlots = $foundation->total_plugins;
        $availableSlots = max(0, $totalSlots - $usedSlots);

        return [
            'included_plugins' => $includedPlugins->map(function ($plugin) {
                return [
                    'id' => $plugin->id,
                    'name' => $plugin->name,
                    'slug' => $plugin->slug,
                    'price' => $plugin->price,
                ];
            })->toArray(),
            
            'additional_plugins' => $additionalPlugins->map(function ($plugin) {
                return [
                    'id' => $plugin->id,
                    'name' => $plugin->name,
                    'slug' => $plugin->slug,
                    'price' => $plugin->price,
                ];
            })->toArray(),
            
            'total_slots' => $totalSlots,
            'used_slots' => $usedSlots,
            'available_slots' => $availableSlots,
            'plugins_installed_at' => $foundation->plugins_installed_at,
        ];
    }
}
