<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\PluginInstallationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Services\PaymentGatewayManager;

class InvoiceController extends Controller
{
    protected PaymentGatewayManager $gatewayManager;
    protected PluginInstallationService $pluginInstallationService;

    public function __construct(PaymentGatewayManager $gatewayManager, PluginInstallationService $pluginInstallationService)
    {
        $this->gatewayManager = $gatewayManager;
        $this->pluginInstallationService = $pluginInstallationService;
    }
    public function index()
    {
        $foundation = \App\Models\Foundation::where('tenant_id', tenant()->id)->first();

        if (!$foundation) {
            return redirect()->back()->with('error', 'Foundation not found.');
        }

        $invoices = Invoice::where('foundation_id', $foundation->id)->latest()->get();
        return view('tenant.invoices.index', compact('invoices', 'foundation'));
    }

    public function show(Invoice $invoice)
    {
        // Safety check: ensure invoice belongs to this tenant's foundation
        $foundation = \App\Models\Foundation::where('tenant_id', tenant()->id)->first();
        if (!$foundation || $invoice->foundation_id !== $foundation->id) {
            abort(403, 'Unauthorized access to invoice.');
        }

        $invoice->load(['foundation', 'subscription.plan']);

        return view('tenant.invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice)
    {
        // Logic for downloading invoice - TODO: Implement PDF generation
        if (!$invoice->file_path || !file_exists($invoice->file_path)) {
            return redirect()->back()->with('error', 'File invoice tidak ditemukan.');
        }
        return response()->download($invoice->file_path);
    }

    public function pay(Request $request, Invoice $invoice)
    {
        // Safety check: ensure invoice belongs to this tenant's foundation
        $foundation = \App\Models\Foundation::where('tenant_id', tenant()->id)->first();
        if (!$foundation || $invoice->foundation_id !== $foundation->id) {
            abort(403, 'Unauthorized access to invoice.');
        }

        // Token check (optional but recommended for secure links)
        if ($request->has('token') && $invoice->payment_token !== $request->token) {
            abort(403, 'Invalid or expired payment token.');
        }

        $invoice->load(['foundation', 'subscription.plan']);

        // Get available payment gateways
        $paymentGateways = \App\Models\PaymentGateway::where('is_active', true)->get();

        // Check if invoice is already paid
        if ($invoice->status === 'paid') {
            return redirect()->route('tenant.invoice.show', $invoice->id)
                ->with('info', 'Invoice sudah lunas.');
        }

        return view('tenant.invoices.pay', compact('invoice', 'paymentGateways'));
    }

    public function processPayment(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'payment_gateway_id' => 'required|exists:central.payment_gateways,id',
        ]);

        // Get the payment gateway
        $paymentGateway = \App\Models\PaymentGateway::findOrFail($validated['payment_gateway_id']);

        // Check if gateway is active and configured
        if (!$paymentGateway->is_active || !$paymentGateway->isConfigured()) {
            return redirect()->back()
                ->with('error', 'Metode pembayaran tidak tersedia. Silakan pilih metode lain.');
        }

        // Check if invoice is already paid
        if ($invoice->status === 'paid') {
            return redirect()->route('tenant.invoice.show', $invoice->id)
                ->with('info', 'Invoice sudah lunas.');
        }

        // Check if amount is valid for this gateway
        if (!$paymentGateway->isAmountValid($invoice->amount)) {
            return redirect()->back()
                ->with('error', 'Jumlah pembayaran tidak valid untuk metode ini.');
        }

        try {
            // Use invoice number as payment reference
            $paymentReference = $invoice->invoice_number;

            // Store payment attempt in session for webhook handling
            session([
                'payment_attempt' => [
                    'invoice_id' => $invoice->id,
                    'gateway_id' => $paymentGateway->id,
                    'reference' => $paymentReference,
                    'amount' => $invoice->amount,
                    'method' => $validated['payment_method'],
                ]
            ]);

            // For now, simulate payment processing
            // In production, integrate with actual payment gateway APIs
            switch ($paymentGateway->name) {
                case 'midtrans':
                    return $this->processMidtransPayment($invoice, $paymentGateway, $paymentReference);
                case 'xendit':
                    return $this->processXenditPayment($invoice, $paymentGateway, $paymentReference);
                case 'manual':
                case 'bank_transfer_bca':
                case 'bank_transfer_mandiri':
                case 'bank_transfer_bni':
                case 'bank_transfer_bri':
                    return $this->processManualPayment($invoice, $paymentGateway, $paymentReference);
                default:
                    return redirect()->back()
                        ->with('error', 'Metode pembayaran belum didukung.');
            }

        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage(), [
                'invoice_id' => $invoice->id,
                'gateway_id' => $paymentGateway->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
        }
    }

    private function processMidtransPayment($invoice, $gateway, $reference)
    {
        $data = [
            'order_id' => $reference,
            'amount' => $invoice->amount,
            'customer' => [
                'first_name' => $invoice->foundation->name,
                'email' => $invoice->foundation->email,
            ],
            'items' => [
                [
                    'id' => $invoice->invoice_number,
                    'price' => (int) $invoice->amount,
                    'quantity' => 1,
                    'name' => 'Langganan ' . ($invoice->subscription->plan->name ?? 'Platform'),
                ]
            ]
        ];

        try {
            $response = $this->gatewayManager->createPayment('midtrans', $data);
            
            if (isset($response['token'])) {
                // If it's a snap token, we can pass it to a view or redirect
                // For direct redirect to midtrans hosted page:
                return redirect($response['redirect_url']);
            }
            
            return redirect()->back()->with('error', 'Gagal mendapatkan token pembayaran Midtrans.');
        } catch (\Exception $e) {
            Log::error('Midtrans payment error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Kesalahan Midtrans: ' . $e->getMessage());
        }
    }

    private function processXenditPayment($invoice, $gateway, $reference)
    {
        $data = [
            'order_id' => $reference,
            'amount' => $invoice->amount,
            'description' => 'Pembayaran Invoice #' . $invoice->invoice_number,
            'customer' => [
                'first_name' => $invoice->foundation->name,
                'email' => $invoice->foundation->email,
            ],
            'success_url' => route('tenant.invoice.show', $invoice->id),
            'failure_url' => route('tenant.invoice.show', $invoice->id),
        ];

        try {
            $response = $this->gatewayManager->createPayment('xendit', $data);
            
            if (isset($response['invoice_url'])) {
                return redirect($response['invoice_url']);
            }
            
            return redirect()->back()->with('error', 'Gagal membuat invoice Xendit.');
        } catch (\Exception $e) {
            Log::error('Xendit payment error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Kesalahan Xendit: ' . $e->getMessage());
        }
    }

    private function processManualPayment($invoice, $gateway, $reference)
    {
        // Show manual payment instructions
        return view('tenant.invoices.manual-payment', compact('invoice', 'gateway', 'reference'));
    }

    public function uploadReceipt(Request $request, Invoice $invoice)
    {
        $request->validate([
            'payment_receipt' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($invoice->status === 'paid') {
            return redirect()->back()->with('error', 'Invoice sudah lunas.');
        }

        if ($request->hasFile('payment_receipt')) {
            $file = $request->file('payment_receipt');
            $filename = 'receipt_' . $invoice->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('receipts', $filename, 'central_public');

            $invoice->update([
                'payment_receipt' => $path,
                'status' => 'verifying',
            ]);

            // Check if this is a plugin purchase invoice
            $items = json_decode($invoice->items, true);
            if (isset($items['type']) && $items['type'] === 'plugin_purchase') {
                // For manual payments, we'll install after admin verification
                // But we can prepare the installation
                $pluginName = $items['plugin_name'] ?? 'Plugin';
                
                return redirect()->route('tenant.invoice.show', $invoice->id)
                    ->with('success', 'Bukti pembayaran berhasil diunggah. Plugin "' . $pluginName . '" akan diinstall setelah verifikasi admin (maksimal 1x24 jam).');
            }

            return redirect()->route('tenant.invoice.show', $invoice->id)
                ->with('success', 'Bukti pembayaran berhasil diunggah. Mohon tunggu verifikasi admin.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah bukti pembayaran.');
    }
    
    /**
     * Verify manual payment and install plugin (admin action)
     */
    public function verifyPayment(Invoice $invoice)
    {
        try {
            // Mark invoice as paid
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            // Check if this is a plugin purchase invoice
            $items = json_decode($invoice->items, true);
            if (isset($items['type']) && $items['type'] === 'plugin_purchase') {
                // Install the plugin
                $this->pluginInstallationService->installAfterPayment($invoice);
                
                $pluginName = $items['plugin_name'] ?? 'Plugin';
                
                Log::info('Plugin installed after manual payment verification', [
                    'invoice_id' => $invoice->id,
                    'plugin_name' => $pluginName,
                    'verified_by' => auth()->id(),
                ]);
                
                return redirect()->back()
                    ->with('success', "Pembayaran diverifikasi dan plugin '{$pluginName}' berhasil diinstall.");
            }

            return redirect()->back()
                ->with('success', 'Pembayaran berhasil diverifikasi.');
                
        } catch (\Exception $e) {
            Log::error('Failed to verify payment and install plugin', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->back()
                ->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }

    public function paymentCallback($gateway, $reference)
    {
        // Get payment attempt from session
        $paymentAttempt = session('payment_attempt');
        
        if (!$paymentAttempt || $paymentAttempt['reference'] !== $reference) {
            return redirect()->route('tenant.invoice.index')
                ->with('error', 'Sesi pembayaran tidak valid. Silakan coba lagi.');
        }

        $invoice = Invoice::find($paymentAttempt['invoice_id']);
        if (!$invoice) {
            return redirect()->route('tenant.invoice.index')
                ->with('error', 'Invoice tidak ditemukan.');
        }

        $paymentGateway = \App\Models\PaymentGateway::find($paymentAttempt['gateway_id']);
        if (!$paymentGateway) {
            return redirect()->route('tenant.invoice.pay', $invoice->id)
                ->with('error', 'Metode pembayaran tidak valid.');
        }

        try {
            // For simulation, we'll mark the invoice as paid
            // In production, this would verify the actual payment with the gateway
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // Check if this is a plugin purchase invoice
            $items = json_decode($invoice->items, true);
            if (isset($items['type']) && $items['type'] === 'plugin_purchase') {
                // Install the plugin automatically
                $this->pluginInstallationService->installAfterPayment($invoice);
                
                // Get plugin details for success message
                $pluginName = $items['plugin_name'] ?? 'Plugin';
                
                // Clear payment attempt session
                session()->forget('payment_attempt');
                
                return redirect()->route('tenant.plugins.index')
                    ->with('success', "Pembayaran berhasil! Plugin '{$pluginName}' telah diinstall.");
            }

            // Clear payment attempt session
            session()->forget('payment_attempt');

            return redirect()->route('tenant.invoice.show', $invoice->id)
                ->with('success', 'Pembayaran berhasil! Invoice telah dilunasi.');
                
        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage(), [
                'invoice_id' => $invoice->id,
                'gateway' => $gateway,
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->route('tenant.invoice.show', $invoice->id)
                ->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }
}
