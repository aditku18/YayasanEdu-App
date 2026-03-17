@extends('layouts.dashboard')

@section('title', 'PPDB Dashboard')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Penerimaan Siswa Baru</h1>
            <p class="text-slate-500 mt-1">Status dan statistik pendaftaran siswa baru unit Anda.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ $schoolSlug ? route('tenant.school.ppdb.settings', ['school' => $schoolSlug]) : route('tenant.ppdb.settings') }}" class="px-5 py-2.5 bg-white border border-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Pengaturan Gelombang
            </a>
            <a href="{{ $schoolSlug ? route('tenant.school.ppdb.applicants', ['school' => $schoolSlug]) : route('tenant.ppdb.applicants') }}" class="px-5 py-2.5 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all flex items-center gap-2 shadow-lg shadow-slate-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Daftar Pendaftar
            </a>
            <a href="{{ $schoolSlug ? route('tenant.school.ppdb.applicants', ['school' => $schoolSlug, 'payment_status' => 'unpaid']) : route('tenant.ppdb.applicants', ['payment_status' => 'unpaid']) }}" class="px-5 py-2.5 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition-all flex items-center gap-2 shadow-lg shadow-amber-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6 1a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Tagihan Belum Lunas
            </a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 premium-shadow">
            <h3 class="text-slate-400 font-bold text-[10px] uppercase tracking-widest">Total</h3>
            <p class="text-2xl font-extrabold text-slate-900 mt-2">{{ number_format($totalApplicants) }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 premium-shadow">
            <h3 class="text-amber-500 font-bold text-[10px] uppercase tracking-widest">Menunggu</h3>
            <p class="text-2xl font-extrabold text-amber-500 mt-2">{{ number_format($unverified) }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 premium-shadow">
            <h3 class="text-blue-500 font-bold text-[10px] uppercase tracking-widest">Terverifikasi</h3>
            <p class="text-2xl font-extrabold text-blue-500 mt-2">{{ number_format($verified) }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 premium-shadow">
            <h3 class="text-green-500 font-bold text-[10px] uppercase tracking-widest">Diterima</h3>
            <p class="text-2xl font-extrabold text-green-500 mt-2">{{ number_format($accepted) }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 premium-shadow">
            <h3 class="text-emerald-500 font-bold text-[10px] uppercase tracking-widest">Terdaftar</h3>
            <p class="text-2xl font-extrabold text-emerald-500 mt-2">{{ number_format($enrolled) }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 premium-shadow">
            <h3 class="text-slate-400 font-bold text-[10px] uppercase tracking-widest">Kuota Tersisa</h3>
            @if($quotaRemaining !== null)
                <p class="text-2xl font-extrabold {{ $quotaRemaining > 0 ? 'text-primary-600' : 'text-rose-500' }} mt-2">{{ number_format($quotaRemaining) }}</p>
                <p class="text-[10px] text-slate-400 font-medium mt-1">dari {{ number_format($totalQuota) }}</p>
            @else
                <p class="text-2xl font-extrabold text-slate-300 mt-2">∞</p>
            @endif
        </div>
    </div>

    {{-- Quota Monitoring per Wave --}}
    @if($waves->count() > 0)
    <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50">
            <h3 class="font-bold text-slate-900">Monitoring Kuota per Gelombang</h3>
            <p class="text-xs text-slate-500 mt-1">Progres pengisian kuota setiap gelombang aktif.</p>
        </div>
        <div class="p-8 space-y-5">
            @foreach($waves as $wave)
            <div class="group">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-sm text-slate-800">{{ $wave->name }}</span>
                        @if($wave->major)
                            <span class="px-2 py-0.5 bg-amber-50 text-amber-600 rounded-lg text-[9px] uppercase tracking-widest font-black">{{ $wave->major->name }}</span>
                        @endif
                    </div>
                    <div class="text-right">
                        @if($wave->quota !== null)
                            <span class="font-bold text-sm text-slate-700">{{ $wave->applicants_count }} / {{ $wave->quota }}</span>
                            @php $remaining = max(0, $wave->quota - $wave->applicants_count); @endphp
                            <span class="text-[10px] text-slate-400 ml-2">Sisa {{ $remaining }}</span>
                        @else
                            <span class="font-bold text-sm text-slate-700">{{ $wave->applicants_count }}</span>
                            <span class="text-[10px] text-slate-400 ml-2">Tanpa Batas</span>
                        @endif
                    </div>
                </div>
                @if($wave->quota !== null)
                    @php
                        $pct = $wave->quota > 0 ? min(100, round(($wave->applicants_count / $wave->quota) * 100)) : 0;
                        $barColor = $pct >= 100 ? 'bg-rose-500' : ($pct >= 75 ? 'bg-amber-500' : 'bg-primary-500');
                    @endphp
                    <div class="w-full bg-slate-100 rounded-full h-2.5">
                        <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                    </div>
                @else
                    <div class="w-full bg-slate-100 rounded-full h-2.5">
                        <div class="bg-slate-300 h-2.5 rounded-full" style="width: 100%; opacity: 0.3;"></div>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white p-12 rounded-[3rem] border border-slate-100 premium-shadow text-center">
        <div class="w-16 h-16 bg-primary-50 text-primary-300 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-900">Belum Ada Gelombang Aktif</h3>
        <p class="text-slate-500 mt-2 max-w-md mx-auto">Buat gelombang pendaftaran terlebih dahulu melalui menu Pengaturan Gelombang untuk mulai menerima pendaftar.</p>
    </div>
    @endif
</div>
@endsection
