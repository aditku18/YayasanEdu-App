@extends('layouts.tenant-platform')

@section('title', 'Dashboard Yayasan')

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
    .animate-slide-in-left {
        animation: slideInLeft 0.8s ease-out forwards;
        opacity: 0;
    }
    .animate-pulse-slow {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
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
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
    }
    .dashboard-item {
        transition: all 0.3s ease;
    }
    .dashboard-item:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto mb-12">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary-600 via-primary-500 to-indigo-500 p-8 md:p-12 text-white animate-fade-in-up shadow-2xl shadow-primary-500/20">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2 animate-pulse-slow" style="animation-delay: 1s;"></div>
            <div class="absolute top-0 left-1/4 w-32 h-32 bg-white/5 rounded-full animate-pulse-slow" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-1/4 right-1/4 w-24 h-24 bg-white/5 rounded-full animate-pulse-slow" style="animation-delay: 3s;"></div>

            <div class="relative z-10 text-center">
                <div class="flex items-center justify-center gap-4 mb-8">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl opacity-20 blur-xl"></div>
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center relative">
                            <svg class="w-8 h-8 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l2-2m3 7a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-white to-primary-100 bg-clip-text text-transparent">
                        Dashboard Yayasan
                    </h1>
                </div>
                <p class="text-primary-100 text-xl leading-relaxed mb-8 max-w-3xl mx-auto">
                    Selamat datang di platform manajemen yayasan terpadu
                </p>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                    <div class="group cursor-pointer">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center border border-white/20 hover:bg-white/20 transition-all duration-300">
                            <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <p class="text-3xl font-black text-white group-hover:text-emerald-100 transition-colors">{{ \App\Models\SchoolUnit::count() }}</p>
                            <p class="text-primary-100 text-sm font-medium">Unit Sekolah</p>
                        </div>
                    </div>
                    
                    <div class="group cursor-pointer">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center border border-white/20 hover:bg-white/20 transition-all duration-300">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <p class="text-3xl font-black text-white group-hover:text-blue-100 transition-colors">{{ \App\Models\Student::count() }}</p>
                            <p class="text-primary-100 text-sm font-medium">Total Siswa</p>
                        </div>
                    </div>
                    
                    <div class="group cursor-pointer">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center border border-white/20 hover:bg-white/20 transition-all duration-300">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <p class="text-3xl font-black text-white group-hover:text-purple-100 transition-colors">{{ \App\Models\Teacher::count() }}</p>
                            <p class="text-primary-100 text-sm font-medium">Total Guru</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto mb-6">
            <div class="glass-effect rounded-2xl p-6 border border-emerald-200 bg-emerald-50 animate-fade-in-up">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-emerald-800">Berhasil!</p>
                        <p class="text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto mb-6">
            <div class="glass-effect rounded-2xl p-6 border border-red-200 bg-red-50 animate-fade-in-up">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-red-800">Terjadi Kesalahan!</p>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Trial Banner -->
    @if(isset($trialDaysLeft) && isset($trialEndsAt))
        <div class="max-w-7xl mx-auto mb-8">
            <div class="glass-effect rounded-2xl p-6 {{ $trialDaysLeft <= 3 ? 'border-orange-200 bg-orange-50' : 'border-primary-200 bg-primary-50' }} animate-fade-in-up">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full {{ $trialDaysLeft <= 3 ? 'bg-orange-100' : 'bg-primary-100' }} flex items-center justify-center">
                            <svg class="w-6 h-6 {{ $trialDaysLeft <= 3 ? 'text-orange-600' : 'text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">
                                {{ $trialDaysLeft <= 3 ? '⚠️ Trial Akan Berakhir' : '🎯 Masa Trial Aktif' }}
                            </h3>
                            <p class="text-slate-600 mt-1">
                                {{ $trialDaysLeft }} hari tersisa • Berakhir pada {{ \Carbon\Carbon::parse($trialEndsAt)->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($trialDaysLeft <= 3)
                            <a href="mailto:admin@edusaas.com?subject=Upgrade Paket - {{ tenant('id') }}"
                               class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-xl shadow-lg transition-all duration-200">
                                Upgrade Sekarang
                            </a>
                        @else
                            <a href="mailto:admin@edusaas.com?subject=Informasi Paket - {{ tenant('id') }}"
                               class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl shadow-lg transition-all duration-200">
                                Lihat Paket
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="max-w-7xl mx-auto mb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Schools -->
            <div class="dashboard-item glass-effect rounded-2xl p-6 hover-lift animate-fade-in-up animate-delay-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">+12%</span>
                </div>
                <div>
                    <p class="text-3xl font-bold text-slate-900">{{ \App\Models\SchoolUnit::count() }}</p>
                    <p class="text-sm text-slate-500 mt-1">Unit Sekolah</p>
                </div>
            </div>

            <!-- Total Students -->
            <div class="dashboard-item glass-effect rounded-2xl p-6 hover-lift animate-fade-in-up animate-delay-2">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">+8%</span>
                </div>
                <div>
                    <p class="text-3xl font-bold text-slate-900">{{ \App\Models\Student::count() }}</p>
                    <p class="text-sm text-slate-500 mt-1">Total Siswa Yayasan</p>
                </div>
            </div>

            <!-- Total Teachers -->
            <div class="dashboard-item glass-effect rounded-2xl p-6 hover-lift animate-fade-in-up animate-delay-3">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">+15%</span>
                </div>
                <div>
                    <p class="text-3xl font-bold text-slate-900">{{ \App\Models\Teacher::count() }}</p>
                    <p class="text-sm text-slate-500 mt-1">Total Guru Yayasan</p>
                </div>
            </div>

            <!-- System Status -->
            <div class="group cursor-pointer">
                <div class="glass-effect rounded-2xl p-6 hover-lift animate-fade-in-up animate-delay-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-emerald-500 rounded-xl opacity-20 blur-lg"></div>
                            <svg class="w-8 h-8 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-9-9m0 0l9 9m-9-9v-2a2 2 0 00-2 2H4a2 2 0 00-2 2v2a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">Aktif</span>
                            <p class="text-3xl font-bold text-white">100%</p>
                            <p class="text-primary-100 text-sm">Status Sistem</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="max-w-7xl mx-auto">
        <div class="glass-effect rounded-2xl p-8 animate-fade-in-up animate-delay-2">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Aksi Cepat</h2>
                    <p class="text-slate-600 mt-1">Kelola data yayasan dengan mudah</p>
                </div>
                <button class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl shadow-lg transition-all duration-200 flex items-center gap-2">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Manage Schools -->
                <div class="group">
                    <a href="{{ route('tenant.schools.index') }}" class="block p-6 bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl border border-primary-200 hover:border-primary-300 hover:shadow-lg transition-all duration-200">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-slate-900 group-hover:text-primary-900">Kelola Sekolah</h3>
                                <p class="text-sm text-slate-600 mt-1">Tambah dan atur unit sekolah</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-primary-600 font-medium">Kelola unit sekolah</span>
                            <svg class="w-4 h-4 text-primary-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                </div>

                <!-- View Reports -->
                <div class="group">
                    <a href="#" class="block p-6 bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl border border-amber-200 hover:border-amber-300 hover:shadow-lg transition-all duration-200">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-slate-900 group-hover:text-amber-900">Laporan Yayasan</h3>
                                <p class="text-sm text-slate-600 mt-1">Lihat laporan keseluruhan</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-amber-600 font-medium">Lihat laporan</span>
                            <svg class="w-4 h-4 text-amber-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                </div>

                <!-- System Settings -->
                <div class="group">
                    <a href="#" class="block p-6 bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl border border-slate-200 hover:border-slate-300 hover:shadow-lg transition-all duration-200">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-slate-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c-.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-1.543.94-3.31-.826-2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-slate-900 group-hover:text-slate-900">Pengaturan Sistem</h3>
                                <p class="text-sm text-slate-600 mt-1">Konfigurasi sistem yayasan</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-600 font-medium">Kelola pengaturan</span>
                            <svg class="w-4 h-4 text-slate-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
