@extends('layouts.tenant-platform')

@section('title', 'Detail Invoice')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    {{-- Header Section --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Invoice</h1>
            <p class="text-slate-500 mt-1">Rincian tagihan layanan EduSaaS</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('tenant.invoice.index') }}" class="px-6 py-3 bg-white text-slate-700 font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- Main Invoice Card --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
        {{-- Invoice Header --}}
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-8 lg:p-10">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-slate-400 text-sm font-medium uppercase tracking-wider">Nomor Invoice</p>
                        <h2 class="text-2xl lg:text-3xl font-black text-white tracking-tight">#{{ $invoice->invoice_number }}</h2>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-3">
                    @if($invoice->status === 'paid' || $invoice->status === 'completed')
                    <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-emerald-500/20 text-emerald-400 text-sm font-bold border border-emerald-500/30">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        LUNAS
                    </span>
                    @elseif($invoice->status === 'pending')
                    <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-amber-500/20 text-amber-400 text-sm font-bold border border-amber-500/30">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        MENUNGGU PEMBAYARAN
                    </span>
                    @else
                    <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-slate-500/20 text-slate-400 text-sm font-bold border border-slate-500/30">
                        {{ strtoupper($invoice->status) }}
                    </span>
                    @endif
                    <p class="text-slate-400 text-sm">Issued: {{ $invoice->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Invoice Body --}}
        <div class="p-8 lg:p-10">
            {{-- From & To Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Dari</p>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">EduSaaS Platform</p>
                            <p class="text-sm text-slate-500">support@edusaas.com</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600">Gedung Teknologi, Jakarta Selatan<br>Indonesia</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Tagihan Kepada</p>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">{{ $invoice->foundation->name }}</p>
                            <p class="text-sm text-slate-500">{{ $invoice->foundation->email }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600">{{ $invoice->foundation->subdomain }}.edusaaS.com</p>
                </div>
            </div>

            {{-- Invoice Meta --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
                <div class="bg-slate-50 rounded-2xl p-4">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Invoice</p>
                    <p class="font-bold text-slate-900">{{ $invoice->created_at->format('d M Y') }}</p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-4">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Jatuh Tempo</p>
                    <p class="font-bold {{ $invoice->due_date && $invoice->due_date->isPast() && $invoice->status !== 'paid' ? 'text-red-600' : 'text-slate-900' }}">{{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '-' }}</p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-4">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Periode</p>
                    <p class="font-bold text-slate-900">{{ $invoice->period_start ? $invoice->period_start->format('M Y') : now()->format('M Y') }}</p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-4">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Metode Bayar</p>
                    <p class="font-bold text-slate-900">{{ $invoice->billing_cycle == 'yearly' ? 'Tahunan' : 'Bulanan' }}</p>
                </div>
            </div>

            {{-- Invoice Items Table --}}
            <div class="mb-10">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Rincian Pembayaran</h3>
                <div class="bg-slate-50 rounded-3xl overflow-hidden border border-slate-100">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-100 border-b border-slate-200">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Deskripsi</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-lg">
                                                @if($invoice->subscription && $invoice->subscription->plan)
                                                    {{ $invoice->subscription->plan->name }}
                                                @else
                                                    Langganan Platform
                                                @endif
                                            </p>
                                            <p class="text-sm text-slate-500">
                                                @if($invoice->subscription && $invoice->subscription->plan)
                                                    {{ $invoice->subscription->plan->description }}
                                                @else
                                                    Akses penuh ke semua fitur
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <p class="font-bold text-slate-900 text-lg">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-primary/5">
                            <tr class="border-t-2 border-primary/20">
                                <td class="px-6 py-5 text-right font-bold text-slate-700">TOTAL</td>
                                <td class="px-6 py-5 text-right text-2xl font-black text-primary">
                                    Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Payment Info --}}
            @if($invoice->status === 'paid' && $invoice->paid_at)
            <div class="mb-10 p-6 bg-emerald-50 rounded-3xl border border-emerald-100">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-emerald-800 text-lg">Pembayaran Diterima</p>
                        <p class="text-emerald-600">Lunas pada {{ $invoice->paid_at->format('d M Y') }} pukul {{ $invoice->paid_at->format('H:i') }}</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-6 pt-8 border-t border-slate-200">
                <div>
                    <p class="text-sm text-slate-500 mb-2">Punya pertanyaan tentang invoice ini?</p>
                    <a href="#" class="text-primary-600 font-bold hover:underline">Hubungi Customer Support</a>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch gap-4 w-full sm:w-auto">
                    <a href="{{ route('tenant.invoice.index') }}" class="px-8 py-4 bg-slate-100 text-slate-700 font-bold rounded-2xl hover:bg-slate-200 transition-all flex items-center justify-center gap-2 text-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Lihat Daftar Invoice
                    </a>
                    @if($invoice->status !== 'paid' && $invoice->status !== 'completed')
                    <a href="{{ route('tenant.invoice.pay', ['invoice' => $invoice->id, 'token' => $invoice->payment_token]) }}" class="px-10 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-2xl shadow-lg shadow-green-500/30 hover:shadow-xl hover:shadow-green-500/40 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2 text-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        BAYAR SEKARANG
                    </a>
                    @else
                    <button class="px-8 py-4 bg-emerald-500 text-white font-bold rounded-2xl hover:bg-emerald-600 shadow-lg shadow-emerald500/30 transition-all flex items-center justify-center gap-2 text-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download PDF Invoice
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
