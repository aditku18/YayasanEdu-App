<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\ApiIntegration;
use Illuminate\Http\Request;

class ApiIntegrationController extends Controller
{
    public function index()
    {
        $integrations = ApiIntegration::withCount(['logs' => function ($query) {
                $query->where('created_at', '>=', now()->subDays(7));
            }])
            ->latest()
            ->paginate(20);

        $stats = [
            'total_integrations' => ApiIntegration::count(),
            'active_integrations' => ApiIntegration::where('is_active', true)->count(),
            'failed_calls_today' => \App\Models\ApiLog::whereDate('created_at', today())
                ->where('status', 'failed')
                ->count(),
        ];
        
        return view('platform.api-integrations.index', compact('integrations', 'stats'));
    }

    public function create()
    {
        $integrationTypes = [
            'payment_gateway' => 'Payment Gateway',
            'notification' => 'Notification Service',
            'analytics' => 'Analytics Service',
            'storage' => 'Storage Service',
            'other' => 'Other'
        ];

        return view('platform.api-integrations.create', compact('integrationTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:payment_gateway,notification,analytics,storage,other',
            'base_url' => 'required|url',
            'api_key' => 'required|string|max:500',
            'api_secret' => 'nullable|string|max:500',
            'webhook_url' => 'nullable|url',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        // Encrypt sensitive data
        $apiKey = encrypt($request->api_key);
        $apiSecret = $request->api_secret ? encrypt($request->api_secret) : null;

        ApiIntegration::create([
            'name' => $request->name,
            'type' => $request->type,
            'base_url' => $request->base_url,
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'webhook_url' => $request->webhook_url,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => auth()->id()
        ]);

        return redirect()->route('platform.api-integrations.index')
            ->with('success', 'API Integration berhasil dibuat.');
    }

    public function show(ApiIntegration $integration)
    {
        $integration->load(['logs' => function ($query) {
            $query->latest()->limit(50);
        }, 'createdBy']);

        // Decrypt sensitive data for display (show partial)
        $integration->api_key_display = $this->maskApiKey(decrypt($integration->api_key));
        if ($integration->api_secret) {
            $integration->api_secret_display = $this->maskApiKey(decrypt($integration->api_secret));
        }

        return view('platform.api-integrations.show', compact('integration'));
    }

    public function edit(ApiIntegration $integration)
    {
        $integrationTypes = [
            'payment_gateway' => 'Payment Gateway',
            'notification' => 'Notification Service',
            'analytics' => 'Analytics Service',
            'storage' => 'Storage Service',
            'other' => 'Other'
        ];

        return view('platform.api-integrations.edit', compact('integration', 'integrationTypes'));
    }

    public function update(Request $request, ApiIntegration $integration)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:payment_gateway,notification,analytics,storage,other',
            'base_url' => 'required|url',
            'api_key' => 'required|string|max:500',
            'api_secret' => 'nullable|string|max:500',
            'webhook_url' => 'nullable|url',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        // Update encrypted data
        $updateData = [
            'name' => $request->name,
            'type' => $request->type,
            'base_url' => $request->base_url,
            'webhook_url' => $request->webhook_url,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
            'updated_by' => auth()->id()
        ];

        // Only update API keys if provided
        if ($request->api_key !== decrypt($integration->api_key)) {
            $updateData['api_key'] = encrypt($request->api_key);
        }

        if ($request->api_secret && (!$integration->api_secret || $request->api_secret !== decrypt($integration->api_secret))) {
            $updateData['api_secret'] = encrypt($request->api_secret);
        }

        $integration->update($updateData);

        return redirect()->route('platform.api-integrations.index')
            ->with('success', 'API Integration berhasil diperbarui.');
    }

    public function destroy(ApiIntegration $integration)
    {
        $integration->delete();

        return redirect()->route('platform.api-integrations.index')
            ->with('success', 'API Integration berhasil dihapus.');
    }

    public function test(ApiIntegration $integration)
    {
        try {
            // Test API connection
            $response = \Http::withHeaders([
                'Authorization' => 'Bearer ' . decrypt($integration->api_key),
            ])->get($integration->base_url . '/test');

            if ($response->successful()) {
                // Log successful test
                \App\Models\ApiLog::create([
                    'api_integration_id' => $integration->id,
                    'endpoint' => $integration->base_url . '/test',
                    'method' => 'GET',
                    'status' => 'success',
                    'response_code' => $response->status(),
                    'response_body' => $response->body(),
                    'created_at' => now()
                ]);

                return redirect()->back()->with('success', 'Koneksi API berhasil.');
            } else {
                throw new \Exception('API test failed');
            }
        } catch (\Exception $e) {
            // Log failed test
            \App\Models\ApiLog::create([
                'api_integration_id' => $integration->id,
                'endpoint' => $integration->base_url . '/test',
                'method' => 'GET',
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'created_at' => now()
            ]);

            return redirect()->back()->with('error', 'Koneksi API gagal: ' . $e->getMessage());
        }
    }

    private function maskApiKey($apiKey)
    {
        if (strlen($apiKey) <= 8) {
            return str_repeat('*', strlen($apiKey));
        }

        $start = substr($apiKey, 0, 4);
        $end = substr($apiKey, -4);
        $middle = str_repeat('*', strlen($apiKey) - 8);

        return $start . $middle . $end;
    }
}
