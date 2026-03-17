@extends('layouts.dashboard')

@section('title', 'Billing & Langganan')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Billing & Langganan</h1>
            <p class="text-slate-500 mt-1">Kelola metode pembayaran dan riwayat transaksi anda.</p>
        </div>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 premium-shadow space-y-6">
        <div class="flex items-center justify-between p-6 bg-primary-600 rounded-3xl text-white">
            <div>
                <p class="text-primary-100 text-sm font-semibold uppercase tracking-wider">Paket Saat Ini</p>
                <h2 class="text-3xl font-extrabold mt-1">Enterprise Plan</h2>
                <p class="text-primary-100 text-xs mt-2 italic">*Berlangganan hingga 12 Des 2026</p>
            </div>
            <div class="text-right">
                <span class="px-4 py-2 bg-white/20 backdrop-blur-md rounded-xl font-bold text-sm">AKTIF</span>
            </div>
        </div>

        <div class="p-8 border-2 border-dashed border-slate-100 rounded-3xl text-center">
            <p class="text-slate-400 font-medium italic">Belum ada riwayat invoice yang tersedia saat ini.</p>
        </div>
    </div>
</div>
@endsection
