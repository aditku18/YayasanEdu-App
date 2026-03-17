<?php

namespace App\Plugins\PPDB\Services;

use App\Models\PluginInstallation;
use App\Models\Plugin;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PPDBService
{
    protected $foundationId;
    protected $installation;

    public function __construct()
    {
        $this->foundationId = $this->getCurrentFoundationId();
        $this->installation = $this->getInstallation();
    }

    /**
     * Get current foundation ID
     */
    private function getCurrentFoundationId(): ?int
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            if ($user->role === 'foundation_admin' && isset($user->foundation_id)) {
                return $user->foundation_id;
            }
            
            if (isset($user->school_unit_id)) {
                $school = \App\Models\SchoolUnit::find($user->school_unit_id);
                return $school?->foundation_id;
            }
        }

        return null;
    }

    /**
     * Get plugin installation
     */
    private function getInstallation(): ?PluginInstallation
    {
        if (!$this->foundationId) {
            return null;
        }

        $plugin = Plugin::where('name', 'PPDB (Penerimaan Peserta Didik Baru)')->first();
        
        if (!$plugin) {
            return null;
        }

        return PluginInstallation::where('plugin_id', $plugin->id)
            ->where('foundation_id', $this->foundationId)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Check if plugin is properly installed
     */
    public function isInstalled(): bool
    {
        return $this->installation !== null;
    }

    /**
     * Get plugin settings
     */
    public function getSettings(): array
    {
        return $this->installation?->settings ?? config('ppdb', []);
    }

    /**
     * Update plugin settings
     */
    public function updateSettings(array $settings): bool
    {
        if (!$this->installation) {
            return false;
        }

        $this->installation->settings = array_merge(
            $this->installation->settings ?? [],
            $settings
        );
        
        $this->installation->last_updated_at = now();
        
        return $this->installation->save();
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): array
    {
        if (!$this->isInstalled()) {
            return [];
        }

        $cacheKey = "ppdb_dashboard_stats_{$this->foundationId}";
        
        return Cache::remember($cacheKey, config('ppdb.performance.cache_duration', 300), function () {
            $query = \App\Models\PPDBApplicant::query();
            
            // Apply foundation filter
            if (auth()->user()->role !== 'foundation_admin') {
                $query->where('school_unit_id', auth()->user()->school_unit_id);
            }

            $stats = [
                'total_applicants' => $query->count(),
                'pending' => (clone $query)->where('status', 'pending')->count(),
                'verified' => (clone $query)->where('status', 'verified')->count(),
                'approved' => (clone $query)->where('status', 'approved')->count(),
                'rejected' => (clone $query)->where('status', 'rejected')->count(),
                'enrolled' => (clone $query)->where('status', 'enrolled')->count(),
            ];

            // Get wave statistics
            $wavesQuery = \App\Models\PPDBWave::where('status', 'active');
            if (auth()->user()->role !== 'foundation_admin') {
                $wavesQuery->where('school_unit_id', auth()->user()->school_unit_id);
            }
            
            $waves = $wavesQuery->with('major')->get();
            
            $totalQuota = 0;
            $totalUsed = 0;
            
            foreach ($waves as $wave) {
                $applicantCount = \App\Models\PPDBApplicant::where('ppdb_wave_id', $wave->id)->count();
                $wave->applicants_count = $applicantCount;
                
                if ($wave->quota !== null) {
                    $totalQuota += $wave->quota;
                    $totalUsed += $applicantCount;
                }
            }

            $stats['quota_remaining'] = $totalQuota > 0 ? max(0, $totalQuota - $totalUsed) : null;
            $stats['quota_total'] = $totalQuota;
            $stats['quota_used'] = $totalUsed;
            $stats['quota_percentage'] = $totalQuota > 0 ? round(($totalUsed / $totalQuota) * 100, 2) : 0;
            $stats['active_waves'] = $waves;

            return $stats;
        });
    }

    /**
     * Get active waves for public display
     */
    public function getActiveWaves(): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = "ppdb_active_waves_{$this->foundationId}";
        
        return Cache::remember($cacheKey, config('ppdb.performance.cache_duration', 300), function () {
            return \App\Models\PPDBWave::where('status', 'active')
                ->with(['fees', 'major'])
                ->get()
                ->map(function ($wave) {
                    $wave->applicants_count = \App\Models\PPDBApplicant::where('ppdb_wave_id', $wave->id)->count();
                    $wave->is_full = $wave->quota !== null && $wave->applicants_count >= $wave->quota;
                    return $wave;
                });
        });
    }

    /**
     * Check if wave is available for registration
     */
    public function isWaveAvailable(int $waveId): array
    {
        $wave = \App\Models\PPDBWave::with('fees.component')->findOrFail($waveId);
        
        // Check if wave is active
        if ($wave->status !== 'active') {
            return ['available' => false, 'reason' => 'Gelombang tidak aktif'];
        }

        // Check quota
        if ($wave->quota !== null) {
            $applicantCount = \App\Models\PPDBApplicant::where('ppdb_wave_id', $wave->id)->count();
            if ($applicantCount >= $wave->quota) {
                return ['available' => false, 'reason' => 'Kuota gelombang telah terpenuhi'];
            }
        }

        // Check registration period
        if ($wave->registration_start && $wave->registration_end) {
            $now = now();
            if ($now->lt($wave->registration_start)) {
                return ['available' => false, 'reason' => 'Pendaftaran belum dibuka'];
            }
            if ($now->gt($wave->registration_end)) {
                return ['available' => false, 'reason' => 'Pendaftaran telah ditutup'];
            }
        }

        return ['available' => true, 'wave' => $wave];
    }

    /**
     * Generate registration number
     */
    public function generateRegistrationNumber(int $waveId): string
    {
        $wave = \App\Models\PPDBWave::findOrFail($waveId);
        $school = \App\Models\SchoolUnit::findOrFail($wave->school_unit_id);
        
        $format = config('ppdb.registration.registration_number_format', 'PPDB-{year}-{school_code}-{sequence}');
        $year = now()->year;
        $schoolCode = $school->code ?? 'SCH';
        
        // Get last sequence number for this wave
        $lastSequence = \App\Models\PPDBApplicant::where('ppdb_wave_id', $waveId)
            ->whereRaw('YEAR(created_at) = ?', [$year])
            ->max('sequence_number') ?? 0;
        
        $sequence = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
        
        return str_replace(
            ['{year}', '{school_code}', '{sequence}'],
            [$year, $schoolCode, $sequence],
            $format
        );
    }

    /**
     * Clear cache for foundation
     */
    public function clearCache(): void
    {
        $patterns = [
            "ppdb_dashboard_stats_{$this->foundationId}",
            "ppdb_active_waves_{$this->foundationId}",
            "ppdb_wave_stats_{$this->foundationId}_*",
        ];

        foreach ($patterns as $pattern) {
            if (str_contains($pattern, '*')) {
                Cache::forgetMatching($pattern);
            } else {
                Cache::forget($pattern);
            }
        }
    }

    /**
     * Get plugin version
     */
    public function getVersion(): string
    {
        return config('ppdb.version', '2.0.0');
    }

    /**
     * Check if plugin needs update
     */
    public function needsUpdate(): bool
    {
        if (!$this->installation) {
            return false;
        }

        $currentVersion = $this->getVersion();
        $installedVersion = $this->installation->settings['version'] ?? '1.0.0';

        return version_compare($currentVersion, $installedVersion, '>');
    }

    /**
     * Perform plugin update
     */
    public function update(): bool
    {
        if (!$this->needsUpdate()) {
            return true;
        }

        try {
            DB::beginTransaction();

            // Update version in settings
            $settings = $this->installation->settings ?? [];
            $settings['version'] = $this->getVersion();
            $settings['updated_at'] = now()->toISOString();

            $this->installation->settings = $settings;
            $this->installation->last_updated_at = now();
            $this->installation->save();

            // Run any migration scripts if needed
            $this->runUpdateMigrations();

            DB::commit();

            $this->clearCache();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('PPDB Plugin update failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Run update migrations
     */
    private function runUpdateMigrations(): void
    {
        // Add any version-specific migration logic here
        // For now, just clear cache
        $this->clearCache();
    }
}
