<?php

namespace App\Plugins\PPDB\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigratePPDBData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ppdb:migrate-data {--dry-run : Show what will be migrated without executing} {--force : Force migration even if plugin is installed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing PPDB data to plugin format';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        $this->info('PPDB Data Migration Tool');
        $this->newLine();

        // Check if PPDB tables exist
        if (!$this->checkPPDBTablesExist()) {
            $this->error('PPDB tables not found. Nothing to migrate.');
            return 1;
        }

        // Check if plugin is already installed for any foundation
        $installedFoundations = $this->getInstalledFoundations();
        if (!empty($installedFoundations) && !$force) {
            $this->warn('PPDB plugin is already installed for the following foundations:');
            foreach ($installedFoundations as $foundationId => $foundationName) {
                $this->line("  - {$foundationId}: {$foundationName}");
            }
            
            $confirm = $this->confirm('Continue migration? This may duplicate data.');
            if (!$confirm) {
                $this->info('Migration cancelled.');
                return 0;
            }
        }

        // Get migration statistics
        $stats = $this->getMigrationStatistics();
        $this->displayMigrationStatistics($stats);

        if ($dryRun) {
            $this->info('DRY RUN MODE - No data will be modified.');
            return 0;
        }

        if (!$this->confirm('Proceed with data migration?')) {
            $this->info('Migration cancelled.');
            return 0;
        }

        try {
            DB::beginTransaction();

            $this->info('Starting data migration...');

            // Migrate data in order of dependencies
            $this->migrateFeeComponents();
            $this->migrateWaves();
            $this->migrateWaveFees();
            $this->migrateApplicants();

            DB::commit();

            $this->info('✅ Data migration completed successfully!');
            $this->info('Run "php artisan ppdb:install <foundation_id>" for each foundation to activate the plugin.');

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Migration failed: ' . $e->getMessage());
            
            // Log detailed error
            \Log::error('PPDB Data Migration Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return 1;
        }
    }

    /**
     * Check if PPDB tables exist
     */
    private function checkPPDBTablesExist()
    {
        $tables = [
            'p_p_d_b_waves',
            'p_p_d_b_applicants',
            'p_p_d_b_fee_components',
            'p_p_d_b_wave_fees',
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get foundations with PPDB plugin installed
     */
    private function getInstalledFoundations()
    {
        return \App\Models\PluginInstallation::whereHas('plugin', function($query) {
                $query->where('name', 'PPDB (Penerimaan Peserta Didik Baru)');
            })
            ->where('is_active', true)
            ->with(['foundation'])
            ->pluck('foundation.name', 'foundation_id')
            ->toArray();
    }

    /**
     * Get migration statistics
     */
    private function getMigrationStatistics()
    {
        return [
            'fee_components' => DB::table('p_p_d_b_fee_components')->count(),
            'waves' => DB::table('p_p_d_b_waves')->count(),
            'wave_fees' => DB::table('p_p_d_b_wave_fees')->count(),
            'applicants' => DB::table('p_p_d_b_applicants')->count(),
        ];
    }

    /**
     * Display migration statistics
     */
    private function displayMigrationStatistics($stats)
    {
        $this->info('Data to migrate:');
        $this->line("  Fee Components: {$stats['fee_components']}");
        $this->line("  Waves: {$stats['waves']}");
        $this->line("  Wave Fees: {$stats['wave_fees']}");
        $this->line("  Applicants: {$stats['applicants']}");
        $this->newLine();
    }

    /**
     * Migrate fee components
     */
    private function migrateFeeComponents()
    {
        $this->info('Migrating fee components...');
        
        $feeComponents = DB::table('p_p_d_b_fee_components')->get();
        
        $this->withProgressBar($feeComponents->count(), function() use ($feeComponents) {
            foreach ($feeComponents as $component) {
                // Check if already migrated
                if (\App\Models\PPDBFeeComponent::where('id', $component->id)->exists()) {
                    continue;
                }

                \App\Models\PPDBFeeComponent::create([
                    'id' => $component->id,
                    'name' => $component->name,
                    'description' => $component->description,
                    'amount' => $component->amount,
                    'type' => $component->type,
                    'is_active' => $component->is_active ?? true,
                    'school_unit_id' => $component->school_unit_id,
                    'created_by' => $component->created_by ?? 1,
                    'created_at' => $component->created_at ?? now(),
                    'updated_at' => $component->updated_at ?? now(),
                ]);
            }
        });

        $this->newLine();
    }

    /**
     * Migrate waves
     */
    private function migrateWaves()
    {
        $this->info('Migrating waves...');
        
        $waves = DB::table('p_p_d_b_waves')->get();
        
        $this->withProgressBar($waves->count(), function() use ($waves) {
            foreach ($waves as $wave) {
                // Check if already migrated
                if (\App\Models\PPDBWave::where('id', $wave->id)->exists()) {
                    continue;
                }

                \App\Models\PPDBWave::create([
                    'id' => $wave->id,
                    'name' => $wave->name,
                    'school_unit_id' => $wave->school_unit_id,
                    'academic_year_id' => $wave->academic_year_id,
                    'major_id' => $wave->major_id,
                    'description' => $wave->description,
                    'quota' => $wave->quota,
                    'registration_start' => $wave->registration_start,
                    'registration_end' => $wave->registration_end,
                    'test_date' => $wave->test_date,
                    'announcement_date' => $wave->announcement_date,
                    'status' => $wave->status ?? 'active',
                    'created_by' => $wave->created_by ?? 1,
                    'created_at' => $wave->created_at ?? now(),
                    'updated_at' => $wave->updated_at ?? now(),
                ]);
            }
        });

        $this->newLine();
    }

    /**
     * Migrate wave fees
     */
    private function migrateWaveFees()
    {
        $this->info('Migrating wave fees...');
        
        $waveFees = DB::table('p_p_d_b_wave_fees')->get();
        
        $this->withProgressBar($waveFees->count(), function() use ($waveFees) {
            foreach ($waveFees as $waveFee) {
                // Check if already migrated
                if (\App\Models\PPDBWaveFee::where('id', $waveFee->id)->exists()) {
                    continue;
                }

                \App\Models\PPDBWaveFee::create([
                    'id' => $waveFee->id,
                    'ppdb_wave_id' => $waveFee->ppdb_wave_id,
                    'fee_component_id' => $waveFee->fee_component_id,
                    'major_id' => $waveFee->major_id,
                    'amount' => $waveFee->amount,
                    'is_active' => $waveFee->is_active ?? true,
                    'created_at' => $waveFee->created_at ?? now(),
                    'updated_at' => $waveFee->updated_at ?? now(),
                ]);
            }
        });

        $this->newLine();
    }

    /**
     * Migrate applicants
     */
    private function migrateApplicants()
    {
        $this->info('Migrating applicants...');
        
        $applicants = DB::table('p_p_d_b_applicants')->get();
        
        $this->withProgressBar($applicants->count(), function() use ($applicants) {
            foreach ($applicants as $applicant) {
                // Check if already migrated
                if (\App\Models\PPDBApplicant::where('id', $applicant->id)->exists()) {
                    continue;
                }

                // Handle JSON fields
                $documents = null;
                $paymentData = null;
                
                if (isset($applicant->documents)) {
                    $documents = is_string($applicant->documents) 
                        ? json_decode($applicant->documents, true) 
                        : $applicant->documents;
                }
                
                if (isset($applicant->payment_data)) {
                    $paymentData = is_string($applicant->payment_data) 
                        ? json_decode($applicant->payment_data, true) 
                        : $applicant->payment_data;
                }

                \App\Models\PPDBApplicant::create([
                    'id' => $applicant->id,
                    'registration_number' => $applicant->registration_number,
                    'ppdb_wave_id' => $applicant->ppdb_wave_id,
                    'school_unit_id' => $applicant->school_unit_id,
                    'academic_year_id' => $applicant->academic_year_id,
                    'major_id' => $applicant->major_id,
                    'sequence_number' => $applicant->sequence_number,
                    'name' => $applicant->name,
                    'email' => $applicant->email,
                    'phone' => $applicant->phone,
                    'nik' => $applicant->nik,
                    'place_of_birth' => $applicant->place_of_birth,
                    'date_of_birth' => $applicant->date_of_birth,
                    'gender' => $applicant->gender,
                    'address' => $applicant->address,
                    'village' => $applicant->village,
                    'district' => $applicant->district,
                    'city' => $applicant->city,
                    'province' => $applicant->province,
                    'postal_code' => $applicant->postal_code,
                    'father_name' => $applicant->father_name,
                    'father_phone' => $applicant->father_phone,
                    'father_occupation' => $applicant->father_occupation,
                    'mother_name' => $applicant->mother_name,
                    'mother_phone' => $applicant->mother_phone,
                    'mother_occupation' => $applicant->mother_occupation,
                    'parent_address' => $applicant->parent_address,
                    'previous_school' => $applicant->previous_school,
                    'previous_school_address' => $applicant->previous_school_address,
                    'graduation_year' => $applicant->graduation_year,
                    'graduation_certificate_number' => $applicant->graduation_certificate_number,
                    'religion' => $applicant->religion,
                    'blood_type' => $applicant->blood_type,
                    'height' => $applicant->height,
                    'weight' => $applicant->weight,
                    'special_needs' => $applicant->special_needs,
                    'hobbies' => $applicant->hobbies,
                    'achievements' => $applicant->achievements,
                    'status' => $applicant->status ?? 'pending',
                    'rejection_reason' => $applicant->rejection_reason,
                    'documents' => $documents,
                    'documents_uploaded_at' => $applicant->documents_uploaded_at,
                    'payment_method' => $applicant->payment_method,
                    'payment_proof_path' => $applicant->payment_proof_path,
                    'payment_data' => $paymentData,
                    'payment_verified_at' => $applicant->payment_verified_at,
                    'verified_at' => $applicant->verified_at,
                    'verified_by' => $applicant->verified_by,
                    'approved_at' => $applicant->approved_at,
                    'approved_by' => $applicant->approved_by,
                    'rejected_at' => $applicant->rejected_at,
                    'rejected_by' => $applicant->rejected_by,
                    'ip_address' => $applicant->ip_address,
                    'user_agent' => $applicant->user_agent,
                    'registered_at' => $applicant->registered_at ?? $applicant->created_at,
                    'created_at' => $applicant->created_at ?? now(),
                    'updated_at' => $applicant->updated_at ?? now(),
                ]);
            }
        });

        $this->newLine();
    }
}
