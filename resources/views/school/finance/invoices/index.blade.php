@extends('layouts.dashboard')

@section('title', 'Tagihan Siswa')

@section('content')
<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Tagihan Siswa</h1>
            <p class="text-slate-500">Kelola tagihan dan faktur siswa</p>
        </div>
        <div class="flex gap-2">
            <button onclick="document.getElementById('generateModal').showModal()" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Generate Massal
            </button>
            <a href="{{ route('tenant.school.finance.invoices.create') }}" class="px-4 py-2 bg-primary-600 text-white rounded-xl text-sm font-medium hover:bg-primary-700 transition-colors">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Tagihan
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Status</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Dibayar Sebagian</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Jatuh Tempo</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Tagihan</label>
                <select name="bill_type_id" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Jenis</option>
                    @foreach($billTypes as $type)
                        <option value="{{ $type->id }}" {{ request('bill_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Bulan</label>
                <input type="month" name="month" value="{{ request('month') }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Cari Siswa</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau NIS" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-slate-800 text-white rounded-xl text-sm font-medium hover:bg-slate-900 transition-colors">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Invoice Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No. Tagihan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Bulan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Sisa</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-900">{{ $invoice->invoice_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-slate-900">{{ $invoice->student->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-slate-500">{{ $invoice->student->nis ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $invoice->billType->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $invoice->month ? Carbon\Carbon::parse($invoice->month)->format('M Y') : '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-slate-900">Rp {{ number_format($invoice->final_amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-red-600">Rp {{ number_format($invoice->remaining_amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $invoice->due_date ? Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') : '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-lg
                                    @if($invoice->status === 'paid') bg-emerald-100 text-emerald-700
                                    @elseif($invoice->status === 'partial') bg-blue-100 text-blue-700
                                    @elseif($invoice->status === 'overdue') bg-red-100 text-red-700
                                    @else bg-amber-100 text-amber-700 @endif">
                                    @if($invoice->status === 'unpaid') Belum Bayar
                                    @elseif($invoice->status === 'partial') Sebagian
                                    @elseif($invoice->status === 'paid') Lunas
                                    @elseif($invoice->status === 'overdue') Jatuh Tempo
                                    @else {{ ucfirst($invoice->status) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('tenant.school.finance.invoices.show', $invoice->id) }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada tagihan ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($invoices->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Generate Massal Modal -->
<dialog id="generateModal" class="modal p-6 rounded-2xl">
    <div class="modal-box max-w-md">
        <h3 class="font-bold text-lg mb-4">Generate Tagihan Massal</h3>
        <form action="{{ route('tenant.school.finance.invoices.generate') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Tagihan</label>
                    <select name="bill_type_id" required class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm">
                        @foreach($billTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }} - Rp {{ number_format($type->default_amount, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Bulan</label>
                    <input type="month" name="month" required value="{{ now()->format('Y-m') }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Jatuh Tempo</label>
                    <input type="date" name="due_date" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm">
                </div>
            </div>
            <div class="modal-action">
                <button type="button" onclick="document.getElementById('generateModal').close()" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700">Generate</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
@endsection
