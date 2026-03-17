@extends('layouts.tenant-platform')

@section('title', 'Langganan Aktif')

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
    .animate-slide-in-right {
        animation: slideInRight 0.8s ease-out forwards;
        opacity: 0;
    }
    .animate-pulse-slow {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    .animate-delay-1 { animation-delay: 0.1s; }
    .animate-delay-2 { animation-delay: 0.2s; }
    .animate-delay-3 { animation-delay: 0.3s; }
    .glass-effect {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary-600 via-primary-500 to-indigo-500 p-8 md:p-10 text-white animate-fade-in-up shadow-2xl shadow-primary-500/20">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2 animate-pulse-slow" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 right-1/4 w-32 h-32 bg-indigo-300/20 rounded-full blur-xl animate-pulse-slow" style="animation-delay: 2s;"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div class="max-w-xl">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold">Paket Berlangganan Aktif</h1>
                    </div>
                    <p class="text-primary-100 text-base leading-relaxed mb-6">
                        Kelola dan lihat rincian paket berlangganan yayasan Anda dengan mudah dan transparan.
                    </p>
                    <div class="flex flex-wrap items-center gap-6">
                        <div class="flex flex-col">
                            <span class="text-2xl font-bold">{{ $subscription ? $subscription->plan->name ?? 'Paket EduSaaS' : 'Tidak Aktif' }}</span>
                            <span class="text-xs font-semibold text-primary-200 mt-1 uppercase tracking-wider">Paket Saat Ini</span>
                        </div>
                        @if($subscription)
                        <div class="h-10 w-px bg-primary-400"></div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-bold">{{ $subscription->plan->max_schools ?? 'Tidak Terbatas' }}</span>
                            <span class="text-xs font-semibold text-primary-200 mt-1 uppercase tracking-wider">Sekolah Didukung</span>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="shrink-0">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center animate-float">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($subscription)
    <!-- Main Content Grid -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Plan Details -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Current Plan Card -->
            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up animate-delay-1">
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">{{ $subscription->plan->name ?? 'Paket EduSaaS' }}</h2>
                                <p class="text-emerald-100 text-sm">Paket Aktif Anda</p>
                            </div>
                        </div>
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center animate-float">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-br from-slate-50 to-slate-100 p-6 rounded-2xl border border-slate-200">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Masa Berlaku</p>
                                    <p class="text-slate-900 font-bold text-lg">{{ $subscription->starts_at ? $subscription->starts_at->format('d M Y') : '-' }} - {{ $subscription->ends_at ? $subscription->ends_at->format('d M Y') : 'Selamanya' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-slate-50 to-slate-100 p-6 rounded-2xl border border-slate-200">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status Perpanjangan</p>
                                    <p class="text-slate-900 font-bold text-lg">{{ $subscription->auto_renew ? 'Otomatis' : 'Manual' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-2 bg-gradient-to-br from-primary-50 to-primary-100 p-6 rounded-2xl border border-primary-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-primary-600 uppercase tracking-wider">Total Sekolah Didukung</p>
                                    <p class="text-slate-900 font-bold text-lg">{{ $subscription->plan->max_schools ?? 'Tidak Terbatas' }} Sekolah</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-4 mt-8">
                        <a href="{{ route('tenant.plan.upgrade') }}" class="group px-8 py-3.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center gap-3">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            Upgrade Paket
                        </a>
                        <a href="{{ route('tenant.invoice.index') }}" class="group px-8 py-3.5 bg-white text-slate-700 font-bold rounded-xl border-2 border-slate-200 hover:bg-slate-50 transition-all duration-200 flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            Lihat Tagihan
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Features Section -->
            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up animate-delay-2">
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 p-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Fitur Paket Anda</h3>
                            <p class="text-slate-300 text-sm">Semua fitur yang tersedia dalam paket Anda</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if(isset($subscription->plan) && is_array($subscription->plan->features))
                            @foreach($subscription->plan->features as $feature)
                            <div class="flex items-start gap-3 p-4 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl border border-slate-200 hover-lift">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-slate-700 text-sm font-medium leading-relaxed">{{ $feature }}</span>
                            </div>
                            @endforeach
                        @else
                            <div class="flex items-start gap-3 p-4 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl border border-slate-200 hover-lift">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-slate-700 text-sm font-medium">Manajemen Siswa & Guru</span>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl border border-slate-200 hover-lift">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-slate-700 text-sm font-medium">Manajemen Keuangan Dasar</span>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl border border-slate-200 hover-lift">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-slate-700 text-sm font-medium">Portal Akademik</span>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl border border-slate-200 hover-lift">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-slate-700 text-sm font-medium">Laporan & Analitik</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Status & Quick Actions -->
        <div class="space-y-8">
            <!-- Subscription Status Card -->
            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-slide-in-right animate-delay-1">
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Status Aktif</h3>
                            <p class="text-emerald-100 text-sm">Langganan berjalan normal</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-500">Tanggal Mulai</span>
                            <span class="text-sm font-bold text-slate-900">{{ $subscription->starts_at ? $subscription->starts_at->format('d M Y') : '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-500">Tanggal Berakhir</span>
                            <span class="text-sm font-bold text-slate-900">{{ $subscription->ends_at ? $subscription->ends_at->format('d M Y') : 'Selamanya' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-500">Perpanjangan</span>
                            <span class="text-sm font-bold text-slate-900">{{ $subscription->auto_renew ? 'Otomatis' : 'Manual' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-slide-in-right animate-delay-2">
                <div class="bg-gradient-to-br from-primary-500 to-primary-600 p-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Aksi Cepat</h3>
                            <p class="text-primary-100 text-sm">Kelola langganan Anda</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 space-y-3">
                    <a href="{{ route('tenant.plan.upgrade') }}" class="w-full flex items-center gap-3 p-4 bg-gradient-to-r from-primary-50 to-primary-100 text-primary-700 rounded-xl hover:from-primary-100 hover:to-primary-200 transition-all duration-200 group">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span class="font-medium">Upgrade Paket</span>
                        <svg class="w-4 h-4 text-primary-400 ml-auto transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="{{ route('tenant.invoice.index') }}" class="w-full flex items-center gap-3 p-4 bg-gradient-to-r from-slate-50 to-slate-100 text-slate-700 rounded-xl hover:from-slate-100 hover:to-slate-200 transition-all duration-200 group">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span class="font-medium">Lihat Tagihan</span>
                        <svg class="w-4 h-4 text-slate-400 ml-auto transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="{{ route('tenant.payment.history') }}" class="w-full flex items-center gap-3 p-4 bg-gradient-to-r from-amber-50 to-amber-100 text-amber-700 rounded-xl hover:from-amber-100 hover:to-amber-200 transition-all duration-200 group">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">Riwayat Pembayaran</span>
                        <svg class="w-4 h-4 text-amber-400 ml-auto transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- No Active Subscription -->
    <div class="max-w-4xl mx-auto">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 p-8">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center animate-float">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Tidak Ada Paket Aktif</h2>
                        <p class="text-amber-100 text-sm mt-1">Yayasan Anda belum memiliki langganan aktif</p>
                    </div>
                </div>
            </div>
            
            <div class="p-12 text-center">
                <div class="max-w-md mx-auto space-y-6">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-amber-50 to-amber-100 rounded-3xl flex items-center justify-center">
                        <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-slate-900">Waktunya Berlangganan!</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Yayasan Anda saat ini tidak memiliki paket berlangganan aktif. Pilih paket yang sesuai dengan kebutuhan operasional yayasan dan sekolah Anda untuk mulai menikmati semua fitur lengkap EduSaaS.
                        </p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-4 justify-center pt-4">
                        <a href="{{ route('tenant.plan.upgrade') }}" class="group px-8 py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-3">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/>
                            </svg>
                            Pilih Paket Berlangganan
                        </a>
                        <a href="#" class="group px-8 py-4 bg-white text-slate-700 font-bold rounded-xl border-2 border-slate-200 hover:bg-slate-50 transition-all duration-200 flex items-center justify-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.657-1.79 4-4 0-1.657-1.79-4-4-4-1.657 0-4 1.79-4 4 0-2.21 1.343-3.772 2-3.772-2 0-4 1.343-4 3 0 1.657 1.79 4 4 0 2.21-1.343 3.772-2 3.772 2 0 4-1.343 4-4 0-1.657-1.79-4-4z"/>
                            </svg>
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
