<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with(['foundation', 'plan', 'payment'])
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('transaction_id', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when($request->foundation_id, function ($query, $foundationId) {
                return $query->where('foundation_id', $foundationId);
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
        $plans = \App\Models\Plan::pluck('name', 'id');

        // Calculate comprehensive statistics for the view
        $stats = [
            'total_transactions' => Transaction::count(),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
            'successful_transactions' => Transaction::where('status', 'success')->count(),
            'failed_transactions' => Transaction::where('status', 'failed')->count(),
            'total_amount' => Transaction::where('status', 'success')->sum('amount'),
            'today_amount' => Transaction::where('status', 'success')->whereDate('created_at', today())->sum('amount'),
            'this_month_amount' => Transaction::where('status', 'success')->whereMonth('created_at', now()->month)->sum('amount'),
            'pending_amount' => Transaction::where('status', 'pending')->sum('amount'),
            'subscription_transactions' => Transaction::where('type', 'subscription')->count(),
            'addon_transactions' => Transaction::where('type', 'addon')->count(),
            'other_transactions' => Transaction::where('type', 'other')->count(),
        ];
        
        return view('platform.transactions.index', compact('transactions', 'foundations', 'plans', 'stats'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['foundation', 'plan', 'payment', 'refund']);
        return view('platform.transactions.show', compact('transaction'));
    }

    public function create()
    {
        $foundations = \App\Models\Foundation::pluck('name', 'id');
        $plans = \App\Models\Plan::pluck('name', 'id');
        return view('platform.transactions.create', compact('foundations', 'plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'foundation_id' => 'required|exists:foundations,id',
            'plan_id' => 'required|exists:plans,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:subscription,addon,other',
            'description' => 'nullable|string|max:500'
        ]);

        Transaction::create([
            'foundation_id' => $request->foundation_id,
            'plan_id' => $request->plan_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'description' => $request->description,
            'status' => 'pending',
            'created_by' => auth()->id()
        ]);

        return redirect()->route('platform.transactions.index')
            ->with('success', 'Transaksi berhasil dibuat.');
    }
}
