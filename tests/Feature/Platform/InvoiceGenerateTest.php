<?php

namespace Tests\Feature\Platform;

use App\Models\Foundation;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceGenerateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_generate_invoice_for_foundation_with_plan()
    {
        $user = User::factory()->create();

        $plan = Plan::factory()->create([
            'price_per_month' => 100000,
            'price_per_year' => 1000000,
        ]);

        $foundation = Foundation::factory()->create([
            'plan_id' => $plan->id,
        ]);

        $response = $this->actingAs($user)
            ->post(route('platform.invoices.generate', $foundation), [
                'billing_cycle' => 'monthly',
                'due_days' => 14,
                'notes' => 'Test invoice',
            ]);

        $response->assertRedirect(route('platform.invoices.show', $foundation));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('invoices', [
            'foundation_id' => $foundation->id,
            'amount' => 100000,
            'status' => 'pending',
        ]);
    }
}

