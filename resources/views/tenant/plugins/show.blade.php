@extends('layouts.tenant-platform')

@section('title', $plugin->name . ' - Plugin Details')

@section('header', $plugin->name)

@section('content')
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3 mb-6">
                        <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center gap-3 mb-6">
                        <svg class="w-5 h-5 flex-shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-sm text-slate-600 mb-6">
                    <a href="{{ route('tenant.plugin.purchase') }}" class="hover:text-slate-900 transition-colors">
                        Plugin Marketplace
                    </a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-slate-900">{{ $plugin->name }}</span>
                </div>

                <!-- Plugin Header -->
                <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl p-8 mb-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div class="flex items-center gap-6">
                            <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-slate-900 mb-2">{{ $plugin->name }}</h1>
                                <div class="flex items-center gap-4 text-slate-600">
                                    @if($plugin->version)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2m0 0V2a1 1 0 011-1h4a1 1 0 011 1v2m0 0h-3m-3 0h-3"/>
                                            </svg>
                                            v{{ $plugin->version }}
                                        </span>
                                    @endif
                                    @if($plugin->category)
                                        <span class="px-3 py-1 bg-white/80 text-primary-700 text-xs font-medium rounded-full">
                                            {{ $plugin->category }}
                                        </span>
                                    @endif
                                    @if($plugin->featured_label)
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded-full">
                                            {{ $plugin->featured_label }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            @if($installation)
                                @if($installation->status === 'active')
                                    <span class="px-4 py-2 bg-emerald-100 text-emerald-700 font-medium rounded-xl">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Installed & Active
                                    </span>
                                @else
                                    <span class="px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-xl">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        Installed
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Description -->
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="p-6 border-b border-slate-100">
                                <h2 class="text-lg font-semibold text-slate-900">Description</h2>
                            </div>
                            <div class="p-6">
                                <p class="text-slate-600 leading-relaxed">{{ $plugin->description ?? 'No description available.' }}</p>
                            </div>
                        </div>

                        <!-- Features -->
                        @if($plugin->features)
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                                <div class="p-6 border-b border-slate-100">
                                    <h2 class="text-lg font-semibold text-slate-900">Features</h2>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach((array) $plugin->features as $feature)
                                            <div class="flex items-start gap-3">
                                                <div class="w-6 h-6 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                                <span class="text-slate-700">{{ $feature }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Requirements -->
                        @if($plugin->requirements)
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                                <div class="p-6 border-b border-slate-100">
                                    <h2 class="text-lg font-semibold text-slate-900">Requirements</h2>
                                </div>
                                <div class="p-6">
                                    <div class="space-y-3">
                                        @foreach((array) $plugin->requirements as $requirement)
                                            <div class="flex items-start gap-3">
                                                <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                                    <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </div>
                                                <span class="text-slate-700">{{ $requirement }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Pricing Card -->
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-slate-900 mb-6">Pricing & Installation</h3>
                                
                                <div class="text-center mb-6">
                                    @if($plugin->price > 0)
                                        <div class="text-4xl font-bold text-primary-600 mb-2">{{ number_format($plugin->price, 0, ',', '.') }}</div>
                                        <p class="text-slate-600">one-time payment</p>
                                    @else
                                        <div class="text-4xl font-bold text-emerald-600 mb-2">Free</div>
                                        <p class="text-slate-600">no cost</p>
                                    @endif
                                </div>

                                @if($installation)
                                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-4">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-emerald-700 font-medium">Plugin is installed</span>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        @if($installation->status === 'active')
                                            <form action="{{ route('tenant.plugin.deactivate', $plugin) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors">
                                                    Deactivate Plugin
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('tenant.plugin.activate', $plugin) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors">
                                                    Activate Plugin
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('tenant.plugin.uninstall', $plugin) }}" method="POST" onsubmit="return confirm('Are you sure you want to uninstall this plugin?')">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-medium rounded-lg transition-colors">
                                                Uninstall Plugin
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <form action="{{ route('tenant.plugin.install', $plugin) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-medium rounded-xl shadow-sm transition-all duration-200">
                                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                            Install Plugin
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <!-- Installation Details -->
                        @if($installation)
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Installation Details</h3>
                                    <div class="space-y-3">
                                        <div>
                                            <div class="text-sm text-slate-500 mb-1">Installed</div>
                                            <div class="font-medium text-slate-900">{{ $installation->installed_at->format('M d, Y H:i') }}</div>
                                        </div>
                                        @if($installation->activated_at)
                                            <div>
                                                <div class="text-sm text-slate-500 mb-1">Last Activated</div>
                                                <div class="font-medium text-slate-900">{{ $installation->activated_at->format('M d, Y H:i') }}</div>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm text-slate-500 mb-1">Status</div>
                                            <div class="font-medium text-slate-900 capitalize">{{ $installation->status }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm text-slate-500 mb-1">Version</div>
                                            <div class="font-medium text-slate-900">{{ $installation->version }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h3>
                                <div class="space-y-2">
                                    <a href="{{ route('tenant.plugin.purchase') }}" class="w-full px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors text-center block">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                        </svg>
                                        Back to Marketplace
                                    </a>
                                    @if($installation)
                                        <a href="{{ route('tenant.plugin.installed') }}" class="w-full px-4 py-2 bg-primary-100 hover:bg-primary-200 text-primary-700 font-medium rounded-lg transition-colors text-center block">
                                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            My Plugins
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection
