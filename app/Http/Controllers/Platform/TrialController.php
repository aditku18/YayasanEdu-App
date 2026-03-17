<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Foundation;
use Illuminate\Http\Request;

class TrialController extends Controller
{
    public function index(Request $request)
    {
        $trials = Foundation::where('status', 'trial')
            ->orWhere(function ($query) {
                $query->whereNotNull('trial_ends_at')
                    ->where('trial_ends_at', '>', now());
            })
            ->with(['user', 'plan'])
            ->when($request->status, function ($query, $status) {
                if ($status === 'active') {
                    return $query->where('trial_ends_at', '>', now());
                } elseif ($status === 'expired') {
                    return $query->where('trial_ends_at', '<=', now());
                }
            })
            ->latest('trial_ends_at')
            ->paginate(20);

        $stats = [
            'total_trials' => Foundation::where('status', 'trial')
                ->orWhere(function ($query) {
                    $query->whereNotNull('trial_ends_at')
                        ->where('trial_ends_at', '>', now());
                })->count(),
            'active_trials' => Foundation::where('status', 'trial')
                ->orWhere(function ($query) {
                    $query->whereNotNull('trial_ends_at')
                        ->where('trial_ends_at', '>', now());
                })->count(),
            'expired_trials' => Foundation::whereNotNull('trial_ends_at')
                ->where('trial_ends_at', '<=', now())
                ->where('status', '!=', 'active')
                ->count(),
            'expiring_soon' => Foundation::where('status', 'trial')
                ->orWhere(function ($query) {
                    $query->whereNotNull('trial_ends_at')
                        ->where('trial_ends_at', '>', now())
                        ->where('trial_ends_at', '<=', now()->addDays(7));
                })->count(),
        ];
        
        return view('platform.trials.index', compact('trials', 'stats'));
    }

    public function show(Foundation $trial)
    {
        if (!$this->isTrial($trial)) {
            return redirect()->route('platform.trials.index')->with('error', 'Data trial tidak ditemukan.');
        }

        $trial->load(['user', 'plan', 'schools']);
        return view('platform.trials.show', compact('trial'));
    }

    public function extend(Foundation $trial, Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:30',
            'reason' => 'required|string|max:500'
        ]);

        if (!$this->isTrial($trial)) {
            return redirect()->back()->with('error', 'Hanya yayasan dalam masa trial yang dapat diperpanjang.');
        }

        $trial->update([
            'trial_ends_at' => $trial->trial_ends_at ? $trial->trial_ends_at->addDays($request->days) : now()->addDays($request->days),
            'trial_extension_reason' => $request->reason
        ]);

        return redirect()->route('platform.trials.show', $trial)
            ->with('success', 'Masa trial berhasil diperpanjang ' . $request->days . ' hari.');
    }

    public function convert(Foundation $trial, Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id'
        ]);

        if (!$this->isTrial($trial)) {
            return redirect()->back()->with('error', 'Hanya yayasan dalam masa trial yang dapat dikonversi.');
        }

        // Convert trial to paid subscription
        $trial->update([
            'status' => 'active',
            'trial_ends_at' => null,
            'plan_id' => $request->plan_id,
        ]);

        // Create subscription record
        // TODO: Implement subscription creation

        return redirect()->route('platform.trials.show', $trial)
            ->with('success', 'Trial berhasil dikonversi ke langganan berbayar.');
    }

    /**
     * Check if a foundation is in trial status
     */
    private function isTrial(Foundation $foundation)
    {
        return $foundation->status === 'trial' || 
               ($foundation->trial_ends_at && $foundation->trial_ends_at->isFuture());
    }
}
