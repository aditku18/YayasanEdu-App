@extends('layouts.dashboard')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Laporan Keuangan</h1>
            <p class="text-slate-500">Ringkasan keuangan periode tertentu</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('tenant.school.finance.reports.print', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}" target="_blank" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 24">
                    0 24 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print
            </a>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <button type="submit" class="w-full px-4 py-2 bg-slate-800 text-white rounded-xl text-sm font-medium hover:bg-slate-900 transition-colors">
                    Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-slate-500">Total Pendapatan</p>
            <h3 class="text-2xl font-bold text-emerald-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-slate-500">Total Pengeluaran</p>
            <h3 class="text-2xl font-bold text-orange-600">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-slate-500">Saldo Bersih</p>
            <h3 class="text-2xl font-bold {{ $netBalance >= 0 ? 'text-blue-600' : 'text-red-600' }}">Rp {{ number_format($netBalance, 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Income Breakdown -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-semibold text-slate-900 mb-4">Rincian Pendapatan</h3>
            @if($income->count() > 0)
                <div class="space-y-3">
                    @foreach($income as $category => $amount)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <span class="text-sm text-slate-700">{{ $category }}</span>
                            <span class="font-semibold text-emerald-600">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-slate-400 py-8">Tidak ada pendapatan</p>
            @endif
        </div>

        <!-- Expense Breakdown -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-semibold text-slate-900 mb-4">Rincian Pengeluaran</h3>
            @if($expenses->count() > 0)
                <div class="space-y-3">
                    @foreach($expenses as $category => $amount)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <span class="text-sm text-slate-700">{{ $category }}</span>
                            <span class="font-semibold text-orange-600">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-slate-400 py-8">Tidak ada pengeluaran</p>
            @endif
        </div>
    </div>

    <!-- Daily Transactions -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h3 class="font-semibold text-slate-900 mb-4">Transaksi Harian</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-emerald-600 uppercase">Kas Masuk</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-orange-600 uppercase">Kas Keluar</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $runningBalance = 0; @endphp
                    @forelse($dailyTransactions as $date => $data)
                        @php $runningBalance += $data['cash_in'] - $data['cash_out']; @endphp
                        <tr>
                            <td class="px-4 py-3 text-sm text-slate-900">{{ Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm text-emerald-600 text-right">Rp {{ number_format($data['cash_in'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-orange-600 text-right">Rp {{ number_format($data['cash_out'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-right {{ $runningBalance >= 0 ? 'text-slate-900' : 'text-red-600' }}">Rp {{ number_format($runningBalance, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-400">Tidak ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
