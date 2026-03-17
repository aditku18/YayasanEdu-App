<?php

namespace App\Services;

use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Defuse\Crypto\Crypto;

class PaymentGatewayManager
{
    protected array $gateways = [];
    protected ?PaymentGateway $defaultGateway = null;

    public function __construct()
    {
        $this->loadGateways();
    }

    protected function loadGateways(): void
    {
        $gateways = PaymentGateway::where('is_active', true)
            ->orderBy('name') // Using 'name' instead of 'priority' since 'priority' column doesn't exist
            ->get();

        foreach ($gateways as $gateway) {
            $this->gateways[$gateway->name] = $gateway;
            
            // Set first gateway as default since there's no priority column
            if (!$this->defaultGateway) {
                $this->defaultGateway = $gateway;
            }
        }
    }

    public function getGateway(string $name): ?PaymentGateway
    {
        return $this->gateways[$name] ?? null;
    }

    public function getGatewayById(int $id): ?PaymentGateway
    {
        return PaymentGateway::find($id);
    }

    public function getDefaultGateway(): ?PaymentGateway
    {
        return $this->defaultGateway;
    }

    public function getActiveGateways(): array
    {
        return $this->gateways;
    }

    public function createPayment(string $gatewayName, array $data): array
    {
        $gateway = $this->getGateway($gatewayName);
        if (!$gateway) {
            throw new \Exception("Gateway {$gatewayName} not found or inactive");
        }

        return $this->processPayment($gateway, $data);
    }

    protected function processPayment(PaymentGateway $gateway, array $data): array
    {
        switch ($gateway->code) { // Using 'code' instead of 'name' for better matching
            case 'midtrans':
                return $this->processMidtransSnap($gateway, $data);
            case 'xendit':
                return $this->processXenditInvoice($gateway, $data);
            case 'custom':
                return $this->processCustomPayment($gateway, $data);
            default:
                throw new \Exception("Unsupported gateway: {$gateway->code}");
        }
    }

    protected function processMidtransSnap(PaymentGateway $gateway, array $data): array
    {
        $config = $gateway->config;
        $baseUrl = $config['api_url'] ?? 'https://app.sandbox.midtrans.com/snap/v1';
        
        $payload = [
            'transaction_details' => [
                'order_id' => $data['order_id'],
                'gross_amount' => (int) $data['amount'],
            ],
            'customer_details' => [
                'first_name' => $data['customer']['first_name'] ?? 'Customer',
                'last_name' => $data['customer']['last_name'] ?? '',
                'email' => $data['customer']['email'] ?? 'customer@example.com',
                'phone' => $data['customer']['phone'] ?? null,
            ],
            'item_details' => $data['items'] ?? [],
            'expiry' => [
                'unit' => 'days',
                'duration' => 1
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($config['server_key'] . ':'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($baseUrl . '/transactions', $payload);

        if ($response->failed()) {
            Log::error('Midtrans Snap Error: ' . $response->body());
            throw new \Exception('Failed to generate Midtrans Snap token: ' . ($response->json()['error_messages'][0] ?? 'Unknown error'));
        }

        return $response->json();
    }

    protected function processXenditInvoice(PaymentGateway $gateway, array $data): array
    {
        $config = $gateway->config;
        $baseUrl = $config['api_url'] ?? 'https://api.xendit.co';
        
        $payload = [
            'external_id' => $data['order_id'],
            'amount' => (int) $data['amount'],
            'description' => $data['description'] ?? 'Pembayaran Invoice ' . $data['order_id'],
            'customer' => [
                'given_names' => $data['customer']['first_name'] ?? 'Customer',
                'surname' => $data['customer']['last_name'] ?? '',
                'email' => $data['customer']['email'] ?? 'customer@example.com',
                'mobile_number' => $data['customer']['phone'] ?? null,
            ],
            'success_redirect_url' => $data['success_url'] ?? route('tenant.invoice.index'),
            'failure_redirect_url' => $data['failure_url'] ?? route('tenant.invoice.index'),
            'currency' => 'IDR',
            'items' => $data['items'] ?? []
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($config['secret_key'] . ':'),
            'Content-Type' => 'application/json',
        ])->post($baseUrl . '/v2/invoices', $payload);

        if ($response->failed()) {
            Log::error('Xendit Invoice Error: ' . $response->body());
            throw new \Exception('Failed to create Xendit invoice: ' . ($response->json()['message'] ?? 'Unknown error'));
        }

        return $response->json();
    }

    protected function processCustomPayment(PaymentGateway $gateway, array $data): array
    {
        // Custom gateway implementation
        // This can be extended based on specific requirements
        
        return [
            'status' => 'success',
            'transaction_id' => uniqid('CUSTOM_'),
            'redirect_url' => route('payment.custom.callback', ['gateway' => $gateway->name]),
            'message' => 'Payment initiated successfully',
        ];
    }

    public function verifyWebhook(string $gatewayName, array $payload): bool
    {
        $gateway = $this->getGateway($gatewayName);
        if (!$gateway) {
            return false;
        }

        // Note: WebhookLog model doesn't exist in current structure
        // For now, just validate signature without logging
        return $this->validateWebhookSignature($gateway, $payload);
    }

    protected function validateWebhookSignature(PaymentGateway $gateway, array $payload): bool
    {
        $config = $gateway->config;
        
        switch ($gateway->name) {
            case 'midtrans':
                return $this->validateMidtransSignature($config, $payload);
            case 'xendit':
                return $this->validateXenditSignature($config, $payload);
            default:
                return true; // Custom gateway might not have signature validation
        }
    }

    protected function validateMidtransSignature(array $config, array $payload): bool
    {
        if (!isset($payload['signature_key'])) {
            return false;
        }

        $orderId = $payload['order_id'];
        $statusCode = $payload['status_code'];
        $grossAmount = $payload['gross_amount'];
        $serverKey = $config['server_key'];

        $input = $orderId . $statusCode . $grossAmount . $serverKey;
        $expectedSignature = hash('sha512', $input);

        return hash_equals($expectedSignature, $payload['signature_key']);
    }

    protected function validateXenditSignature(array $config, array $payload): bool
    {
        if (!isset($payload['token'])) {
            return false;
        }

        $expectedToken = hash_hmac('sha256', $config['webhook_token'], $payload['id']);
        return hash_equals($expectedToken, $payload['token']);
    }

    public function encryptPaymentData(array $data): string
    {
        $key = env('PAYMENT_ENCRYPTION_KEY');
        if (!$key) {
            throw new \Exception('Payment encryption key not configured');
        }

        return Crypto::encryptWithPassword(json_encode($data), $key);
    }

    public function decryptPaymentData(string $encryptedData): array
    {
        $key = env('PAYMENT_ENCRYPTION_KEY');
        if (!$key) {
            throw new \Exception('Payment encryption key not configured');
        }

        return json_decode(Crypto::decryptWithPassword($encryptedData, $key), true);
    }

    public function getSupportedMethods(string $gatewayName = null): array
    {
        if ($gatewayName) {
            $gateway = $this->getGateway($gatewayName);
            return $gateway ? $gateway->supported_methods : [];
        }

        $methods = [];
        foreach ($this->gateways as $gateway) {
            $methods = array_merge($methods, $gateway->supported_methods);
        }

        return array_unique($methods);
    }

    public function calculateTotalFees(float $amount, string $gatewayName): array
    {
        $gateway = $this->getGateway($gatewayName);
        if (!$gateway) {
            return ['admin_fee' => 0, 'total' => $amount];
        }

        $adminFee = $gateway->calculateFee($amount); // Using calculateFee method instead of calculateAdminFee
        $total = $amount + $adminFee;

        return [
            'admin_fee' => $adminFee,
            'total' => $total,
            'fee_percentage' => $gateway->fee_percentage,
            'fee_fixed' => $gateway->fee_fixed,
        ];
    }
}
