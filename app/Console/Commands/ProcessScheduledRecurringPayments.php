<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Finance\RecurringPayment;
use App\Jobs\ProcessRecurringPayment;
use Illuminate\Support\Facades\Log;

class ProcessScheduledRecurringPayments extends Command
{
    protected $signature = 'payments:process-recurring';
    protected $description = 'Process scheduled recurring payments';

    public function handle(): int
    {
        $this->info('Processing scheduled recurring payments...');

        $duePayments = RecurringPayment::where('status', 'active')
            ->where('next_charge_date', '<=', now())
            ->where(function ($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>', now());
            })
            ->where(function ($query) {
                $query->whereNull('max_charges')
                      ->orWhere('total_charges', '<', \DB::raw('max_charges'));
            })
            ->get();

        $processedCount = 0;
        $failedCount = 0;

        foreach ($duePayments as $recurringPayment) {
            try {
                ProcessRecurringPayment::dispatch($recurringPayment);
                $processedCount++;
                $this->line("Dispatched recurring payment {$recurringPayment->id}");
            } catch (\Exception $e) {
                $failedCount++;
                Log::error("Failed to dispatch recurring payment {$recurringPayment->id}: " . $e->getMessage());
                $this->error("Failed to dispatch recurring payment {$recurringPayment->id}: {$e->getMessage()}");
            }
        }

        $this->info("Processed {$processedCount} recurring payments, {$failedCount} failed");
        
        return Command::SUCCESS;
    }
}
