@extends('layouts.tenant-platform')

@section('title', 'Plugin Marketplace')

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
    .featured-badge {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    .price-free {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    .price-paid {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">Plugin Marketplace</h1>
                </div>
                <p class="text-primary-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Temukan dan pasang plugin untuk meningkatkan fungsionalitas sistem yayasan Anda
                </p>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $plugins->count() }}</p>
                        <p class="text-primary-100 text-sm">Total Plugin</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $plugins->where('price', 0)->count() }}</p>
                        <p class="text-primary-100 text-sm">Plugin Gratis</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $plugins->where('price', '>', 0)->count() }}</p>
                        <p class="text-primary-100 text-sm">Plugin Berbayar</p>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center justify-center gap-4">
                    <a href="{{ route('tenant.plugin.installed') }}" class="group px-8 py-4 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white font-bold rounded-2xl transition-all duration-300 border border-white/30">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            My Plugins
                        </span>
                    </a>
                    <a href="{{ route('tenant.plugin.active') }}" class="group px-8 py-4 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white font-bold rounded-2xl transition-all duration-300 border border-white/30">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Active Plugins
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

    <!-- Search and Filters Section -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="glass-effect rounded-3xl shadow-2xl p-8 animate-fade-in-up">
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="flex-1">
                    <div class="relative">
                        <svg class="w-5 h-5 text-slate-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" placeholder="Cari plugin..." class="w-full pl-12 pr-4 py-3 bg-white/50 backdrop-blur-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <select class="px-6 py-3 bg-white/50 backdrop-blur-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                    <select class="px-6 py-3 bg-white/50 backdrop-blur-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Harga</option>
                        <option value="free">Gratis</option>
                        <option value="paid">Berbayar</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Plugins Section -->
    <div class="max-w-7xl mx-auto mb-12">
        @if($plugins->where('featured_label', '!=', null)->count() > 0)
            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-8 border-b border-amber-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 1.52a1 1 0 01.61.922l.155.655a6 6 0 01-5.664 5.546l-1.68-1.682a2 2 0 00-2.828 0l-1.519-1.52a1 1 0 01-.61-.922l-.155-.655a6 6 0 015.664-5.546l1.68 1.682a2 2 0 002.828 0l1.519 1.52a1 1 0 01.61.922l.155.655a6 6 0 01-5.664 5.546l-1.68-1.682a2 2 0 00-2.828 0l-1.519-1.52a1 1 0 01-.61-.922l-.155-.655a6 6 0 015.664-5.546l1.68 1.682a2 2 0 002.828 0l1.519 1.52a1 1 0 01.61.922l.155.655A6 6 0 011.049 2.927z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900">Plugin Unggulan</h2>
                                <p class="text-slate-600">Plugin pilihan terbaik untuk yayasan Anda</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-black text-amber-600">{{ $plugins->where('featured_label', '!=', null)->count() }}</p>
                            <p class="text-sm text-slate-500 font-medium">Plugin Unggulan</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($plugins->where('featured_label', '!=', null)->take(3) as $plugin)
                            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden hover-lift animate-slide-in-left {{ $loop->iteration % 3 === 0 ? 'animate-delay-1' : ($loop->iteration % 3 === 1 ? 'animate-delay-2' : 'animate-delay-3') }}">
                                <!-- Plugin Header -->
                                <div class="bg-gradient-to-r from-amber-500 to-orange-600 p-6 text-white">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                            </svg>
                                        </div>
                                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-sm font-bold rounded-full">
                                            {{ $plugin->featured_label }}
                                        </span>
                                    </div>
                                    <h3 class="text-xl font-bold text-white mb-2">{{ $plugin->name }}</h3>
                                    @if($plugin->price > 0)
                                        <span class="inline-flex items-center px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-sm font-medium rounded-full">
                                            Rp{{ number_format($plugin->price, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-sm font-medium rounded-full">
                                            Gratis
                                        </span>
                                    @endif
                                </div>

                                <div class="p-6">
                                    <!-- Description -->
                                    <p class="text-slate-600 mb-6 leading-relaxed">{{ Str::limit($plugin->description ?? 'No description available', 80) }}</p>

                                    <!-- Features Preview -->
                                    @if($plugin->features)
                                        <div class="mb-6">
                                            <p class="text-sm font-bold text-slate-900 mb-3">Fitur Utama:</p>
                                            <div class="space-y-2">
                                                @foreach(array_slice((array) $plugin->features, 0, 3) as $feature)
                                                    <div class="flex items-center text-sm text-slate-700">
                                                        <div class="w-5 h-5 bg-emerald-50 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        </div>
                                                        {{ $feature }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Actions -->
                                    <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                                        <div>
                                            @if($plugin->category)
                                                <div class="flex items-center gap-2 mb-3">
                                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                    </svg>
                                                    <p class="text-slate-700 font-medium text-sm">{{ $plugin->category }}</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('tenant.plugin.show', $plugin) }}" class="group p-3 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-xl transition-all duration-200 hover-lift" title="Plugin Details">
                                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('tenant.plugin.install', $plugin) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="group px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
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

    <!-- All Plugins Section -->
    <div class="max-w-7xl mx-auto">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4V2m0-6V4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">Semua Plugin</h2>
                            <p class="text-slate-600">Jelajahi semua plugin yang tersedia di marketplace</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-black text-primary-600">{{ $plugins->count() }}</p>
                        <p class="text-sm text-slate-500 font-medium">Total Plugin</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                @if($plugins->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($plugins as $plugin)
                            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden hover-lift animate-slide-in-left {{ $loop->iteration % 3 === 0 ? 'animate-delay-1' : ($loop->iteration % 3 === 1 ? 'animate-delay-2' : 'animate-delay-3') }}">
                                <!-- Plugin Header -->
                                <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-6 text-white">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4V2m0-6V4m6 6V10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4V2m0-6V4"/>
                                            </svg>
                                        </div>
                                        <div class="flex flex-col items-end gap-2">
                                            @if($plugin->featured_label)
                                                <span class="px-3 py-1 featured-badge text-white text-xs font-bold rounded-full">
                                                    {{ $plugin->featured_label }}
                                                </span>
                                            @endif
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
                                    </div>
                                    <h3 class="text-xl font-bold text-white mb-2">{{ $plugin->name }}</h3>
                                    <p class="text-primary-100 text-sm mb-4">{{ Str::limit($plugin->description ?? 'No description available', 60) }}</p>
                                </div>

                                <div class="p-6">
                                    <!-- Category -->
                                    @if($plugin->category)
                                        <div class="mb-4">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                                <p class="text-slate-700 font-medium text-sm">{{ $plugin->category }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Features Preview -->
                                    @if($plugin->features)
                                        <div class="mb-6">
                                            <p class="text-sm font-bold text-slate-900 mb-3">Fitur Utama:</p>
                                            <div class="space-y-2">
                                                @foreach(array_slice((array) $plugin->features, 0, 2) as $feature)
                                                    <div class="flex items-center text-sm text-slate-700">
                                                        <div class="w-5 h-5 bg-emerald-50 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        </div>
                                                        {{ $feature }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Actions -->
                                    <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                                        <div>
                                            @if($plugin->price > 0)
                                                <div class="flex items-center gap-2">
                                                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-2xl font-black text-slate-900">Rp{{ number_format($plugin->price, 0, ',', '.') }}</p>
                                                        <p class="text-slate-500 text-xs">Berbayar</p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="flex items-center gap-2">
                                                    <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-2xl font-black text-slate-900">Gratis</p>
                                                        <p class="text-slate-500 text-xs">Tidak Berbayar</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('tenant.plugin.show', $plugin) }}" class="group p-3 bg-primary-50 hover:bg-primary-100 text-primary-600 rounded-xl transition-all duration-200 hover-lift" title="Plugin Details">
                                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('tenant.plugin.install', $plugin) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="group px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
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

                    <!-- Pagination -->
                    <div class="mt-8 flex justify-center">
                        {{ $plugins->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-8">
                            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Tidak Ada Plugin Tersedia</h3>
                        <p class="text-slate-600 max-w-md mx-auto mb-8">Saat ini tidak ada plugin yang tersedia di marketplace. Silakan cek kembali nanti.</p>
                        <div class="flex items-center justify-center gap-4">
                            <a href="{{ route('tenant.plugin.installed') }}" class="group px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2V2M7 7h10"/>
                                    </svg>
                                    My Plugins
                                </span>
                            </a>
                            <a href="{{ route('tenant.plugin.active') }}" class="group px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all duration-300">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Active Plugins
                                </span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
