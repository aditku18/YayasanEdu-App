<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PaymentGatewayManager;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentGatewayManager::class, function ($app) {
            return new PaymentGatewayManager();
        });
    }

    public function boot(): void
    {
        // Load payment gateway configurations
        $this->publishes([
            __DIR__.'/../config/payment.php' => config_path('payment.php'),
        ], 'payment-config');

        // Load views for payment components
        $this->loadViewsFrom(__DIR__.'/../resources/views/payments', 'payments');
    }
}
