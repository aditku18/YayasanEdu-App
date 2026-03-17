@extends('layouts.tenant-platform')

@section('title', $addon->name . ' - Add-on Details')

@section('header', $addon->name)

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
                    <a href="{{ route('tenant.addon.index') }}" class="hover:text-slate-900 transition-colors">
                        Add-on Management
                    </a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-slate-900">{{ $addon->name }}</span>
                </div>

                <!-- Addon Header -->
                <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl p-8 mb-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div class="flex items-center gap-6">
                            <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-slate-900 mb-2">{{ $addon->name }}</h1>
                                <div class="flex items-center gap-4 text-slate-600">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $addon->developer ?? 'Unknown Developer' }}
                                    </span>
                                    @if($addon->version)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2m0 0V2a1 1 0 011-1h4a1 1 0 011 1v2m0 0h-3m-3 0h-3"/>
                                            </svg>
                                            v{{ $addon->version }}
                                        </span>
                                    @endif
                                    @if($addon->category)
                                        <span class="px-3 py-1 bg-white/80 text-primary-700 text-xs font-medium rounded-full">
                                            {{ $addon->category }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            @if($addon->isActive())
                                <span class="px-4 py-2 bg-emerald-100 text-emerald-700 font-medium rounded-xl">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Active
                                </span>
                            @endif
                            @if($addon->isExpired())
                                <span class="px-4 py-2 bg-red-100 text-red-700 font-medium rounded-xl">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Expired
                                </span>
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
                                <p class="text-slate-600 leading-relaxed">{{ $addon->description ?? 'No description available.' }}</p>
                            </div>
                        </div>

                        <!-- Features -->
                        @if($addon->features)
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                                <div class="p-6 border-b border-slate-100">
                                    <h2 class="text-lg font-semibold text-slate-900">Features</h2>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($addon->getFeaturesArray() as $feature)
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
                        @if($addon->requirements)
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                                <div class="p-6 border-b border-slate-100">
                                    <h2 class="text-lg font-semibold text-slate-900">Requirements</h2>
                                </div>
                                <div class="p-6">
                                    <div class="space-y-3">
                                        @foreach($addon->getRequirementsArray() as $requirement)
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

                        <!-- Resources -->
                        @if($addon->documentation_url || $addon->support_url)
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                                <div class="p-6 border-b border-slate-100">
                                    <h2 class="text-lg font-semibold text-slate-900">Resources</h2>
                                </div>
                                <div class="p-6">
                                    <div class="flex flex-wrap gap-3">
                                        @if($addon->documentation_url)
                                            <a href="{{ $addon->documentation_url }}" target="_blank" class="px-4 py-2 bg-primary-100 hover:bg-primary-200 text-primary-700 font-medium rounded-lg transition-colors">
                                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                                Documentation
                                            </a>
                                        @endif
                                        @if($addon->support_url)
                                            <a href="{{ $addon->support_url }}" target="_blank" class="px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium rounded-lg transition-colors">
                                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                </svg>
                                                Support
                                            </a>
                                        @endif
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
                                <h3 class="text-lg font-semibold text-slate-900 mb-6">Pricing & Purchase</h3>
                                
                                <div class="text-center mb-6">
                                    @if($addon->price > 0)
                                        <div class="text-4xl font-bold text-primary-600 mb-2">{{ $addon->getFormattedPrice() }}</div>
                                        <p class="text-slate-600">
                                            @if($addon->is_recurring)
                                                per month
                                            @else
                                                one-time payment
                                            @endif
                                        </p>
                                    @else
                                        <div class="text-4xl font-bold text-emerald-600 mb-2">Free</div>
                                        <p class="text-slate-600">no cost</p>
                                    @endif
                                </div>

                                @if($addon->isActive())
                                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-4">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-emerald-700 font-medium">This add-on is currently active</span>
                                        </div>
                                    </div>
                                @elseif($addon->isExpired())
                                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-red-700 font-medium">This add-on has expired</span>
                                        </div>
                                    </div>
                                @else
                                    <form action="{{ route('tenant.addon.purchase', $addon) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-medium rounded-xl shadow-sm transition-all duration-200">
                                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            Purchase Add-on
                                        </button>
                                    </form>
                                @endif

                                @if($addon->max_users || $addon->max_storage)
                                    <div class="mt-4 pt-4 border-t border-slate-100">
                                        <div class="space-y-2 text-sm text-slate-600">
                                            @if($addon->max_users)
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                    </svg>
                                                    Up to {{ $addon->max_users }} users
                                                </div>
                                            @endif
                                            @if($addon->max_storage)
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                                    </svg>
                                                    {{ $addon->max_storage }}GB storage
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Installation Details -->
                        @if($addon->installation_date || $addon->expiry_date)
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Installation Details</h3>
                                    <div class="space-y-3">
                                        @if($addon->installation_date)
                                            <div>
                                                <div class="text-sm text-slate-500 mb-1">Installed</div>
                                                <div class="font-medium text-slate-900">{{ $addon->installation_date->format('M d, Y') }}</div>
                                            </div>
                                        @endif
                                        @if($addon->expiry_date)
                                            <div>
                                                <div class="text-sm text-slate-500 mb-1">Expires</div>
                                                <div class="font-medium text-slate-900">{{ $addon->expiry_date->format('M d, Y') }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h3>
                                <div class="space-y-2">
                                    <a href="{{ route('tenant.addon.index') }}" class="w-full px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors text-center block">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                        </svg>
                                        Back to Add-ons
                                    </a>
                                    <a href="{{ route('tenant.marketplace.index') }}" class="w-full px-4 py-2 bg-primary-100 hover:bg-primary-200 text-primary-700 font-medium rounded-lg transition-colors text-center block">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Browse Marketplace
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection
