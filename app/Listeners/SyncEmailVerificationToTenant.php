<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Log;

class SyncEmailVerificationToTenant
{
    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        $user = $event->user;

        // Only sync if user has a tenant_id (meaning they are a foundation admin approved/provisioned)
        if ($user->tenant_id) {
            $tenant = Tenant::find($user->tenant_id);
            
            if ($tenant) {
                try {
                    tenancy()->initialize($tenant);
                    
                    $tenantUser = User::where('email', $user->email)->first();
                    if ($tenantUser) {
                        $tenantUser->forceFill([
                            'email_verified_at' => $user->email_verified_at,
                        ])->save();
                        
                        Log::info("Synced email verification to tenant {$tenant->id} for user {$user->email}");
                    }
                    
                    tenancy()->end();
                } catch (\Exception $e) {
                    Log::error("Failed to sync email verification to tenant: " . $e->getMessage());
                }
            }
        }
    }
}
