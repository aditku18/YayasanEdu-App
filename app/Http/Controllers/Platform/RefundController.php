<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\Transaction;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        $refunds = Refund::with(['transaction.foundation', 'transaction.payment', 'processedBy'])
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('refund_id', 'like', "%{$search}%")
                      ->orWhere('reason', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when($request->foundation_id, function ($query, $foundationId) {
                return $query->whereHas('transaction', function ($q) use ($foundationId) {
                    $q->where('foundation_id', $foundationId);
                });
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->latest()
            ->paginate(20);

        $foundations = \App\Models\Foundation::pluck('name', 'id');

        // Calculate comprehensive statistics for the view
        $stats = [
            'total_refunds' => Refund::count(),
            'pending_refunds' => Refund::where('status', 'pending')->count(),
            'approved_refunds' => Refund::where('status', 'approved')->count(),
            'rejected_refunds' => Refund::where('status', 'rejected')->count(),
            'total_refund_amount' => Refund::where('status', 'approved')->sum('amount'),
            'today_refund_amount' => Refund::where('status', 'approved')->whereDate('created_at', today())->sum('amount'),
            'this_month_refund_amount' => Refund::where('status', 'approved')->whereMonth('created_at', now()->month)->sum('amount'),
            'pending_amount' => Refund::where('status', 'pending')->sum('amount'),
            'full_refunds' => Refund::where('type', 'full')->count(),
            'partial_refunds' => Refund::where('type', 'partial')->count(),
        ];
        
        return view('platform.refunds.index', compact('refunds', 'foundations', 'stats'));
    }

    public function show(Refund $refund)
    {
        $refund->load(['transaction.foundation', 'transaction.payment', 'processedBy']);
        return view('platform.refunds.show', compact('refund'));
    }

    public function create()
    {
        $transactions = Transaction::where('status', 'success')
            ->whereDoesntHave('refund')
            ->with(['foundation'])
            ->latest()
            ->get();

        return view('platform.refunds.create', compact('transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'amount' => 'required|numeric|min:0|max:' . Transaction::find($request->transaction_id)->amount,
            'reason' => 'required|string|max:1000',
            'type' => 'required|in:full,partial'
        ]);

        $transaction = Transaction::find($request->transaction_id);

        // Check if refund already exists
        if ($transaction->refund) {
            return redirect()->back()->with('error', 'Refund untuk transaksi ini sudah ada.');
        }

        Refund::create([
            'transaction_id' => $request->transaction_id,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'type' => $request->type,
            'status' => 'pending',
            'requested_by' => auth()->id()
        ]);

        return redirect()->route('platform.refunds.index')
            ->with('success', 'Permintaan refund berhasil dibuat.');
    }

    public function approve(Refund $refund, Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        if ($refund->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya refund dengan status pending yang dapat disetujui.');
        }

        $refund->update([
            'status' => 'approved',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
            'notes' => $request->notes
        ]);

        // Process actual refund
        // TODO: Implement payment gateway refund processing

        return redirect()->route('platform.refunds.show', $refund)
            ->with('success', 'Refund berhasil disetujui dan diproses.');
    }

    public function reject(Refund $refund, Request $request)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        if ($refund->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya refund dengan status pending yang dapat ditolak.');
        }

        $refund->update([
            'status' => 'rejected',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->route('platform.refunds.show', $refund)
            ->with('success', 'Refund berhasil ditolak.');
    }
}
