<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Plugin;
use App\Models\PluginInstallation;
use App\Models\Foundation;
use App\Models\Invoice;
use App\Services\PluginPurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MarketplaceController extends Controller
{
    protected PluginPurchaseService $pluginPurchaseService;

    public function __construct(PluginPurchaseService $pluginPurchaseService)
    {
        $this->pluginPurchaseService = $pluginPurchaseService;
    }
    public function index()
    {
        $plugins = Plugin::where('is_available_in_marketplace', true)->get();
        
        // Get installed plugins for current tenant
        $foundation = $this->getCurrentFoundation();
        $installedPlugins = PluginInstallation::where('foundation_id', $foundation->id)
            ->where('is_active', true)
            ->pluck('plugin_id')
            ->toArray();
            
        return view('tenant.marketplace.index', compact('plugins', 'installedPlugins'));
    }

    public function show(Plugin $plugin)
    {
        // Check if already installed
        $foundation = $this->getCurrentFoundation();
        $isInstalled = PluginInstallation::where('foundation_id', $foundation->id)
            ->where('plugin_id', $plugin->id)
            ->where('is_active', true)
            ->exists();
            
        // Get purchase summary
        $purchaseSummary = $this->pluginPurchaseService->getPurchaseSummary($plugin, $foundation);
            
        return view('tenant.marketplace.show', compact('plugin', 'isInstalled', 'purchaseSummary'));
    }

    public function purchase(Request $request, Plugin $plugin)
    {
        $user = Auth::user();
        $foundation = $this->getCurrentFoundation();
        
        try {
            // Check if user can install this plugin
            $canInstall = $this->pluginPurchaseService->canInstallPlugin($plugin, $foundation);
            
            if (!$canInstall['can_install']) {
                return redirect()->back()->with('error', implode(' ', $canInstall['errors']));
            }
            
            // Handle free plugins - install directly
            if ($plugin->price == 0) {
                $this->pluginPurchaseService->installFreePlugin($plugin, $foundation);
                
                return redirect()->route('tenant.plugins.index')
                    ->with('success', "Plugin '{$plugin->name}' berhasil diinstall!");
            }
            
            // Handle paid plugins - create invoice and redirect to payment
            DB::beginTransaction();
            
            $invoice = $this->pluginPurchaseService->createInvoice($plugin, $foundation);
            
            // Store purchase attempt in session for tracking
            session([
                'plugin_purchase' => [
                    'plugin_id' => $plugin->id,
                    'plugin_name' => $plugin->name,
                    'invoice_id' => $invoice->id,
                    'foundation_id' => $foundation->id,
                    'user_id' => $user->id,
                ]
            ]);
            
            DB::commit();
            
            // Redirect to payment flow
            return redirect()->route('tenant.invoice.pay', $invoice->id)
                ->with('success', 'Invoice berhasil dibuat. Silakan lanjutkan pembayaran.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Plugin purchase error: ' . $e->getMessage(), [
                'plugin_id' => $plugin->id,
                'foundation_id' => $foundation->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses pembelian: ' . $e->getMessage());
        }
    }
    
    /**
     * Handle plugin installation after successful payment
     */
    public function installAfterPayment(Request $request, Invoice $invoice)
    {
        try {
            // Verify this is a plugin purchase invoice
            $items = json_decode($invoice->items, true);
            if (!isset($items['type']) || $items['type'] !== 'plugin_purchase') {
                return redirect()->back()->with('error', 'Invoice bukan untuk pembelian plugin.');
            }
            
            // Install the plugin
            $this->pluginPurchaseService->installAfterPayment($invoice);
            
            // Get plugin details for success message
            $pluginName = $items['plugin_name'] ?? 'Plugin';
            
            // Clear plugin purchase session
            session()->forget('plugin_purchase');
            
            return redirect()->route('tenant.plugins.index')
                ->with('success', "Plugin '{$pluginName}' berhasil diinstall setelah pembayaran!");
                
        } catch (\Exception $e) {
            Log::error('Plugin installation after payment error: ' . $e->getMessage(), [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->back()
                ->with('error', 'Gagal menginstall plugin setelah pembayaran: ' . $e->getMessage());
        }
    }
    
    /**
     * Uninstall/deactivate a plugin
     */
    public function uninstall(Request $request, Plugin $plugin)
    {
        $foundation = $this->getCurrentFoundation();
        
        $installation = PluginInstallation::where('foundation_id', $foundation->id)
            ->where('plugin_id', $plugin->id)
            ->where('is_active', true)
            ->first();
            
        if (!$installation) {
            return redirect()->back()->with('error', 'Plugin tidak ditemukan atau sudah tidak aktif.');
        }
        
        $installation->update([
            'is_active' => false,
            'last_updated_at' => now(),
        ]);
        
        return redirect()->route('tenant.plugins.index')
            ->with('success', "Plugin '{$plugin->name}' berhasil dinonaktifkan.");
    }
    
    /**
     * Get current foundation from tenant context
     */
    private function getCurrentFoundation()
    {
        // Get foundation from domain parsing (more reliable)
        $domain = request()->getHost();
        $subdomain = str_replace('.localhost', '', $domain);
        
        // Try to get foundation by subdomain
        $foundation = Foundation::where('subdomain', $subdomain)->first();
        
        if (!$foundation) {
            // Try with 'tenant-' prefix
            $subdomain = str_replace('tenant-', '', $subdomain);
            $foundation = Foundation::where('subdomain', $subdomain)->first();
        }
        
        if (!$foundation) {
            // Last resort: get first foundation (for development)
            $foundation = Foundation::first();
        }
        
        return $foundation;
    }
}
