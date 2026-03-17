<?php

namespace Tests\Feature\Tenant\Finance;

use App\Models\Finance\Invoice;
use App\Models\Finance\BillType;
use App\Models\SchoolUnit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_can_view_school_invoices()
    {
        $school = SchoolUnit::factory()->create();

        $user = User::factory()->create([
            'school_unit_id' => $school->id,
        ]);

        $billType = BillType::factory()->create([
            'school_unit_id' => $school->id,
        ]);

        Invoice::factory()->count(3)->create([
            'school_unit_id' => $school->id,
            'bill_type_id' => $billType->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('tenant.school.finance.invoices.index', ['school' => $school->slug]));

        $response->assertStatus(200);
        $response->assertSee($billType->name);
    }
}

