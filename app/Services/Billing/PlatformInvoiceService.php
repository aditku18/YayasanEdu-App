<?php

namespace App\Services\Billing;

use App\Models\Foundation;
use App\Models\Invoice;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PlatformInvoiceService
{
    /**
     * Generate a subscription invoice for a foundation based on its current plan.
     *
     * @param Foundation $foundation
     * @param array{billing_cycle:string,due_days:int,notes?:string} $data
     * @return Invoice
     *
     * @throws \InvalidArgumentException
     */
    public function generateForFoundation(Foundation $foundation, array $data): Invoice
    {
        $plan = $foundation->plan;

        if (!$plan) {
            throw new \InvalidArgumentException('Yayasan belum memiliki paket langganan. Silakan assign paket terlebih dahulu.');
        }

        $billingCycle = Arr::get($data, 'billing_cycle');
        $dueDays = (int) Arr::get($data, 'due_days');
        $notes = Arr::get($data, 'notes');

        if (!in_array($billingCycle, ['monthly', 'yearly'], true)) {
            throw new \InvalidArgumentException('Billing cycle tidak valid.');
        }

        if (!in_array($dueDays, [7, 14, 30, 60], true)) {
            throw new \InvalidArgumentException('Jatuh tempo tidak valid.');
        }

        // Calculate amount based on billing cycle
        if ($billingCycle === 'yearly') {
            $amount = $plan->price_per_year > 0 ? $plan->price_per_year : ($plan->price_per_month * 12);
        } else {
            $amount = $plan->price_per_month;
        }

        if ($amount <= 0) {
            throw new \InvalidArgumentException('Paket gratis tidak memerlukan invoice.');
        }

        $periodStart = now();
        $periodEnd = $billingCycle === 'yearly'
            ? now()->copy()->addYear()
            : now()->copy()->addMonth();

        $latestSubscription = $foundation->subscriptions()->latest()->first();

        return Invoice::create([
            'foundation_id' => $foundation->id,
            'subscription_id' => $latestSubscription?->id,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'amount' => $amount,
            'status' => 'pending',
            'due_date' => now()->addDays($dueDays),
            'billing_cycle' => $billingCycle,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'notes' => $notes,
            'items' => [
                'plan_name' => $plan->name,
                'plan_id' => $plan->id,
                'billing_cycle' => $billingCycle,
                'price_per_month' => $plan->price_per_month,
                'price_per_year' => $plan->price_per_year,
            ],
            'payment_token' => Str::random(40),
        ]);
    }
}

