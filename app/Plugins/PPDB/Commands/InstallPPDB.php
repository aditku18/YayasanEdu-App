<?php

namespace App\Plugins\PPDB\Commands;

use Illuminate\Console\Command;
use App\Models\Plugin;
use App\Models\PluginInstallation;
use App\Models\Foundation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InstallPPDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ppdb:install {foundation_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install PPDB plugin for a foundation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $foundationId = $this->argument('foundation_id');

        if (!$foundationId) {
            $this->error('Foundation ID is required.');
            $this->info('Available foundations:');
            
            $foundations = Foundation::pluck('name', 'id');
            foreach ($foundations as $id => $name) {
                $this->line("  {$id}: {$name}");
            }
            
            return 1;
        }

        $foundation = Foundation::find($foundationId);
        if (!$foundation) {
            $this->error("Foundation with ID {$foundationId} not found.");
            return 1;
        }

        $this->info("Installing PPDB plugin for: {$foundation->name}");

        try {
            // Check if plugin exists
            $plugin = Plugin::where('name', 'PPDB (Penerimaan Peserta Didik Baru)')->first();
            if (!$plugin) {
                $this->error('PPDB plugin not found in marketplace. Please run PPDBPluginSeeder first.');
                return 1;
            }

            // Check if already installed
            $existingInstallation = PluginInstallation::where('plugin_id', $plugin->id)
                ->where('foundation_id', $foundationId)
                ->first();

            if ($existingInstallation) {
                if ($existingInstallation->is_active) {
                    $this->warn('PPDB plugin is already installed and active for this foundation.');
                    return 0;
                } else {
                    $this->info('Reactivating existing PPDB plugin installation...');
                    $existingInstallation->is_active = true;
                    $existingInstallation->last_updated_at = now();
                    $existingInstallation->save();
                    
                    $this->info('PPDB plugin reactivated successfully!');
                    return 0;
                }
            }

            DB::beginTransaction();

            // Run plugin migrations
            $this->info('Running plugin migrations...');
            $this->call('migrate', [
                '--path' => 'app/Plugins/PPDB/Database/Migrations',
                '--force' => true,
            ]);

            // Create plugin installation record
            $installation = PluginInstallation::create([
                'plugin_id' => $plugin->id,
                'foundation_id' => $foundationId,
                'is_active' => true,
                'installed_at' => now(),
                'installed_by' => 1, // System user
                'settings' => [
                    'version' => '2.0.0',
                    'installed_at' => now()->toISOString(),
                    'features' => config('ppdb.public_features', []),
                    'email_enabled' => config('ppdb.email.enabled', true),
                    'sms_enabled' => config('ppdb.sms.enabled', false),
                    'payment_gateways' => config('ppdb.payment.gateways', []),
                ],
                'last_updated_at' => now(),
            ]);

            // Create default fee components for the foundation
            $this->createDefaultFeeComponents($foundationId);

            // Create default settings
            $this->createDefaultSettings($installation);

            DB::commit();

            $this->info('✅ PPDB plugin installed successfully!');
            $this->info("Installation ID: {$installation->id}");
            $this->info('You can now access PPDB from the admin panel.');

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Installation failed: ' . $e->getMessage());
            
            // Log detailed error
            \Log::error('PPDB Plugin Installation Failed', [
                'foundation_id' => $foundationId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return 1;
        }
    }

    /**
     * Create default fee components
     */
    private function createDefaultFeeComponents($foundationId)
    {
        $defaultComponents = [
            [
                'name' => 'Biaya Pendaftaran',
                'description' => 'Biaya pendaftaran awal untuk proses seleksi',
                'amount' => 250000,
                'type' => 'mandatory',
                'is_active' => true,
            ],
            [
                'name' => 'Biaya Tes',
                'description' => 'Biaya untuk tes masuk (jika ada)',
                'amount' => 150000,
                'type' => 'mandatory',
                'is_active' => true,
            ],
            [
                'name' => 'Biaya Seragam',
                'description' => 'Biaya seragam sekolah',
                'amount' => 500000,
                'type' => 'mandatory',
                'is_active' => true,
            ],
            [
                'name' => 'Biaya Buku',
                'description' => 'Biaya buku pegangan siswa',
                'amount' => 350000,
                'type' => 'mandatory',
                'is_active' => true,
            ],
            [
                'name' => 'Biaya Kegiatan',
                'description' => 'Biaya untuk kegiatan ekstrakurikuler',
                'amount' => 200000,
                'type' => 'optional',
                'is_active' => true,
            ],
        ];

        foreach ($defaultComponents as $component) {
            // Find school units for this foundation
            $schoolUnits = \App\Models\SchoolUnit::where('foundation_id', $foundationId)->get();
            
            foreach ($schoolUnits as $school) {
                \App\Models\PPDBFeeComponent::create(array_merge($component, [
                    'school_unit_id' => $school->id,
                    'created_by' => 1,
                ]));
            }
        }

        $this->info('Default fee components created.');
    }

    /**
     * Create default settings
     */
    private function createDefaultSettings($installation)
    {
        $defaultSettings = [
            'registration' => [
                'require_parent_data' => true,
                'require_previous_school' => true,
                'enable_major_selection' => true,
                'enable_document_upload' => true,
                'auto_generate_registration_number' => true,
                'registration_number_format' => 'PPDB-{year}-{school_code}-{sequence}',
                'allow_edit_after_submit' => false,
                'edit_deadline_hours' => 24,
            ],
            'uploads' => [
                'max_size' => 5120,
                'allowed_types' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
                'storage_path' => 'ppdb/documents',
                'resize_images' => true,
                'image_max_width' => 1200,
                'image_max_height' => 1200,
            ],
            'email' => [
                'enabled' => true,
                'templates' => [
                    'registration_success' => 'ppdb::emails.registration-success',
                    'verification_required' => 'ppdb::emails.verification-required',
                    'approved' => 'ppdb::emails.approved',
                    'rejected' => 'ppdb::emails.rejected',
                    'payment_verified' => 'ppdb::emails.payment-verified',
                ],
                'queue' => true,
            ],
            'payment' => [
                'gateways' => [
                    'manual' => [
                        'enabled' => true,
                        'name' => 'Manual Transfer',
                        'instructions' => 'Silakan transfer ke rekening yang tersedia.',
                    ],
                ],
                'auto_verify' => false,
            ],
            'quota' => [
                'enable_global_quota' => true,
                'enable_major_quota' => true,
                'allow_waitlist' => true,
                'waitlist_limit' => 50,
                'quota_check_strategy' => 'strict',
            ],
            'security' => [
                'require_captcha' => false,
                'rate_limit_registration' => true,
                'max_registrations_per_ip' => 5,
                'rate_limit_window' => 3600,
                'validate_nik' => true,
                'validate_phone' => true,
                'validate_email_domain' => false,
            ],
            'performance' => [
                'cache_applicant_count' => true,
                'cache_wave_statistics' => true,
                'cache_duration' => 300,
                'lazy_load_relationships' => true,
                'optimize_image_uploads' => true,
            ],
        ];

        $installation->settings = array_merge($installation->settings ?? [], $defaultSettings);
        $installation->save();

        $this->info('Default settings configured.');
    }
}
