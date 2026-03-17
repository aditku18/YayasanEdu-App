<?php

namespace App\Services;

use App\Models\Plugin;
use App\Models\Foundation;
use App\Models\Invoice;
use App\Models\PluginInstallation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PluginPurchaseService
{
    /**
     * Create invoice for plugin purchase
     */
    public function createInvoice(Plugin $plugin, Foundation $foundation): Invoice
    {
        // Generate unique invoice number
        $invoiceNumber = $this->generateInvoiceNumber($foundation);
        
        // Calculate total amount (plugin price + potential admin fee)
        $totalAmount = $plugin->price;
        
        // Create invoice record
        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'foundation_id' => $foundation->id,
            'amount' => $totalAmount,
            'status' => 'pending',
            'due_date' => now()->addDays(3),
            'description' => "Pembelian Plugin: {$plugin->name}",
            'items' => json_encode([
                'type' => 'plugin_purchase',
                'plugin_id' => $plugin->id,
                'plugin_name' => $plugin->name,
                'plugin_version' => $plugin->version ?? '1.0.0',
                'price' => $plugin->price,
                'developer' => $plugin->developer ?? 'EduSaaS Official',
            ]),
            'payment_token' => $this->generatePaymentToken(),
            'created_at' => now(),
        ]);
        
        return $invoice;
    }
    
    /**
     * Install free plugin directly
     */
    public function installFreePlugin(Plugin $plugin, Foundation $foundation): bool
    {
        try {
            DB::beginTransaction();
            
            // Create or update plugin installation
            PluginInstallation::updateOrCreate(
                [
                    'plugin_id' => $plugin->id,
                    'foundation_id' => $foundation->id,
                ],
                [
                    'is_active' => true,
                    'installed_at' => now(),
                    'installed_by' => Auth::id(),
                    'last_updated_at' => now(),
                    'subscription_type' => 'free',
                    'subscription_amount' => 0,
                    'settings' => [],
                ]
            );
            
            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Install plugin after successful payment
     */
    public function installAfterPayment(Invoice $invoice): bool
    {
        try {
            DB::beginTransaction();
            
            // Get plugin details from invoice items
            $items = json_decode($invoice->items, true);
            $pluginId = $items['plugin_id'] ?? null;
            
            if (!$pluginId) {
                throw new \Exception('Plugin ID not found in invoice items');
            }
            
            $plugin = Plugin::findOrFail($pluginId);
            $foundation = $invoice->foundation;
            
            // Create or update plugin installation
            PluginInstallation::updateOrCreate(
                [
                    'plugin_id' => $plugin->id,
                    'foundation_id' => $foundation->id,
                ],
                [
                    'is_active' => true,
                    'installed_at' => now(),
                    'installed_by' => $invoice->created_by ?? Auth::id(),
                    'last_updated_at' => now(),
                    'subscription_type' => 'paid',
                    'subscription_amount' => $invoice->amount,
                    'subscription_invoice_id' => $invoice->id,
                    'settings' => [],
                ]
            );
            
            // Update invoice with plugin installation reference
            $invoice->update([
                'status' => 'completed',
                'paid_at' => now(),
                'items' => json_encode(array_merge($items, [
                    'installed_at' => now()->toISOString(),
                    'installation_id' => PluginInstallation::where('plugin_id', $plugin->id)
                        ->where('foundation_id', $foundation->id)
                        ->first()->id,
                ])),
            ]);
            
            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber(Foundation $foundation): string
    {
        $prefix = 'PLG';
        $date = date('Ym');
        $foundationId = str_pad($foundation->id, 4, '0', STR_PAD_LEFT);
        
        // Get last invoice number for this month
        $lastInvoice = Invoice::where('invoice_number', 'like', "{$prefix}{$date}%")
            ->orderBy('invoice_number', 'desc')
            ->first();
        
        $sequence = 1;
        if ($lastInvoice) {
            $lastSequence = (int) substr($lastInvoice->invoice_number, -4);
            $sequence = $lastSequence + 1;
        }
        
        $sequence = str_pad($sequence, 4, '0', STR_PAD_LEFT);
        
        return "{$prefix}{$date}-{$foundationId}-{$sequence}";
    }
    
    /**
     * Generate secure payment token
     */
    private function generatePaymentToken(): string
    {
        return Str::random(32) . '-' . time();
    }
    
    /**
     * Check if foundation can install plugin
     */
    public function canInstallPlugin(Plugin $plugin, Foundation $foundation): array
    {
        $errors = [];
        
        // Check if plugin is available in marketplace
        if (!$plugin->is_available_in_marketplace) {
            $errors[] = 'Plugin tidak tersedia di marketplace.';
        }
        
        // Check if already installed
        $existingInstallation = PluginInstallation::where('foundation_id', $foundation->id)
            ->where('plugin_id', $plugin->id)
            ->first();
            
        if ($existingInstallation && $existingInstallation->is_active) {
            $errors[] = 'Plugin sudah terinstall dan aktif.';
        }
        
        // Check foundation subscription limits (if any)
        if ($plugin->price > 0 && $foundation->subscription_plan) {
            // Add subscription validation logic here if needed
            // For now, allow all paid plugins
        }
        
        return [
            'can_install' => empty($errors),
            'errors' => $errors,
        ];
    }
    
    /**
     * Get plugin purchase summary
     */
    public function getPurchaseSummary(Plugin $plugin, Foundation $foundation): array
    {
        $canInstall = $this->canInstallPlugin($plugin, $foundation);
        
        $summary = [
            'plugin' => [
                'id' => $plugin->id,
                'name' => $plugin->name,
                'price' => $plugin->price,
                'developer' => $plugin->developer ?? 'EduSaaS Official',
                'version' => $plugin->version ?? '1.0.0',
                'category' => $plugin->category ?? 'General',
            ],
            'can_install' => $canInstall['can_install'],
            'errors' => $canInstall['errors'],
        ];
        
        if ($plugin->price > 0) {
            $summary['payment'] = [
                'amount' => $plugin->price,
                'formatted_amount' => 'Rp ' . number_format($plugin->price, 0, ',', '.'),
                'payment_methods' => ['bank_transfer'],
                'due_days' => 3,
            ];
        } else {
            $summary['installation'] = [
                'type' => 'free',
                'instant' => true,
            ];
        }
        
        return $summary;
    }
}
