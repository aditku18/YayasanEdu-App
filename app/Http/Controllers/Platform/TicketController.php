<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Foundation;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::with(['foundation', 'user', 'responses'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->priority, function ($query, $priority) {
                return $query->where('priority', $priority);
            })
            ->when($request->foundation_id, function ($query, $foundationId) {
                return $query->where('foundation_id', $foundationId);
            })
            ->latest()
            ->paginate(20);

        $foundations = Foundation::pluck('name', 'id');
        $stats = [
            'total_tickets' => Ticket::count(),
            'open_tickets' => Ticket::where('status', 'open')->count(),
            'in_progress_tickets' => Ticket::where('status', 'in_progress')->count(),
            'closed_tickets' => Ticket::where('status', 'closed')->count(),
            'high_priority_tickets' => Ticket::where('priority', 'high')->count(),
        ];
        
        return view('platform.tickets.index', compact('tickets', 'foundations', 'stats'));
    }

    public function create()
    {
        $foundations = Foundation::pluck('name', 'id');
        return view('platform.tickets.create', compact('foundations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'foundation_id' => 'required|exists:foundations,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:technical,billing,feature,bug,other'
        ]);

        $ticket = Ticket::create([
            'foundation_id' => $request->foundation_id,
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'category' => $request->category,
            'status' => 'open',
            'ticket_number' => $this->generateTicketNumber()
        ]);

        return redirect()->route('platform.tickets.show', $ticket)
            ->with('success', 'Ticket berhasil dibuat dengan nomor: ' . $ticket->ticket_number);
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['foundation', 'user', 'responses.user']);
        return view('platform.tickets.show', compact('ticket'));
    }

    public function respond(Ticket $ticket, Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'is_internal' => 'boolean'
        ]);

        $ticket->responses()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_internal' => $request->boolean('is_internal', false)
        ]);

        // Update ticket status if needed
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return redirect()->route('platform.tickets.show', $ticket)
            ->with('success', 'Respons berhasil ditambahkan.');
    }

    public function close(Ticket $ticket, Request $request)
    {
        $request->validate([
            'resolution' => 'required|string|max:1000'
        ]);

        $ticket->update([
            'status' => 'closed',
            'resolution' => $request->resolution,
            'closed_at' => now(),
            'closed_by' => auth()->id()
        ]);

        return redirect()->route('platform.tickets.show', $ticket)
            ->with('success', 'Ticket berhasil ditutup.');
    }

    public function reopen(Ticket $ticket)
    {
        if ($ticket->status !== 'closed') {
            return redirect()->back()->with('error', 'Hanya ticket yang ditutup yang dapat dibuka kembali.');
        }

        $ticket->update([
            'status' => 'open',
            'resolution' => null,
            'closed_at' => null,
            'closed_by' => null
        ]);

        return redirect()->route('platform.tickets.show', $ticket)
            ->with('success', 'Ticket berhasil dibuka kembali.');
    }

    private function generateTicketNumber()
    {
        $prefix = 'TKT';
        $date = now()->format('Ymd');
        $lastTicket = Ticket::whereDate('created_at', now())
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTicket) {
            $lastNumber = intval(substr($lastTicket->ticket_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
