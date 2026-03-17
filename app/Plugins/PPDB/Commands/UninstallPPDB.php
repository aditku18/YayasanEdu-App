<?php

namespace App\Plugins\PPDB\Commands;

use Illuminate\Console\Command;
use App\Models\Plugin;
use App\Models\PluginInstallation;
use App\Models\Foundation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UninstallPPDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ppdb:uninstall {foundation_id?} {--force : Force uninstall without confirmation} {--keep-data : Keep PPDB data after uninstall}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall PPDB plugin from a foundation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $foundationId = $this->argument('foundation_id');
        $force = $this->option('force');
        $keepData = $this->option('keep-data');

        if (!$foundationId) {
            $this->error('Foundation ID is required.');
            $this->info('Available foundations with PPDB installed:');
            
            $foundationsWithPPDB = PluginInstallation::whereHas('plugin', function($query) {
                $query->where('name', 'PPDB (Penerimaan Peserta Didik Baru)');
            })->with(['foundation', 'plugin'])->get();

            foreach ($foundationsWithPPDB as $installation) {
                $status = $installation->is_active ? 'Active' : 'Inactive';
                $this->line("  {$installation->foundation_id}: {$installation->foundation->name} ({$status})");
            }
            
            return 1;
        }

        $foundation = Foundation::find($foundationId);
        if (!$foundation) {
            $this->error("Foundation with ID {$foundationId} not found.");
            return 1;
        }

        // Check if PPDB is installed
        $plugin = Plugin::where('name', 'PPDB (Penerimaan Peserta Didik Baru)')->first();
        if (!$plugin) {
            $this->error('PPDB plugin not found in marketplace.');
            return 1;
        }

        $installation = PluginInstallation::where('plugin_id', $plugin->id)
            ->where('foundation_id', $foundationId)
            ->first();

        if (!$installation) {
            $this->error('PPDB plugin is not installed for this foundation.');
            return 1;
        }

        $this->info("Uninstalling PPDB plugin from: {$foundation->name}");

        // Confirmation prompt
        if (!$force) {
            $this->warn('This will remove PPDB plugin from the foundation.');
            if (!$keepData) {
                $this->warn('All PPDB data (applicants, waves, fees) will be permanently deleted!');
            }
            
            $confirm = $this->confirm('Do you want to continue?');
            if (!$confirm) {
                $this->info('Uninstallation cancelled.');
                return 0;
            }
        }

        try {
            DB::beginTransaction();

            // Get data statistics before uninstall
            $stats = $this->getDataStatistics($foundationId);
            $this->displayDataStatistics($stats);

            // Deactivate plugin first
            $installation->is_active = false;
            $installation->last_updated_at = now();
            $installation->save();

            if (!$keepData) {
                $this->info('Removing PPDB data...');
                $this->removePPDBData($foundationId);
            } else {
                $this->info('Keeping PPDB data as requested.');
            }

            // Remove plugin installation record
            $installation->delete();

            // Clean up cache
            $this->cleanupCache($foundationId);

            DB::commit();

            $this->info('✅ PPDB plugin uninstalled successfully!');
            
            if ($keepData) {
                $this->warn('⚠️  PPDB data has been preserved. To remove data later, run: php artisan ppdb:cleanup-data ' . $foundationId);
            }

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Uninstallation failed: ' . $e->getMessage());
            
            // Log detailed error
            \Log::error('PPDB Plugin Uninstallation Failed', [
                'foundation_id' => $foundationId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return 1;
        }
    }

    /**
     * Get data statistics
     */
    private function getDataStatistics($foundationId)
    {
        // Get school units for this foundation
        $schoolUnits = \App\Models\SchoolUnit::where('foundation_id', $foundationId)->pluck('id');

        return [
            'applicants' => \App\Models\PPDBApplicant::whereIn('school_unit_id', $schoolUnits)->count(),
            'waves' => \App\Models\PPDBWave::whereIn('school_unit_id', $schoolUnits)->count(),
            'fee_components' => \App\Models\PPDBFeeComponent::whereIn('school_unit_id', $schoolUnits)->count(),
            'wave_fees' => \App\Models\PPDBWaveFee::whereHas('wave', function($query) use ($schoolUnits) {
                $query->whereIn('school_unit_id', $schoolUnits);
            })->count(),
            'schools' => $schoolUnits->count(),
        ];
    }

    /**
     * Display data statistics
     */
    private function displayDataStatistics($stats)
    {
        $this->info('Current PPDB Data:');
        $this->line("  Applicants: {$stats['applicants']}");
        $this->line("  Waves: {$stats['waves']}");
        $this->line("  Fee Components: {$stats['fee_components']}");
        $this->line("  Wave Fees: {$stats['wave_fees']}");
        $this->line("  Schools: {$stats['schools']}");
    }

    /**
     * Remove PPDB data
     */
    private function removePPDBData($foundationId)
    {
        // Get school units for this foundation
        $schoolUnits = \App\Models\SchoolUnit::where('foundation_id', $foundationId)->pluck('id');

        // Remove in order of dependencies
        $this->withProgressBar(5, function() use ($schoolUnits) {
            // Remove wave fees
            \App\Models\PPDBWaveFee::whereHas('wave', function($query) use ($schoolUnits) {
                $query->whereIn('school_unit_id', $schoolUnits);
            })->delete();

            // Remove fee components
            \App\Models\PPDBFeeComponent::whereIn('school_unit_id', $schoolUnits)->delete();

            // Remove applicants
            \App\Models\PPDBApplicant::whereIn('school_unit_id', $schoolUnits)->delete();

            // Remove waves
            \App\Models\PPDBWave::whereIn('school_unit_id', $schoolUnits)->delete();

            // Remove uploaded documents
            $this->removeUploadedDocuments($schoolUnits);
        });

        $this->newLine();
        $this->info('All PPDB data removed.');
    }

    /**
     * Remove uploaded documents
     */
    private function removeUploadedDocuments($schoolUnits)
    {
        try {
            $storagePath = 'ppdb/documents';
            
            if (Storage::disk('public')->exists($storagePath)) {
                // Get all directories for this foundation's schools
                $directories = Storage::disk('public')->directories($storagePath);
                
                foreach ($directories as $directory) {
                    // Check if directory belongs to this foundation
                    $applicantId = basename($directory);
                    $applicant = \App\Models\PPDBApplicant::find($applicantId);
                    
                    if ($applicant && in_array($applicant->school_unit_id, $schoolUnits->toArray())) {
                        Storage::disk('public')->deleteDirectory($directory);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->warn('Could not remove some uploaded files: ' . $e->getMessage());
        }
    }

    /**
     * Clean up cache
     */
    private function cleanupCache($foundationId)
    {
        try {
            $cacheKeys = [
                "ppdb_dashboard_stats_{$foundationId}",
                "ppdb_active_waves_{$foundationId}",
                "ppdb_wave_stats_{$foundationId}_*",
            ];

            foreach ($cacheKeys as $key) {
                if (str_contains($key, '*')) {
                    // This would require a custom cache implementation
                    // For now, just clear the specific key
                    cache()->forget(str_replace('*', '', $key));
                } else {
                    cache()->forget($key);
                }
            }

            $this->info('Cache cleared.');
        } catch (\Exception $e) {
            $this->warn('Could not clear cache: ' . $e->getMessage());
        }
    }
}
