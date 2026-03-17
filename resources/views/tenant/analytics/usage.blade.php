@extends('layouts.tenant-platform')

@section('title', 'Statistik Penggunaan')

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
    .chart-bar {
        transition: all 0.3s ease;
    }
    .chart-bar:hover {
        opacity: 0.8;
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
            
            <div class="relative z-10 text-center">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">Statistik Penggunaan</h1>
                </div>
                <p class="text-primary-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Pantau dan analisis penggunaan sistem EduSaaS untuk optimasi performa dan pengambilan keputusan
                </p>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">1,247</p>
                        <p class="text-primary-100 text-sm">Total Pengguna</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">8,542</p>
                        <p class="text-primary-100 text-sm">Total Sesi</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">94.2%</p>
                        <p class="text-primary-100 text-sm">Tingkat Aktif</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">3.2h</p>
                        <p class="text-primary-100 text-sm">Rata-rata Sesi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Usage Overview Cards -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="glass-effect rounded-2xl p-6 hover-lift animate-fade-in-up animate-delay-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm text-blue-600 font-medium">+12%</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">856</h3>
                <p class="text-slate-600 text-sm mt-1">Pengguna Hari Ini</p>
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                        <span>Target</span>
                        <span>107%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-2xl p-6 hover-lift animate-fade-in-up animate-delay-2">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <span class="text-sm text-emerald-600 font-medium">+8%</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">234</h3>
                <p class="text-slate-600 text-sm mt-1">Notifikasi Aktif</p>
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                        <span>Rate</span>
                        <span>78%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: 78%"></div>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-2xl p-6 hover-lift animate-fade-in-up animate-delay-3">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="text-sm text-amber-600 font-medium">+15%</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">1,847</h3>
                <p class="text-slate-600 text-sm mt-1">Aksi/Hari</p>
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                        <span>Efficiency</span>
                        <span>92%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-amber-500 h-2 rounded-full" style="width: 92%"></div>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-2xl p-6 hover-lift animate-fade-in-up animate-delay-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm text-purple-600 font-medium">2.4h</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">2.4h</h3>
                <p class="text-slate-600 text-sm mt-1">Rata-rata Online</p>
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                        <span>Engagement</span>
                        <span>High</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: 88%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Usage Charts Section -->
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Daily Usage Chart -->
            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
                <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">Penggunaan Harian</h2>
                            <p class="text-slate-600 text-sm">7 hari terakhir</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 w-12">Sen</span>
                            <div class="flex-1 mx-4 bg-slate-200 rounded-full h-6 relative overflow-hidden">
                                <div class="chart-bar bg-gradient-to-r from-blue-500 to-blue-600 h-full rounded-full" style="width: 75%"></div>
                            </div>
                            <span class="text-sm font-medium text-slate-900 w-16 text-right">856</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 w-12">Sel</span>
                            <div class="flex-1 mx-4 bg-slate-200 rounded-full h-6 relative overflow-hidden">
                                <div class="chart-bar bg-gradient-to-r from-blue-500 to-blue-600 h-full rounded-full" style="width: 82%"></div>
                            </div>
                            <span class="text-sm font-medium text-slate-900 w-16 text-right">934</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 w-12">Rab</span>
                            <div class="flex-1 mx-4 bg-slate-200 rounded-full h-6 relative overflow-hidden">
                                <div class="chart-bar bg-gradient-to-r from-blue-500 to-blue-600 h-full rounded-full" style="width: 68%"></div>
                            </div>
                            <span class="text-sm font-medium text-slate-900 w-16 text-right">778</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 w-12">Kam</span>
                            <div class="flex-1 mx-4 bg-slate-200 rounded-full h-6 relative overflow-hidden">
                                <div class="chart-bar bg-gradient-to-r from-blue-500 to-blue-600 h-full rounded-full" style="width: 90%"></div>
                            </div>
                            <span class="text-sm font-medium text-slate-900 w-16 text-right">1,028</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 w-12">Jum</span>
                            <div class="flex-1 mx-4 bg-slate-200 rounded-full h-6 relative overflow-hidden">
                                <div class="chart-bar bg-gradient-to-r from-blue-500 to-blue-600 h-full rounded-full" style="width: 85%"></div>
                            </div>
                            <span class="text-sm font-medium text-slate-900 w-16 text-right">972</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 w-12">Sab</span>
                            <div class="flex-1 mx-4 bg-slate-200 rounded-full h-6 relative overflow-hidden">
                                <div class="chart-bar bg-gradient-to-r from-amber-500 to-amber-600 h-full rounded-full" style="width: 45%"></div>
                            </div>
                            <span class="text-sm font-medium text-slate-900 w-16 text-right">514</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 w-12">Min</span>
                            <div class="flex-1 mx-4 bg-slate-200 rounded-full h-6 relative overflow-hidden">
                                <div class="chart-bar bg-gradient-to-r from-amber-500 to-amber-600 h-full rounded-full" style="width: 32%"></div>
                            </div>
                            <span class="text-sm font-medium text-slate-900 w-16 text-right">365</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature Usage Chart -->
            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
                <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">Penggunaan Fitur</h2>
                            <p class="text-slate-600 text-sm">Berdasarkan aksi pengguna</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-900">Manajemen Siswa</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-24 bg-slate-200 rounded-full h-4 relative overflow-hidden">
                                    <div class="chart-bar bg-gradient-to-r from-blue-500 to-blue-600 h-full rounded-full" style="width: 92%"></div>
                                </div>
                                <span class="text-sm font-medium text-slate-900 w-12 text-right">92%</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-900">Keuangan</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-24 bg-slate-200 rounded-full h-4 relative overflow-hidden">
                                    <div class="chart-bar bg-gradient-to-r from-emerald-500 to-emerald-600 h-full rounded-full" style="width: 78%"></div>
                                </div>
                                <span class="text-sm font-medium text-slate-900 w-12 text-right">78%</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332-.477-4.5-1.253"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-900">Akademik</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-24 bg-slate-200 rounded-full h-4 relative overflow-hidden">
                                    <div class="chart-bar bg-gradient-to-r from-amber-500 to-amber-600 h-full rounded-full" style="width: 65%"></div>
                                </div>
                                <span class="text-sm font-medium text-slate-900 w-12 text-right">65%</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-900">Laporan</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-24 bg-slate-200 rounded-full h-4 relative overflow-hidden">
                                    <div class="chart-bar bg-gradient-to-r from-purple-500 to-purple-600 h-full rounded-full" style="width: 58%"></div>
                                </div>
                                <span class="text-sm font-medium text-slate-900 w-12 text-right">58%</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 002.573-1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 00-1.065 2.572z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-900">Pengaturan</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-24 bg-slate-200 rounded-full h-4 relative overflow-hidden">
                                    <div class="chart-bar bg-gradient-to-r from-indigo-500 to-indigo-600 h-full rounded-full" style="width: 42%"></div>
                                </div>
                                <span class="text-sm font-medium text-slate-900 w-12 text-right">42%</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-900">Integrasi</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-24 bg-slate-200 rounded-full h-4 relative overflow-hidden">
                                    <div class="chart-bar bg-gradient-to-r from-red-500 to-red-600 h-full rounded-full" style="width: 28%"></div>
                                </div>
                                <span class="text-sm font-medium text-slate-900 w-12 text-right">28%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Usage Table -->
    <div class="max-w-7xl mx-auto mt-8">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">Aktivitas Pengguna Terbaru</h2>
                            <p class="text-slate-600 text-sm">Log aktivitas sistem real-time</p>
                        </div>
                    </div>
                    <button class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filter
                        </span>
                    </button>
                </div>
            </div>
            
            <div class="p-8">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Pengguna</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Aktivitas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Modul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <tr class="hover-lift animate-slide-in-left animate-delay-1">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-slate-900">Ahmad Rizki</div>
                                            <div class="text-sm text-slate-500">Admin Sekolah</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">Menambah data siswa baru</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Manajemen Siswa</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    2 menit yang lalu
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full">Sukses</span>
                                </td>
                            </tr>

                            <tr class="hover-lift animate-slide-in-left animate-delay-2">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-slate-900">Siti Nurhaliza</div>
                                            <div class="text-sm text-slate-500">Admin Yayasan</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">Membuat laporan keuangan</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full">Keuangan</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    5 menit yang lalu
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full">Sukses</span>
                                </td>
                            </tr>

                            <tr class="hover-lift animate-slide-in-left animate-delay-3">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-slate-900">Budi Santoso</div>
                                            <div class="text-sm text-slate-500">Guru</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">Input nilai siswa</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">Akademik</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    15 menit yang lalu
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full">Sukses</span>
                                </td>
                            </tr>

                            <tr class="hover-lift animate-slide-in-left animate-delay-1">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-slate-900">Dewi Lestari</div>
                                            <div class="text-sm text-slate-500">Admin Sekolah</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">Mengatur integrasi WhatsApp</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">Integrasi</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    1 jam yang lalu
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">Proses</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
