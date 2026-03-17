@extends('layouts.tenant-platform')

@section('title', 'Installed Plugins')

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
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .status-active {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    .status-inactive {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">Installed Plugins</h1>
                </div>
                <p class="text-primary-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Kelola semua plugin yang telah terpasang di sistem yayasan Anda
                </p>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $installedPlugins->count() }}</p>
                        <p class="text-primary-100 text-sm">Total Terpasang</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $installedPlugins->where('status', 'active')->count() }}</p>
                        <p class="text-primary-100 text-sm">Plugin Aktif</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $availablePlugins->count() }}</p>
                        <p class="text-primary-100 text-sm">Tersedia</p>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center justify-center gap-4">
                    <a href="{{ route('tenant.plugin.active') }}" class="group px-8 py-4 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white font-bold rounded-2xl transition-all duration-300 border border-white/30">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Active Only
                        </span>
                    </a>
                    <a href="{{ route('tenant.marketplace.index') }}" class="group px-8 py-4 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white font-bold rounded-2xl transition-all duration-300 border border-white/30">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Browse Marketplace
                        </span>
                    </a>
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
                        <p class="font-bold text-emerald-800">Success!</p>
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
                        <p class="font-bold text-red-800">Error!</p>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Stats Cards Section -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Installed -->
            <div class="glass-effect rounded-3xl shadow-2xl p-8 hover-lift animate-slide-in-left animate-delay-1">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-bold rounded-full">Total</span>
                </div>
                <div>
                    <p class="text-4xl font-black text-slate-900">{{ $installedPlugins->count() }}</p>
                    <p class="text-slate-600 mt-2 font-medium">Total Plugin Terpasang</p>
                </div>
            </div>

            <!-- Active Plugins -->
            <div class="glass-effect rounded-3xl shadow-2xl p-8 hover-lift animate-slide-in-left animate-delay-2">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-bold rounded-full">Aktif</span>
                </div>
                <div>
                    <p class="text-4xl font-black text-slate-900">{{ $installedPlugins->where('status', 'active')->count() }}</p>
                    <p class="text-slate-600 mt-2 font-medium">Plugin Sedang Berjalan</p>
                </div>
            </div>

            <!-- Available -->
            <div class="glass-effect rounded-3xl shadow-2xl p-8 hover-lift animate-slide-in-left animate-delay-3">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm font-bold rounded-full">Tersedia</span>
                </div>
                <div>
                    <p class="text-4xl font-black text-slate-900">{{ $availablePlugins->count() }}</p>
                    <p class="text-slate-600 mt-2 font-medium">Plugin Tersedia</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Installed Plugins Section -->
    <div class="max-w-7xl mx-auto">
        @if($installedPlugins->count() > 0)
            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
                <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900">Plugin Terpasang</h2>
                                <p class="text-slate-600">Kelola semua plugin yang telah terpasang</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-black text-primary-600">{{ $installedPlugins->count() }}</p>
                            <p class="text-sm text-slate-500 font-medium">Total Terpasang</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="space-y-6">
                        @foreach($installedPlugins as $installation)
                            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden hover-lift animate-slide-in-left {{ $loop->iteration % 3 === 0 ? 'animate-delay-1' : ($loop->iteration % 3 === 1 ? 'animate-delay-2' : 'animate-delay-3') }}">
                                <!-- Plugin Header -->
                                <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-6 text-white">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-sm font-bold rounded-full">
                                            Version {{ $installation->version }}
                                        </span>
                                    </div>
                                    <h3 class="text-xl font-bold text-white mb-2">{{ $installation->plugin->name ?? 'Unknown Plugin' }}</h3>
                                    @if($installation->plugin->price > 0)
                                        <span class="inline-flex items-center px-3 py-1 bg-white/20 backdrop-blur-sm text-blue-100 text-sm font-medium rounded-full">
                                            Rp{{ number_format($installation->plugin->price, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-white/20 backdrop-blur-sm text-emerald-100 text-sm font-medium rounded-full">
                                            Gratis
                                        </span>
                                    @endif
                                </div>

                                <div class="p-6">
                                    <!-- Description -->
                                    <p class="text-slate-600 mb-6 leading-relaxed">{{ $installation->plugin->description ?? 'No description available' }}</p>

                                    <!-- Installation Info -->
                                    <div class="mb-6">
                                        <div class="flex items-center gap-2 mb-3">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <div>
                                                <p class="text-slate-700 font-medium text-sm">Installed on</p>
                                                <p class="text-slate-900 font-bold">{{ $installation->installed_at ? $installation->installed_at->format('d M Y') : 'Unknown' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                                        <div class="flex items-center gap-2">
                                            @if($installation->status === 'active')
                                                <span class="px-4 py-2 status-active text-xs font-bold rounded-full uppercase tracking-wider shadow-lg">
                                                    Active
                                                </span>
                                                <form action="{{ route('tenant.plugin.deactivate', $installation->plugin) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="group px-6 py-3 bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                                                        <span class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l2 2m-2-2v6m0 0v6m0 0v1m0-1c0 1.11.89 2 2 2h2a2 2 0 002-2v-1"/>
                                                            </svg>
                                                            Deactivate
                                                        </span>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="px-4 py-2 status-inactive text-xs font-bold rounded-full uppercase tracking-wider shadow-lg">
                                                    Inactive
                                                </span>
                                                <form action="{{ route('tenant.plugin.activate', $installation->plugin) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="group px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                                                        <span class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6m6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                                            </svg>
                                                            Activate
                                                        </span>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('tenant.plugin.show', $installation->plugin) }}" class="group p-3 bg-primary-50 hover:bg-primary-100 text-primary-600 rounded-xl transition-all duration-200 hover-lift" title="Plugin Details">
                                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('tenant.plugin.uninstall', $installation->plugin) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to uninstall this plugin?')">
                                                @csrf
                                                <button type="submit" class="group px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                                                    <span class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116 2.828H5.07a2 2 0 01-2.828 1.414L12 4.586A7.001 7.001 0 0010 10V17a2 2 0 002 2z"/>
                                                        </svg>
                                                        Uninstall
                                                    </span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Available Plugins Section -->
        @if($availablePlugins->count() > 0)
            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
                <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900">Plugin Tersedia</h2>
                                <p class="text-slate-600">Plugin yang dapat dipasang dari marketplace</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-black text-purple-600">{{ $availablePlugins->count() }}</p>
                            <p class="text-sm text-slate-500 font-medium">Tersedia</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($availablePlugins as $plugin)
                            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden hover-lift animate-slide-in-left {{ $loop->iteration % 3 === 0 ? 'animate-delay-1' : ($loop->iteration % 3 === 1 ? 'animate-delay-2' : 'animate-delay-3') }}">
                                <!-- Plugin Header -->
                                <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                            </svg>
                                        </div>
                                        @if($plugin->price > 0)
                                            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-sm font-bold rounded-full">
                                                Rp{{ number_format($plugin->price, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-sm font-bold rounded-full">
                                                Gratis
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-xl font-bold text-white mb-2">{{ $plugin->name }}</h3>
                                    <p class="text-purple-100 text-sm mb-4">{{ $plugin->description ?? 'No description available' }}</p>
                                </div>

                                <div class="p-6">
                                    <!-- Actions -->
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <a href="{{ route('tenant.plugin.show', $plugin) }}" class="group p-3 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-xl transition-all duration-200 hover-lift" title="Plugin Details">
                                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                        </div>
                                        <div>
                                            <form action="{{ route('tenant.plugin.install', $plugin) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="group px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                                                    <span class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6m6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                                        </svg>
                                                        Install Plugin
                                                    </span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Empty State -->
    @if($installedPlugins->count() === 0 && $availablePlugins->count() === 0)
        <div class="glass-effect rounded-3xl shadow-2xl p-16 text-center animate-fade-in-up">
            <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-8">
                <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-4">No Plugins Found</h3>
            <p class="text-slate-600 max-w-md mx-auto mb-8">You haven't installed any plugins yet. Browse the marketplace to discover available plugins for your system.</p>
            <div class="flex items-center justify-center gap-4">
                <a href="{{ route('tenant.marketplace.index') }}" class="group px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Browse Marketplace
                    </span>
                </a>
                <a href="{{ route('tenant.plugin.active') }}" class="group px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all duration-300">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        View Active Plugins
                    </span>
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
