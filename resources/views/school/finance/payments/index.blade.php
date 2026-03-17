@extends('layouts.dashboard')

@section('title', 'Pembayaran')

@section('content')
<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Pembayaran</h1>
            <p class="text-slate-500">Kelola data pembayaran siswa</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('tenant.school.finance.spp.payment') }}" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                </svg>
                Pembayaran SPP
            </a>
            <a href="{{ route('tenant.school.finance.payments.create') }}" class="px-4 py-2 bg-primary-600 text-white rounded-xl text-sm font-medium hover:bg-primary-700 transition-colors">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Pembayaran Baru
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
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Metode</label>
                <select name="payment_method" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Metode</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                    <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="virtual_account" {{ request('payment_method') == 'virtual_account' ? 'selected' : '' }}>Virtual Account</option>
                    <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
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

    <!-- Payments Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No. Pembayaran</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tagihan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Metode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-900">{{ $payment->payment_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-slate-900">{{ $payment->student->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-slate-500">{{ $payment->student->nis ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $payment->invoice->billType->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                @if($payment->payment_method === 'cash') Tunai
                                @elseif($payment->payment_method === 'transfer') Transfer
                                @elseif($payment->payment_method === 'virtual_account') VA
                                @elseif($payment->payment_method === 'qris') QRIS
                                @else {{ ucfirst($payment->payment_method) }}
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-slate-900">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-lg
                                    @if($payment->status === 'confirmed') bg-emerald-100 text-emerald-700
                                    @elseif($payment->status === 'pending') bg-amber-100 text-amber-700
                                    @elseif($payment->status === 'rejected') bg-red-100 text-red-700
                                    @else bg-slate-100 text-slate-700 @endif">
                                    @if($payment->status === 'pending') Menunggu
                                    @elseif($payment->status === 'confirmed') Dikonfirmasi
                                    @elseif($payment->status === 'rejected') Ditolak
                                    @else {{ ucfirst($payment->status) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('tenant.school.finance.payments.show', $payment->id) }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada pembayaran ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($payments->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
