@extends('layouts.dashboard')

@section('title', 'Pengeluaran')

@section('content')
<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Pengeluaran</h1>
            <p class="text-slate-500">Kelola pengeluaran sekolah</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('tenant.school.finance.expense-categories.index') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-50 transition-colors">
                Kategori
            </a>
            <a href="{{ route('tenant.school.finance.expenses.create') }}" class="px-4 py-2 bg-primary-600 text-white rounded-xl text-sm font-medium hover:bg-primary-700 transition-colors">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Pengeluaran Baru
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <form method="GET" class="grid grid-cols5 gap-4-1 md:grid-cols-">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : 'selected' }}>Disetujui</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Dibayar</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                <select name="expense_category_id" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('expense_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-slate-800 text-white rounded-xl text-sm font-medium hover:bg-slate-900 transition-colors">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Expenses Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No. Pengeluaran</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-900">{{ $expense->expense_number }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $expense->expenseCategory->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate">{{ $expense->description }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-slate-900">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-lg
                                    @if($expense->status === 'paid') bg-emerald-100 text-emerald-700
                                    @elseif($expense->status === 'approved') bg-blue-100 text-blue-700
                                    @elseif($expense->status === 'pending') bg-amber-100 text-amber-700
                                    @elseif($expense->status === 'rejected') bg-red-100 text-red-700
                                    @else bg-slate-100 text-slate-700 @endif">
                                    @if($expense->status === 'draft') Draft
                                    @elseif($expense->status === 'pending') Menunggu
                                    @elseif($expense->status === 'approved') Disetujui
                                    @elseif($expense->status === 'paid') Dibayar
                                    @elseif($expense->status === 'rejected') Ditolak
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('tenant.school.finance.expenses.show', $expense->id) }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada pengeluaran ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($expenses->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
