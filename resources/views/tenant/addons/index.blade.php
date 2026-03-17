@extends('layouts.tenant-platform')

@section('title', 'Add-on Management')

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
                    <h1 class="text-3xl md:text-4xl font-bold">Add-on Management</h1>
                </div>
                <p class="text-primary-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Enhance your school management system with powerful add-ons and extensions
                </p>
                
                <div class="flex items-center justify-center gap-4">
                    <a href="{{ route('tenant.marketplace.index') }}" class="group px-8 py-4 bg-white text-primary-600 font-bold rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                        <span class="relative z-10 flex items-center gap-2">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>Browse Marketplace</span>
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-primary-50 to-primary-100 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Available Add-ons -->
            <div class="glass-effect rounded-3xl shadow-2xl p-8 hover-lift animate-slide-in-left animate-delay-1">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-bold rounded-full">{{ $addons->count() }} Total</span>
                </div>
                <div>
                    <p class="text-4xl font-black text-slate-900">{{ $addons->where('is_active', true)->count() }}</p>
                    <p class="text-slate-600 mt-2 font-medium">Available Add-ons</p>
                </div>
            </div>

            <!-- Installed Add-ons -->
            <div class="glass-effect rounded-3xl shadow-2xl p-8 hover-lift animate-slide-in-left animate-delay-2">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-bold rounded-full">Active</span>
                </div>
                <div>
                    <p class="text-4xl font-black text-slate-900">0</p>
                    <p class="text-slate-600 mt-2 font-medium">Installed Add-ons</p>
                </div>
            </div>

            <!-- Free Add-ons -->
            <div class="glass-effect rounded-3xl shadow-2xl p-8 hover-lift animate-slide-in-left animate-delay-3">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm font-bold rounded-full">Free</span>
                </div>
                <div>
                    <p class="text-4xl font-black text-slate-900">{{ $addons->where('price', 0)->count() }}</p>
                    <p class="text-slate-600 mt-2 font-medium">Free Add-ons</p>
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

    <!-- Available Add-ons Section -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-2">Available Add-ons</h2>
                        <p class="text-slate-600">{{ $addons->count() }} add-ons available to enhance your system</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-black text-primary-600">{{ $addons->count() }}</p>
                        <p class="text-sm text-slate-500 font-medium">Total Available</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                @if($addons->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($addons as $addon)
                            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden hover-lift animate-slide-in-left {{ $loop->iteration % 3 === 0 ? 'animate-delay-1' : ($loop->iteration % 3 === 1 ? 'animate-delay-2' : 'animate-delay-3') }}">
                                <!-- Addon Header -->
                                <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-6 text-white">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                            </svg>
                                        </div>
                                        @if($addon->price > 0)
                                            <span class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white text-sm font-bold rounded-full">
                                                {{ $addon->getFormattedPrice() }}
                                            </span>
                                        @else
                                            <span class="px-4 py-2 bg-emerald-400/30 backdrop-blur-sm text-emerald-100 text-sm font-bold rounded-full">
                                                Free
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-xl font-bold text-white mb-2">{{ $addon->name }}</h3>
                                    @if($addon->category)
                                        <p class="text-primary-100 text-sm">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            {{ $addon->category }}
                                        </p>
                                    @endif
                                </div>

                                <div class="p-6">
                                    <!-- Description -->
                                    <p class="text-slate-600 mb-6 leading-relaxed">{{ $addon->description ?? 'No description available' }}</p>

                                    <!-- Features Preview -->
                                    @if($addon->features)
                                        <div class="mb-6">
                                            <p class="text-sm font-bold text-slate-900 mb-3">Key Features:</p>
                                            <div class="space-y-2">
                                                @foreach(array_slice($addon->getFeaturesArray(), 0, 3) as $feature)
                                                    <div class="flex items-center text-sm text-slate-700">
                                                        <div class="w-5 h-5 bg-emerald-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        </div>
                                                        {{ $feature }}
                                                    </div>
                                                @endforeach
                                                @if(count($addon->getFeaturesArray()) > 3)
                                                    <div class="text-xs text-slate-500 font-medium">+{{ count($addon->getFeaturesArray()) - 3 }} more features</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Status & Actions -->
                                    <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                                        <div class="flex items-center gap-2">
                                            @if($addon->isActive())
                                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">
                                                    Active
                                                </span>
                                            @endif
                                            @if($addon->isExpired())
                                                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full">
                                                    Expired
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('tenant.addon.show', $addon) }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm">
                                                Details
                                            </a>
                                            @if(!$addon->isActive() && !$addon->isExpired())
                                                <form action="{{ route('tenant.addon.purchase', $addon) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-bold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg">
                                                        Get Add-on
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-8">
                            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">No Add-ons Available</h3>
                        <p class="text-slate-600 mb-8 max-w-md mx-auto">Browse marketplace to discover powerful add-ons for your school management system.</p>
                        <a href="{{ route('tenant.marketplace.index') }}" class="group px-8 py-4 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                            <span class="relative z-10 flex items-center gap-2">
                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span>Browse Marketplace</span>
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-primary-700 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Installed Add-ons Section -->
    <div class="max-w-7xl mx-auto">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up animate-delay-2">
            <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-2">Installed Add-ons</h2>
                        <p class="text-slate-600">Manage your currently installed add-ons</p>
                    </div>
                    <a href="{{ route('tenant.plugin.installed') }}" class="text-primary-600 hover:text-primary-700 font-medium flex items-center gap-2">
                        View All
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="p-8">
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">No Add-ons Installed</h3>
                    <p class="text-slate-600 mb-6 max-w-md mx-auto">Install add-ons to enhance your school management system capabilities.</p>
                    <a href="{{ route('tenant.plugin.installed') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors">
                        Manage Plugins
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
