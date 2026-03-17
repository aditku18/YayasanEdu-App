<?php
namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Tenant;
use App\Models\User;
use App\Models\SchoolUnit;
use Stancl\Tenancy\Database\Models\Domain;

class TenantLoginTest extends TestCase
{
    public function test_tenant_login()
    {
        $subdomain = 'pelita-hati2.localhost';
        $domain = Domain::where('domain', $subdomain)->first();
        if (!$domain) {
            $this->markTestSkipped('Tenant domain not found.');
        }

        $tenant = $domain->tenant;
        tenancy()->initialize($tenant);

        // Ensure user exists
        $user = User::where('email', 'aditku02@gmail.com')->first();
        if (!$user) {
            $user = User::factory()->create([
                'email' => 'aditku02@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'school_admin'
            ]);
        }

        tenancy()->end();

        $response = $this->withHeaders([
            'Host' => $subdomain,
        ])->post("http://{$subdomain}/login", [
            'email' => 'aditku02@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        
        $location = $response->headers->get('Location');
        echo "Response Redirect: " . $location . "\n";
        
        if (session()->has('errors')) {
            echo "Validation Errors: " . json_encode(session('errors')->getMessages()) . "\n";
        }
    }
}
