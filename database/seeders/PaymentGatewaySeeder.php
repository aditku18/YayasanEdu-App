<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;

class PaymentGatewaySeeder extends Seeder
{
    public function run(): void
    {
        $gateways = [
            [
                'name' => 'Midtrans',
                'code' => 'midtrans',
                'description' => 'Payment gateway terpercaya untuk transaksi online di Indonesia',
                'is_active' => true,
                'config' => [
                    'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-TEST_KEY'),
                    'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-TEST_KEY'),
                    'merchant_id' => env('MIDTRANS_MERCHANT_ID', 'TEST_MERCHANT_ID'),
                    'api_url' => env('APP_ENV') === 'local' 
                        ? 'https://api.sandbox.midtrans.com/v2' 
                        : 'https://api.midtrans.com/v2',
                ],
                'supported_methods' => ['credit_card', 'bank_transfer', 'virtual_account', 'ewallet', 'qris'],
                'currency' => 'IDR',
                'min_amount' => 1000,
                'max_amount' => 99999999.99,
                'fee_percentage' => 2.5,
                'fee_fixed' => 0,
            ],
            [
                'name' => 'Xendit',
                'code' => 'xendit',
                'description' => 'Platform pembayaran all-in-one untuk bisnis Indonesia',
                'is_active' => true,
                'config' => [
                    'secret_key' => env('XENDIT_SECRET_KEY', 'xnd_test_SECRET_KEY'),
                    'api_url' => env('XENDIT_API_URL', 'https://api.xendit.co'),
                    'webhook_token' => env('XENDIT_WEBHOOK_TOKEN', 'test_webhook_token'),
                ],
                'supported_methods' => ['credit_card', 'bank_transfer', 'virtual_account', 'ewallet', 'qris'],
                'currency' => 'IDR',
                'min_amount' => 1000,
                'max_amount' => 99999999.99,
                'fee_percentage' => 2.9,
                'fee_fixed' => 0,
            ],
            [
                'name' => 'Transfer Bank Manual',
                'code' => 'manual',
                'description' => 'Transfer bank manual yang akan dikonfirmasi oleh admin',
                'is_active' => true,
                'config' => [
                    'bank_name' => 'BCA',
                    'account_number' => '1234567890',
                    'account_name' => 'PT EduSaaS Indonesia',
                    'bank_branch' => 'KCP Jakarta Pusat',
                ],
                'supported_methods' => ['bank_transfer'],
                'currency' => 'IDR',
                'min_amount' => 10000,
                'max_amount' => 49999999.99,
                'fee_percentage' => 0,
                'fee_fixed' => 0,
            ],
        ];

        foreach ($gateways as $gateway) {
            PaymentGateway::updateOrCreate(
                ['code' => $gateway['code']],
                $gateway
            );
        }
    }
}
