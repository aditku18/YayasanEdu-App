<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Payment commands
Artisan::command('payments:process-recurring', function () {
    $this->call('payments:process-recurring');
})->describe('Process scheduled recurring payments');
