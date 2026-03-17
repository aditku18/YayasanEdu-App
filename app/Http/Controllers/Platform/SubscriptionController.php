<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Foundation;
use App\Models\PlatformPayment;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $subscriptions = Subscription::with(['foundation', 'plan', 'payments'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->foundation_id, function ($query, $foundationId) {
                return $query->where('foundation_id', $foundationId);
            })
            ->latest()
            ->paginate(20);

        $foundations = Foundation::pluck('name', 'id');

        // Calculate statistics for the view
        $stats = [
            'active_count' => Subscription::where('status', 'active')->count(),
            'trial_count' => Subscription::where('status', 'trial')->count(),
            'cancelled_count' => Subscription::where('status', 'cancelled')->count(),
            'expired_count' => Subscription::where('status', 'expired')->count(),
            'total_revenue' => Subscription::where('status', 'active')->sum('price'),
            'auto_renew_count' => Subscription::where('auto_renew', true)->count(),
            'this_month_count' => Subscription::whereMonth('created_at', now()->month)->count(),
        ];
        
        return view('platform.subscriptions.index', compact('subscriptions', 'foundations', 'stats'));
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['foundation', 'plan', 'payments']);
        return view('platform.subscriptions.show', compact('subscription'));
    }

    public function cancel(Subscription $subscription, Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->reason
        ]);

        return redirect()->route('platform.subscriptions.show', $subscription)
            ->with('success', 'Langganan berhasil dibatalkan.');
    }

    public function reactivate(Subscription $subscription)
    {
        if ($subscription->status !== 'cancelled') {
            return redirect()->back()->with('error', 'Hanya langganan yang dibatalkan yang dapat diaktifkan kembali.');
        }

        $subscription->update([
            'status' => 'active',
            'cancelled_at' => null,
            'cancellation_reason' => null
        ]);

        return redirect()->route('platform.subscriptions.show', $subscription)
            ->with('success', 'Langganan berhasil diaktifkan kembali.');
    }
}
