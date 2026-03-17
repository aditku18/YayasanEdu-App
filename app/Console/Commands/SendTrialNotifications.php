<?php

namespace App\Console\Commands;

use App\Services\TrialNotificationService;
use Illuminate\Console\Command;

class SendTrialNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trial:send-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send trial expiration notifications to foundations';

    /**
     * Execute the console command.
     */
    public function handle(TrialNotificationService $notificationService)
    {
        $this->info('Checking trial notifications...');
        
        $notificationService->checkAndSendNotifications();
        
        $this->info('Trial notifications sent successfully!');
        
        return Command::SUCCESS;
    }
}
