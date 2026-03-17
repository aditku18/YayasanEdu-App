<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\DB;

class BankTransferGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing bank transfer gateways
        PaymentGateway::where('name', 'like', 'bank_transfer_%')->delete();
        
        $bankGateways = [
            [
                'code' => 'bank_transfer_bca',
                'name' => 'bank_transfer_bca',
                'display_name' => 'BCA Transfer',
                'type' => 'custom',
                'config' => [
                    'bank_name' => 'Bank Central Asia',
                    'account_number' => '1234567890',
                    'account_name' => 'PT YayasanEdu Indonesia',
                    'bank_branch' => 'KCP Jakarta Pusat',
                    'swift_code' => 'CENAIDJA',
                ],
                'is_active' => true,
                'supports_recurring' => false,
                'supports_split_payment' => false,
                'supported_methods' => ['bank_transfer'],
                'admin_fee_rate' => 0,
                'fixed_admin_fee' => 0,
                'min_amount' => 10000,
                'max_amount' => 100000000,
                'priority' => 1,
            ],
            [
                'code' => 'bank_transfer_mandiri',
                'name' => 'bank_transfer_mandiri',
                'display_name' => 'Mandiri Transfer',
                'type' => 'custom',
                'config' => [
                    'bank_name' => 'Bank Mandiri',
                    'account_number' => '0987654321',
                    'account_name' => 'PT YayasanEdu Indonesia',
                    'bank_branch' => 'KCP Jakarta Sudirman',
                    'swift_code' => 'BEIDIDJA',
                ],
                'is_active' => true,
                'supports_recurring' => false,
                'supports_split_payment' => false,
                'supported_methods' => ['bank_transfer'],
                'admin_fee_rate' => 0,
                'fixed_admin_fee' => 0,
                'min_amount' => 10000,
                'max_amount' => 100000000,
                'priority' => 2,
            ],
            [
                'code' => 'bank_transfer_bni',
                'name' => 'bank_transfer_bni',
                'display_name' => 'BNI Transfer',
                'type' => 'custom',
                'config' => [
                    'bank_name' => 'Bank Negara Indonesia',
                    'account_number' => '1122334455',
                    'account_name' => 'PT YayasanEdu Indonesia',
                    'bank_branch' => 'KCP Jakarta Thamrin',
                    'swift_code' => 'BNINIDJA',
                ],
                'is_active' => true,
                'supports_recurring' => false,
                'supports_split_payment' => false,
                'supported_methods' => ['bank_transfer'],
                'admin_fee_rate' => 0,
                'fixed_admin_fee' => 0,
                'min_amount' => 10000,
                'max_amount' => 100000000,
                'priority' => 3,
            ],
            [
                'code' => 'bank_transfer_bri',
                'name' => 'bank_transfer_bri',
                'display_name' => 'BRI Transfer',
                'type' => 'custom',
                'config' => [
                    'bank_name' => 'Bank Rakyat Indonesia',
                    'account_number' => '5544332211',
                    'account_name' => 'PT YayasanEdu Indonesia',
                    'bank_branch' => 'KCP Jakarta Gatot Subroto',
                    'swift_code' => 'BRINIDJA',
                ],
                'is_active' => true,
                'supports_recurring' => false,
                'supports_split_payment' => false,
                'supported_methods' => ['bank_transfer'],
                'admin_fee_rate' => 0,
                'fixed_admin_fee' => 0,
                'min_amount' => 10000,
                'max_amount' => 100000000,
                'priority' => 4,
            ],
        ];
        
        foreach ($bankGateways as $gateway) {
            PaymentGateway::create($gateway);
        }
        
        $this->command->info('Bank transfer gateways seeded successfully!');
        
        // Display created gateways
        $this->displayGateways();
    }
    
    /**
     * Display the created bank gateways
     */
    private function displayGateways(): void
    {
        $gateways = PaymentGateway::where('name', 'like', 'bank_transfer_%')->get();
        
        $this->command->line("\n=== Bank Transfer Gateways Created ===");
        foreach ($gateways as $gateway) {
            $this->command->line("• {$gateway->display_name}");
            $this->command->line("  Name: {$gateway->name}");
            $this->command->line("  Account: {$gateway->getConfigValue('bank_name')} - {$gateway->getConfigValue('account_number')}");
            $this->command->line("  Name: {$gateway->getConfigValue('account_name')}");
            $this->command->line("  Branch: {$gateway->getConfigValue('bank_branch')}");
            $this->command->line("");
        }
    }
    
    /**
     * Reverse the seed operation
     */
    public function rollback(): void
    {
        PaymentGateway::where('name', 'like', 'bank_transfer_%')->delete();
        $this->command->info('Bank transfer gateways removed successfully!');
    }
}
