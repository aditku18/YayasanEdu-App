<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckTrialStatus
{
    /**
     * Cek apakah tenant masih dalam masa trial aktif atau sudah expired.
     */
    public function handle(Request $request, Closure $next)
    {
        $tenantId = tenant('id');

        if (!$tenantId) {
            return $next($request);
        }

        // Access foundation data from central database
        $foundation = DB::connection('mysql')
            ->table('foundations')
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$foundation) {
            return $next($request);
        }

        // Convert to object with methods
        $foundationObj = $this->createFoundationObject($foundation);

        // Jika status aktif (sudah bayar) → lanjut
        if ($foundationObj->status === 'active') {
            return $next($request);
        }

        // Jika trial sudah expired → auto-update status & redirect
        if ($foundationObj->isTrialExpired()) {
            DB::connection('mysql')
                ->table('foundations')
                ->where('tenant_id', $tenantId)
                ->update(['status' => 'expired']);

            return redirect()->to('/trial-expired');
        }

        // Jika status expired (sudah di-mark sebelumnya) → redirect
        if ($foundationObj->status === 'expired') {
            return redirect()->to('/trial-expired');
        }

        // Share trial info ke semua views
        if ($foundationObj->status === 'trial') {
            view()->share('trialDaysLeft', $foundationObj->daysLeftInTrial());
            view()->share('trialEndsAt', $foundationObj->trial_ends_at);
        }

        return $next($request);
    }

    /**
     * Create foundation object with required methods
     */
    private function createFoundationObject($foundation)
    {
        return new class($foundation) {
            private $foundation;

            public function __construct($foundation)
            {
                $this->foundation = $foundation;
            }

            public function __get($name)
            {
                return $this->foundation->{$name};
            }

            public function isTrialExpired(): bool
            {
                if (!$this->trial_ends_at || $this->status !== 'trial') {
                    return false;
                }
                
                $trialEndsAt = \Carbon\Carbon::parse($this->trial_ends_at);
                return $trialEndsAt->isPast();
            }

            public function daysLeftInTrial(): int
            {
                if (!$this->trial_ends_at || $this->status !== 'trial') {
                    return 0;
                }
                
                $trialEndsAt = \Carbon\Carbon::parse($this->trial_ends_at);
                if ($trialEndsAt->isPast()) {
                    return 0;
                }
                
                return (int) \Carbon\Carbon::now()->diffInDays($trialEndsAt, false);
            }
        };
    }
}
