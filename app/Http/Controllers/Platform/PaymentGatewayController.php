<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\PaymentGateway;
use App\Services\PaymentGatewayManager;

class PaymentGatewayController extends Controller
{
    protected PaymentGatewayManager $gatewayManager;

    public function __construct(PaymentGatewayManager $gatewayManager)
    {
        $this->gatewayManager = $gatewayManager;
    }

    public function index()
    {
        $gateways = PaymentGateway::orderBy('priority')->get();
        return view('platform.payment-gateways.index', compact('gateways'));
    }

    public function create()
    {
        return view('platform.payment-gateways.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:payment_gateways',
            'display_name' => 'required|string|max:150',
            'type' => 'required|in:third_party,custom',
            'config' => 'required|array',
            'is_active' => 'boolean',
            'supports_recurring' => 'boolean',
            'supports_split_payment' => 'boolean',
            'supported_methods' => 'required|array',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'admin_fee_rate' => 'required|numeric|min:0|max:100',
            'fixed_admin_fee' => 'required|numeric|min:0',
            'priority' => 'required|integer|min:1',
        ]);

        // Encrypt sensitive configuration
        $validated['config'] = $this->gatewayManager->encryptPaymentData($validated['config']);

        // Auto-populate strict database columns missing from controller
        $validated['code'] = $validated['name'] ?? uniqid('gw_');

        PaymentGateway::create($validated);

        return redirect()->route('platform.payment-gateways.index')
            ->with('success', 'Payment gateway berhasil ditambahkan.');
    }

    public function show(PaymentGateway $paymentGateway)
    {
        $paymentGateway->load(['webhookLogs']);
        
        // Decrypt if it's an encrypted string
        if (is_string($paymentGateway->config)) {
            try {
                $paymentGateway->config = $this->gatewayManager->decryptPaymentData($paymentGateway->config);
            } catch (\Exception $e) {
                // Keep as is or handle error
            }
        }
        
        return view('platform.payment-gateways.show', compact('paymentGateway'));
    }

    public function edit(PaymentGateway $paymentGateway)
    {
        // Decrypt configuration for editing if it's an encrypted string
        if (is_string($paymentGateway->config)) {
            try {
                $paymentGateway->config = $this->gatewayManager->decryptPaymentData($paymentGateway->config);
            } catch (\Exception $e) {
                // Keep as is or handle error
            }
        }
        
        return view('platform.payment-gateways.edit', compact('paymentGateway'));
    }

    public function update(Request $request, PaymentGateway $paymentGateway)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:payment_gateways,name,' . $paymentGateway->id,
            'display_name' => 'required|string|max:150',
            'type' => 'required|in:third_party,custom',
            'config' => 'required|array',
            'is_active' => 'boolean',
            'supports_recurring' => 'boolean',
            'supports_split_payment' => 'boolean',
            'supported_methods' => 'required|array',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'admin_fee_rate' => 'required|numeric|min:0|max:100',
            'fixed_admin_fee' => 'required|numeric|min:0',
            'priority' => 'required|integer|min:1',
        ]);

        // Encrypt sensitive configuration
        $validated['config'] = $this->gatewayManager->encryptPaymentData($validated['config']);

        $paymentGateway->update($validated);

        return redirect()->route('platform.payment-gateways.show', $paymentGateway->id)
            ->with('success', 'Payment gateway berhasil diperbarui.');
    }

    public function destroy(PaymentGateway $paymentGateway)
    {
        $name = $paymentGateway->display_name;
        
        // Check if gateway has active payments
        if ($paymentGateway->payments()->where('status', 'pending')->exists()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus gateway yang memiliki pembayaran pending.');
        }

        $paymentGateway->delete();

        return redirect()->route('platform.payment-gateways.index')
            ->with('success', "Payment gateway {$name} berhasil dihapus.");
    }

    public function testConnection(PaymentGateway $paymentGateway)
    {
        try {
            // Decrypt if it's an encrypted string
            if (is_string($paymentGateway->config)) {
                try {
                    $paymentGateway->config = $this->gatewayManager->decryptPaymentData($paymentGateway->config);
                } catch (\Exception $e) {
                    // Continue, maybe it's not encrypted or has wrong key
                }
            }

            // Test API connection based on gateway type
            $testResult = $this->testGatewayConnection($paymentGateway);
            
            return response()->json([
                'success' => $testResult['success'],
                'message' => $testResult['message'],
                'data' => $testResult['data'] ?? null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    protected function testGatewayConnection(PaymentGateway $gateway): array
    {
        switch ($gateway->name) {
            case 'midtrans':
                return $this->testMidtransConnection($gateway);
            case 'xendit':
                return $this->testXenditConnection($gateway);
            case 'custom':
                return $this->testCustomConnection($gateway);
            default:
                return ['success' => false, 'message' => 'Gateway tidak didukung untuk testing.'];
        }
    }

    protected function testMidtransConnection(PaymentGateway $gateway): array
    {
        $config = $gateway->config;
        
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($config['server_key'] . ':'),
                'Content-Type' => 'application/json',
            ])->get($config['api_url'] . '/v2/status');

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Koneksi ke Midtrans berhasil.',
                    'data' => [
                        'status' => 'active',
                        'merchant_id' => $config['merchant_id'] ?? 'N/A',
                    ]
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal terhubung ke Midtrans: ' . $response->body(),
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error koneksi Midtrans: ' . $e->getMessage(),
            ];
        }
    }

    protected function testXenditConnection(PaymentGateway $gateway): array
    {
        $config = $gateway->config;
        
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => $config['secret_key'],
                'Content-Type' => 'application/json',
            ])->get($config['api_url'] . '/balance');

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Koneksi ke Xendit berhasil.',
                    'data' => [
                        'balance' => $response->json('balance'),
                        'status' => 'active',
                    ]
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal terhubung ke Xendit: ' . $response->body(),
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error koneksi Xendit: ' . $e->getMessage(),
            ];
        }
    }

    protected function testCustomConnection(PaymentGateway $gateway): array
    {
        // Custom gateway test
        return [
            'success' => true,
            'message' => 'Custom gateway siap digunakan.',
            'data' => [
                'type' => 'custom',
                'status' => 'configured',
            ]
        ];
    }

    public function toggleStatus(PaymentGateway $paymentGateway)
    {
        $paymentGateway->update(['is_active' => !$paymentGateway->is_active]);
        
        $status = $paymentGateway->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->back()
            ->with('success', "Payment gateway {$paymentGateway->display_name} berhasil {$status}.");
    }
}
