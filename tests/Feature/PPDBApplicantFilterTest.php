<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\PPDBApplicant;
use App\Models\PPDBWave;
use App\Models\SchoolUnit;
use App\Models\User;

class PPDBApplicantFilterTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // create a school unit and wave
        $school = SchoolUnit::create(['name' => 'Test School', 'status' => 'active']);
        $wave = PPDBWave::create([
            'school_unit_id' => $school->id,
            'name' => 'Test Wave',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'registration_fee' => 0,
            'status' => 'active',
        ]);

        // create an admin user for that school
        $this->user = User::factory()->create([
            'school_unit_id' => $school->id,
            'role' => 'school_admin',
        ]);
    }

    public function test_can_filter_applicants_by_payment_status()
    {
        // create some applicants with various statuses
        PPDBApplicant::create([
            'school_unit_id' => $this->user->school_unit_id,
            'ppdb_wave_id' => PPDBWave::first()->id,
            'registration_number' => 'PPDB-TEST1',
            'name' => 'One',
            'phone' => '000',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'total_fee' => 0,
        ]);
        PPDBApplicant::create([
            'school_unit_id' => $this->user->school_unit_id,
            'ppdb_wave_id' => PPDBWave::first()->id,
            'registration_number' => 'PPDB-TEST2',
            'name' => 'Two',
            'phone' => '000',
            'status' => 'pending',
            'payment_status' => 'partial',
            'total_fee' => 0,
        ]);
        PPDBApplicant::create([
            'school_unit_id' => $this->user->school_unit_id,
            'ppdb_wave_id' => PPDBWave::first()->id,
            'registration_number' => 'PPDB-TEST3',
            'name' => 'Three',
            'phone' => '000',
            'status' => 'pending',
            'payment_status' => 'paid',
            'total_fee' => 0,
        ]);

        $response = $this->actingAs($this->user)
                         ->get(route('tenant.ppdb.applicants', ['payment_status' => 'unpaid']));

        $response->assertStatus(200);
        $response->assertSee('Belum');
        $response->assertDontSee('DP 50%');
        $response->assertDontSee('Lunas');
    }
}
