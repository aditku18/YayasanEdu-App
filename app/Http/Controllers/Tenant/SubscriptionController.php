<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Foundation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function current()
    {
        $foundation = Foundation::where('tenant_id', tenant()->id)->first();
        $subscription = Subscription::where('foundation_id', $foundation->id)
            ->whereIn('status', ['active', 'trial'])
            ->first();

        return view('tenant.subscriptions.current', compact('subscription', 'foundation'));
    }

    public function upgrade()
    {
        $foundation = Foundation::where('tenant_id', tenant()->id)->first();
        $subscription = Subscription::where('foundation_id', $foundation->id)
            ->whereIn('status', ['active', 'trial'])
            ->first();

        // Get available plans for upgrade
        $plans = \App\Models\Plan::where('is_active', true)->get();

        return view('tenant.subscriptions.upgrade', compact('subscription', 'foundation', 'plans'));
    }

    public function history()
    {
        $foundation = Foundation::where('tenant_id', tenant()->id)->first();
        $subscriptions = Subscription::where('foundation_id', $foundation->id)
            ->with(['plan'])
            ->latest()
            ->get();

        return view('tenant.subscriptions.history', compact('subscriptions', 'foundation'));
    }
}
