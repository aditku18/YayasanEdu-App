<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index()
    {
        $bills = Bill::latest()->paginate(20);
        return view('tenant.bills.index', compact('bills'));
    }

    public function create()
    {
        return view('tenant.bills.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'vendor' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,paid,overdue',
        ]);

        $bill = Bill::create($validated);

        return redirect()->route('tenant.bill.index')->with('success', 'Bill created successfully');
    }

    public function show(Bill $bill)
    {
        return view('tenant.bills.show', compact('bill'));
    }

    public function edit(Bill $bill)
    {
        return view('tenant.bills.edit', compact('bill'));
    }

    public function update(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'vendor' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,paid,overdue',
        ]);

        $bill->update($validated);

        return redirect()->route('tenant.bill.index')->with('success', 'Bill updated successfully');
    }

    public function destroy(Bill $bill)
    {
        $bill->delete();

        return redirect()->route('tenant.bill.index')->with('success', 'Bill deleted successfully');
    }
}
