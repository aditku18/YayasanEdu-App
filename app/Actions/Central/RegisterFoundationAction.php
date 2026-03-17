<?php

namespace App\Actions\Central;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Foundation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class RegisterFoundationAction
{
    /**
     * Execute the registration process.
     *
     * @param array $data
     * @return Foundation
     */
    public function execute(array $data): Foundation
    {
        return DB::transaction(function () use ($data) {
            // 1. Create the Tenant (Database automatically created by stancl/tenancy)
            $tenant = Tenant::create([
                'id' => Str::slug($data['name']),
            ]);

            // 2. Create the Domain
            $tenant->domains()->create([
                'domain' => Str::slug($data['name']) . '.localhost', 
            ]);

            // 3. Create the Foundation Owner (Central User)
            $user = User::create([
                'name' => $data['owner_name'] ?? $data['name'] . ' Admin',
                'email' => $data['email'],
                'password' => Hash::make($data['password'] ?? Str::random(10)),
                'yayasan_id' => null, // Will be linked if needed, or owners are global
            ]);

            // 4. Create the Foundation Record
            $foundation = Foundation::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'status' => 'active',
                // 'plan_id' => $data['plan_id'] ?? 1, // To be implemented
                // 'expiry_date' => now()->addMonth(), // To be implemented
            ]);

            return $foundation;
        });
    }
}
