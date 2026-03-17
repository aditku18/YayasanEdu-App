<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$config = ['bank_name' => 'BCA', 'account_number' => '1234567890', 'account_name' => 'PT EduSaaS Indonesia'];
$encryptedConfig = app(\App\Services\PaymentGatewayManager::class)->encryptPaymentData($config);
\App\Models\Finance\PaymentGateway::updateOrCreate(
    ['name' => 'bank_transfer_bca'],
    [
        'display_name' => 'Transfer Bank BCA',
        'type' => 'custom',
        'code' => 'bca_manual',
        'config' => $encryptedConfig,
        'is_active' => true,
        'supports_recurring' => false,
        'supports_split_payment' => false,
        'supported_methods' => ['bank_transfer'],
        'priority' => 1
    ]
);
echo "Payment Gateway created successfully.\n";
