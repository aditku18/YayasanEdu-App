<?php

namespace App\Providers;

use App\Models\Finance\PaymentSplit;
use App\Observers\PaymentSplitObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register observers
        PaymentSplit::observe(PaymentSplitObserver::class);
        
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Verified::class,
            \App\Listeners\SyncEmailVerificationToTenant::class
        );
    }
}
