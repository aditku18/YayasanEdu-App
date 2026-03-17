<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\DatabaseManager;
use Stancl\Tenancy\TenantCollection;

class TenantService
{
    /**
     * Create a new tenant database and run migrations.
     *
     * @param string|null $uuid - Optional UUID (if null, will be generated from foundation name)
     * @param string $domain
     * @param string|null $foundationName - Foundation name to generate slug-based ID
     * @return \App\Models\Tenant
     */
    public function createTenantWithDomain(?string $uuid, string $domain, ?string $foundationName = null): Tenant
    {
        // Generate tenant ID from foundation name if UUID is not provided
        if (empty($uuid) && $foundationName) {
            // Create slug from foundation name: "Yayasan Kemala" -> "yayasan-kemala"
            $slug = Str::slug($foundationName);
            // Ensure it starts with 'tenant-' prefix
            $uuid = 'tenant-' . $slug;
        } elseif (empty($uuid)) {
            // Fallback to UUID if no foundation name provided
            $uuid = Str::uuid()->toString();
        }

        // Check if tenant already exists
        $existingTenant = Tenant::find($uuid);
        if ($existingTenant) {
            // Ensure database exists for existing tenant
            $this->ensureTenantDatabase($existingTenant);
            return $existingTenant;
        }

        // Create tenant
        $tenant = Tenant::create([
            'id' => $uuid,
        ]);

        // Create domain
        $actualDomain = $domain;
        if (app()->environment('local') && !str_ends_with($actualDomain, '.localhost')) {
            $actualDomain .= '.localhost';
        }

        $tenant->domains()->create([
            'domain' => $actualDomain,
        ]);

        // Create database and run migrations synchronously
        $this->ensureTenantDatabase($tenant);

        return $tenant;
    }

    /**
     * Ensure tenant database exists and migrations have been run.
     *
     * @param Tenant $tenant
     * @return void
     */
    protected function ensureTenantDatabase(Tenant $tenant): void
    {
        $databaseManager = app(DatabaseManager::class);
        
        // Check if database already exists
        $dbName = config('tenancy.database.prefix') . $tenant->id . config('tenancy.database.suffix');
        $conn = new \mysqli('127.0.0.1', 'root', '');
        $result = $conn->query("SHOW DATABASES LIKE '$dbName'");
        $dbExists = $result->num_rows > 0;
        $conn->close();
        
        if (!$dbExists) {
            // Create database only if it doesn't exist
            // Correct way in stancl/tenancy v3 for manual trigger
            $tenant->database()->manager()->createDatabase($tenant);
        }
        
        // Run migrations synchronously
        \Artisan::call('tenants:migrate', [
            '--tenants' => [$tenant->id],
        ]);
    }

    /**
     * Create tenant with slug-based ID from foundation name.
     *
     * @param string $foundationName
     * @param string $domain
     * @return \App\Models\Tenant
     */
    public function createTenantWithFoundationName(string $foundationName, string $domain): Tenant
    {
        return $this->createTenantWithDomain(null, $domain, $foundationName);
    }
}
