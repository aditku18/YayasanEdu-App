@extends('layouts.dashboard')

@section('title', 'Keuangan - Dashboard')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Dashboard Keuangan</h1>
            <p class="text-slate-500">Ringkasan kondisi keuangan sekolah</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('tenant.school.finance.reports.index', ['school' => $schoolSlug]) }}" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan
            </a>
            <a href="{{ route('tenant.school.finance.invoices.create', ['school' => $schoolSlug]) }}" class="px-4 py-2 bg-primary-600 text-white rounded-xl text-sm font-medium hover:bg-primary-700 transition-colors">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Tagihan
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Tagihan -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">Piutang</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-900">Rp {{ number_format($stats['total_receivable'], 0, ',', '.') }}</h3>
            <p class="text-sm text-slate-500 mt-1">Total Tagihan Belum Lunas</p>
        </div>

        <!-- Pendapatan Bulan Ini -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Masuk</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-900">Rp {{ number_format($stats['total_paid_this_month'], 0, ',', '.') }}</h3>
            <p class="text-sm text-slate-500 mt-1">Pendapatan Bulan Ini</p>
        </div>

        <!-- Pengeluaran Bulan Ini -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-orange-600 bg-orange-50 px-2 py-1 rounded-lg">Keluar</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-900">Rp {{ number_format($stats['total_expenses_this_month'], 0, ',', '.') }}</h3>
            <p class="text-sm text-slate-500 mt-1">Pengeluaran Bulan Ini</p>
        </div>

        <!-- Tagihan Jatuh Tempo -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">Jatuh Tempo</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-900">{{ $stats['overdue_invoices'] }}</h3>
            <p class="text-sm text-slate-500 mt-1">Tagihan Jatuh Tempo</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('tenant.school.finance.spp.payment', ['school' => $schoolSlug]) }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:border-primary-200 transition-colors group">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-3 group-hover:bg-blue-100 transition-colors">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                </svg>
            </div>
            <h4 class="font-semibold text-slate-900">Pembayaran SPP</h4>
            <p class="text-xs text-slate-500 mt-1">Catat pembayaran SPP</p>
        </a>

        <a href="{{ route('tenant.school.finance.invoices.index', ['school' => $schoolSlug]) }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:border-primary-200 transition-colors group">
            <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center mb-3 group-hover:bg-purple-100 transition-colors">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h4 class="font-semibold text-slate-900">Tagihan</h4>
            <p class="text-xs text-slate-500 mt-1">Kelola tagihan siswa</p>
        </a>

        <a href="{{ route('tenant.school.finance.expenses.create', ['school' => $schoolSlug]) }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:border-primary-200 transition-colors group">
            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center mb-3 group-hover:bg-orange-100 transition-colors">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h4 class="font-semibold text-slate-900">Pengeluaran</h4>
            <p class="text-xs text-slate-500 mt-1">Catat pengeluaran</p>
        </a>

        <a href="{{ route('tenant.school.finance.receivables.index', ['school' => $schoolSlug]) }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:border-primary-200 transition-colors group">
            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center mb-3 group-hover:bg-red-100 transition-colors">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h4 class="font-semibold text-slate-900">Piutang</h4>
            <p class="text-xs text-slate-500 mt-1">Lihat piutang siswa</p>
        </a>
    </div>

    <!-- Charts and Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Chart - Monthly Trend -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Trend Pendapatan & Pengeluaran</h3>
            <div class="h-64 flex items-end gap-2">
                @foreach($monthlyIncome as $i => $income)
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <div class="w-full bg-emerald-100 rounded-t-lg relative" style="height: {{ max(10, ($income / max(1, max($monthlyIncome))) * 200) }}px">
                            <div class="absolute bottom-0 w-full bg-emerald-500 rounded-t-lg transition-all" style="height: 100%"></div>
                        </div>
                        <div class="w-full bg-orange-100 rounded-t-lg relative" style="height: {{ max(10, ($monthlyExpense[$i] / max(1, max($monthlyExpense + [0]))) * 200) }}px">
                            <div class="absolute bottom-0 w-full bg-orange-500 rounded-t-lg transition-all" style="height: 100%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-center gap-6 mt-4">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                    <span class="text-sm text-slate-500">Pemasukan</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                    <span class="text-sm text-slate-500">Pengeluaran</span>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900">Pembayaran Terbaru</h3>
                <a href="{{ route('tenant.school.finance.payments.index', ['school' => $schoolSlug]) }}" class="text-sm text-primary-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                @forelse($recentPayments as $payment)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900">{{ $payment->student->name ?? 'N/A' }}</p>
                                <p class="text-xs text-slate-500">{{ $payment->invoice->billType->name ?? 'Tagihan' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-slate-900">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
                            <p class="text-xs text-slate-500">{{ $payment->payment_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-slate-400 py-8">Belum ada pembayaran</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Expenses -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-900">Pengeluaran Terbaru</h3>
            <a href="{{ route('tenant.school.finance.expenses.index', ['school' => $schoolSlug]) }}" class="text-sm text-primary-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="pb-3">Nomor</th>
                        <th class="pb-3">Kategori</th>
                        <th class="pb-3">Deskripsi</th>
                        <th class="pb-3">Tanggal</th>
                        <th class="pb-3">Jumlah</th>
                        <th class="pb-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentExpenses as $expense)
                        <tr>
                            <td class="py-3 text-sm font-medium text-slate-900">{{ $expense->expense_number }}</td>
                            <td class="py-3 text-sm text-slate-600">{{ $expense->expenseCategory->name ?? '-' }}</td>
                            <td class="py-3 text-sm text-slate-600">{{ Str::limit($expense->description, 30) }}</td>
                            <td class="py-3 text-sm text-slate-600">{{ $expense->expense_date->format('d/m/Y') }}</td>
                            <td class="py-3 text-sm font-semibold text-slate-900">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                            <td class="py-3">
                                <span class="px-2 py-1 text-xs font-medium rounded-lg
                                    @if($expense->status === 'paid') bg-emerald-100 text-emerald-700
                                    @elseif($expense->status === 'approved') bg-blue-100 text-blue-700
                                    @elseif($expense->status === 'pending') bg-amber-100 text-amber-700
                                    @else bg-slate-100 text-slate-700 @endif">
                                    {{ ucfirst($expense->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada pengeluaran</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
