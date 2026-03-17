<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Foundation;
use App\Models\User;
use App\Services\TenantService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ApproveYayasanController extends Controller
{
    /**
     * Setujui dan provision yayasan menjadi tenant aktif.
     */
    public function approve(Request $request, Foundation $foundation, TenantService $tenantService)
    {
        // Pastikan hanya memproses yang berstatus pending
        if ($foundation->status !== 'pending') {
            return redirect()->back()->with('error', 'Yayasan ini sudah tidak dalam status pending.');
        }

        // Pastikan email admin yayasan sudah terverifikasi
        $user = User::where('email', strtolower($foundation->email))->first();
        if (!$user || !$user->hasVerifiedEmail()) {
            return redirect()->back()->with('warning', 'Yayasan tidak dapat di-approve karena email administrator (' . $foundation->email . ') belum terverifikasi.');
        }

        // Generate slug-based tenant ID from foundation name
        // Example: "Yayasan Kemala" -> tenant-yayasan-kemala
        $expectedTenantId = 'tenant-' . Str::slug($foundation->name);
        
        // Check if tenant already exists (from previous failed attempt)
        $existingTenant = \App\Models\Tenant::find($expectedTenantId);
        
        if ($existingTenant) {
            // Tenant already exists, just use the existing tenant
            $tenantId = $expectedTenantId;
            $tenant = $existingTenant;
        } else {
            // Create new tenant with slug-based ID from foundation name
            $tenant = $tenantService->createTenantWithDomain(null, $foundation->subdomain, $foundation->name);
            $tenantId = $tenant ? $tenant->id : null;
        }

        // 2. Update status & trial yayasan
        $foundation->update([
            'tenant_id' => $tenantId,
            'status' => 'trial',
            'trial_ends_at' => now()->addDays(14),
            'plan_id' => 1, // Trial plan ID
        ]);

        // 3. Update User central admin untuk binding dengan tenant_id ini
        $user = User::where('email', strtolower($foundation->email))->first();
        if ($user) {
            $user->update([
                'tenant_id' => $tenantId,
                'role' => 'foundation_admin' // Backup ensure role is set
            ]);

            // 4. Provision user in Tenant DB
            // We must also create this user in the tenant's own database so they can log in at the subdomain
            $tenant = \App\Models\Tenant::find($tenantId);
            if ($tenant) {
                tenancy()->initialize($tenant);
                
                $tenantUser = User::where('email', strtolower($foundation->email))->first();
                if (!$tenantUser) {
                    $userData = $user->toArray();
                    unset($userData['tenant_id'], $userData['id']);
                    $userData['password'] = $user->password; // Copy hashed password
                    $userData['email_verified_at'] = $user->email_verified_at; // Sync verification status
                    
                    $tenantUser = User::create($userData);
                    
                    // Assign role in tenant DB
                    $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'foundation_admin', 'guard_name' => 'web']);
                    $tenantUser->assignRole($role);
                }
                
                tenancy()->end();
            }
        } else {
            Log::error("Failed to find user for foundation approval: " . $foundation->email);
        }

        return redirect()->route('admin.foundations.index')->with('success', "Yayasan {$foundation->name} berhasil di-approve! Tenant {$foundation->subdomain} siap digunakan.");
    }
}
