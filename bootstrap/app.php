<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'school.status' => \App\Http\Middleware\CheckSchoolStatus::class,
            'tenant.verified' => \App\Http\Middleware\TenantEnsureEmailIsVerified::class,
            'school.slug' => \App\Http\Middleware\ValidateSchoolSlug::class,
            'payment.gateway' => \App\Http\Middleware\CheckPaymentGateway::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
