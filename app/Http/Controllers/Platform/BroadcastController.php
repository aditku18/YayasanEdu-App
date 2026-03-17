<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\Foundation;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    public function index(Request $request)
    {
        $broadcasts = Broadcast::withCount(['recipients'])
            ->when($request->status, function ($query, $status) {
                if ($status === 'draft') {
                    return $query->where('is_draft', true);
                } elseif ($status === 'sent') {
                    return $query->where('is_sent', true);
                } elseif ($status === 'scheduled') {
                    return $query->whereNotNull('scheduled_at')->where('is_sent', false);
                }
                return $query;
            })
            ->when($request->type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when($request->target, function ($query, $target) {
                return $query->where('target', $target);
            })
            ->latest()
            ->paginate(20);

        // Calculate comprehensive statistics for the view
        $stats = [
            'total_broadcasts' => Broadcast::count(),
            'draft_broadcasts' => Broadcast::where('is_draft', true)->count(),
            'sent_broadcasts' => Broadcast::where('is_sent', true)->count(),
            'scheduled_broadcasts' => Broadcast::whereNotNull('scheduled_at')->where('is_sent', false)->count(),
            'total_recipients' => \App\Models\BroadcastRecipient::count(),
            'info_broadcasts' => Broadcast::where('type', 'info')->count(),
            'success_broadcasts' => Broadcast::where('type', 'success')->count(),
            'warning_broadcasts' => Broadcast::where('type', 'warning')->count(),
            'error_broadcasts' => Broadcast::where('type', 'error')->count(),
            'maintenance_broadcasts' => Broadcast::where('type', 'maintenance')->count(),
            'all_users_broadcasts' => Broadcast::where('target', 'all_users')->count(),
            'platform_admins_broadcasts' => Broadcast::where('target', 'platform_admins')->count(),
            'foundation_admins_broadcasts' => Broadcast::where('target', 'foundation_admins')->count(),
            'school_admins_broadcasts' => Broadcast::where('target', 'school_admins')->count(),
            'specific_foundations_broadcasts' => Broadcast::where('target', 'specific_foundations')->count(),
            'today_broadcasts' => Broadcast::whereDate('created_at', today())->count(),
        ];
        
        return view('platform.broadcasts.index', compact('broadcasts', 'stats'));
    }

    public function create()
    {
        $foundations = Foundation::where('status', 'active')->pluck('name', 'id');
        return view('platform.broadcasts.create', compact('foundations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'type' => 'required|in:info,success,warning,error,maintenance',
            'target' => 'required|in:all_users,platform_admins,foundation_admins,school_admins,specific_foundations',
            'target_foundations' => 'required_if:target,specific_foundations|array',
            'target_foundations.*' => 'exists:foundations,id',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $broadcast = Broadcast::create([
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'target' => $request->target,
            'target_foundations' => $request->target ? json_encode($request->target_foundations) : null,
            'scheduled_at' => $request->scheduled_at,
            'is_draft' => !$request->scheduled_at,
            'is_sent' => false,
            'created_by' => auth()->id()
        ]);

        // If scheduled for immediate send, send it
        if (!$request->scheduled_at) {
            $this->sendBroadcast($broadcast);
        }

        return redirect()->route('platform.broadcasts.show', $broadcast)
            ->with('success', 'Broadcast berhasil dibuat.');
    }

    public function show(Broadcast $broadcast)
    {
        $broadcast->load(['recipients.foundation', 'createdBy']);
        return view('platform.broadcasts.show', compact('broadcast'));
    }

    public function send(Broadcast $broadcast)
    {
        if ($broadcast->status === 'sent') {
            return redirect()->back()->with('error', 'Broadcast sudah dikirim.');
        }

        $this->sendBroadcast($broadcast);

        return redirect()->route('platform.broadcasts.show', $broadcast)
            ->with('success', 'Broadcast berhasil dikirim.');
    }

    private function sendBroadcast(Broadcast $broadcast)
    {
        // Update broadcast status
        $broadcast->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);

        // Update recipient status
        $broadcast->recipients()->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);

        // TODO: Implement actual sending logic (email, notification, etc.)
        // This would depend on your notification system
    }
}
