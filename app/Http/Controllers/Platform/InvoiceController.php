<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Services\Billing\PlatformInvoiceService;
use App\Models\Foundation;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function __construct(
        private readonly PlatformInvoiceService $platformInvoiceService
    ) {
    }
    public function index(Request $request)
    {
        $foundations = Foundation::with(['plan', 'subscriptions'])
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                if ($status === 'active') {
                    return $query->where('status', 'active');
                } elseif ($status === 'expired') {
                    return $query->where('subscription_ends_at', '<', now());
                } elseif ($status === 'expiring') {
                    return $query->where('subscription_ends_at', '>', now())
                        ->where('subscription_ends_at', '<=', now()->addDays(30));
                }
            })
            ->when($request->plan_id, function ($query, $planId) {
                return $query->where('plan_id', $planId);
            })
            ->latest()
            ->paginate(20);

        $plans = Plan::pluck('name', 'id');

        // Fetch recent invoices
        $recentInvoices = Invoice::with('foundation')
            ->latest()
            ->limit(10)
            ->get();

        // Calculate statistics for the view
        $stats = [
            'total_foundations' => Foundation::count(),
            'active_subscriptions' => Foundation::where('status', 'active')->count(),
            'expired_subscriptions' => Foundation::where('subscription_ends_at', '<', now())
                ->where('status', '!=', 'trial')->count(),
            'expiring_soon' => Foundation::where('subscription_ends_at', '>', now())
                ->where('subscription_ends_at', '<=', now()->addDays(30))->count(),
            'total_revenue' => Subscription::where('status', 'active')->sum('price'),
            'this_month_revenue' => Subscription::where('status', 'active')
                ->whereMonth('created_at', now()->month)->sum('price'),
            
            // Actual Invoice Stats
            'total_invoices' => Invoice::count(),
            'paid_invoices' => Invoice::where('status', 'paid')->count(),
            'pending_invoices' => Invoice::where('status', 'pending')->count(),
            'overdue_invoices' => Invoice::where('status', 'pending')
                ->where('due_date', '<', now())->count(),
        ];

        return view('platform.invoices.index', compact('foundations', 'plans', 'stats', 'recentInvoices'));
    }

    public function show(Foundation $foundation)
    {
        $foundation->load(['plan', 'subscriptions', 'users']);

        // Get all invoices for this foundation
        $invoices = Invoice::where('foundation_id', $foundation->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate invoice statistics
        $invoiceStats = [
            'total_invoices' => $invoices->count(),
            'paid_invoices' => $invoices->where('status', 'paid')->count(),
            'pending_invoices' => $invoices->where('status', 'pending')->count(),
            'overdue_invoices' => $invoices->filter(fn($inv) => $inv->isOverdue())->count(),
            'total_amount' => $invoices->sum('amount'),
            'paid_amount' => $invoices->where('status', 'paid')->sum('amount'),
            'pending_amount' => $invoices->where('status', 'pending')->sum('amount'),
            'last_invoice' => $invoices->first(),
        ];

        return view('platform.invoices.show', compact('foundation', 'invoiceStats', 'invoices'));
    }

    public function generate(Foundation $foundation, Request $request)
    {
        $validated = $request->validate([
            'billing_cycle' => 'required|in:monthly,yearly',
            'due_days' => 'required|integer|in:7,14,30,60',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $invoice = $this->platformInvoiceService->generateForFoundation($foundation, $validated);

            return redirect()->route('platform.invoices.show', $foundation)
                ->with('success', "Invoice #{$invoice->invoice_number} berhasil dibuat untuk {$foundation->name}.");
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Invoice generation failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal membuat invoice. Silakan coba lagi.');
        }
    }

    public function send(Foundation $foundation, Request $request)
    {
        $invoice = Invoice::where('foundation_id', $foundation->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$invoice) {
            return redirect()->back()->with('error', 'Tidak ada invoice tertunda untuk yayasan ini.');
        }

        // Generate token if missing
        if (!$invoice->payment_token) {
            $invoice->update(['payment_token' => Str::random(40)]);
        }

        // In production, send email here:
        // Mail::to($foundation->email)->send(new InvoiceCreated($invoice));
        Log::info('Simulating invoice email send to ' . $foundation->email . ' for invoice #' . $invoice->invoice_number);

        // Log the activity
        activity()
            ->on($invoice)
            ->withProperties(['foundation' => $foundation->name, 'email' => $foundation->email])
            ->log('invoice_sent');

        return redirect()->route('platform.invoices.show', $foundation)
            ->with('success', 'Invoice berhasil dikirim ke ' . $foundation->email);
    }

    /**
     * Generate payment link for foundation to pay their invoice
     */
    public function paymentLink(Invoice $invoice)
    {
        $invoice->load('foundation');

        if ($invoice->status === 'paid') {
            return redirect()->back()->with('error', 'Invoice sudah lunas.');
        }

        // Ensure token exists
        if (!$invoice->payment_token) {
            $invoice->update(['payment_token' => Str::random(40)]);
        }

        // Create payment URL for the foundation
        // Fixed: Use payment_token instead of MD5 of ID
        $paymentUrl = route('tenant.invoice.pay', ['invoice' => $invoice->id, 'token' => $invoice->payment_token]);

        return view('platform.invoices.payment-link', compact('invoice', 'paymentUrl'));
    }

    /**
     * Send payment link to foundation via email
     */
    public function sendPaymentLink(Invoice $invoice, Request $request)
    {
        // ... (existing code)
    }

    public function verifyPayment(Invoice $invoice, Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string',
        ]);

        if ($invoice->status !== 'verifying') {
            return redirect()->back()->with('error', 'Invoice tidak dalam status verifikasi.');
        }

        if ($validated['action'] === 'approve') {
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
                'notes' => $invoice->notes . "\n[System: Payment verified by admin. " . ($validated['notes'] ?? '') . "]",
            ]);

            return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi.');
        } else {
            $invoice->update([
                'status' => 'rejected',
                'notes' => $invoice->notes . "\n[System: Payment rejected by admin. Reason: " . ($validated['notes'] ?? '') . "]",
            ]);

            return redirect()->back()->with('warning', 'Pembayaran ditolak.');
        }
    }
}
