@extends('layouts.tenant-platform')

@section('title', 'Detail Plugin - ' . $plugin->name)
@section('header', 'Detail Plugin Market')

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
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center gap-3 mb-6">
            <svg class="w-5 h-5 flex-shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Purchase Summary Errors -->
    @if(isset($purchaseSummary) && !$purchaseSummary['can_install'])
        <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl flex items-center gap-3 mb-6">
            <svg class="w-5 h-5 flex-shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <strong>Tidak dapat menginstall plugin:</strong>
                <ul class="mt-1 list-disc list-inside">
                    @foreach($purchaseSummary['errors'] as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('tenant.marketplace.index') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 transition-colors font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Marketplace
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Header Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                <div class="flex flex-col md:flex-row gap-6 items-start">
                    <div class="w-24 h-24 bg-indigo-50 rounded-2xl flex items-center justify-center flex-shrink-0">
                        @if($plugin->logo)
                            <img src="{{ Storage::url($plugin->logo) }}" alt="{{ $plugin->name }}" class="w-16 h-16 object-contain">
                        @else
                            <svg class="w-12 h-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <h1 class="text-3xl font-bold text-slate-900">{{ $plugin->name }}</h1>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 uppercase tracking-wide">
                                v{{ $plugin->version ?? '1.0.0' }}
                            </span>
                        </div>
                        <p class="text-slate-500 mb-4">Dikembangkan oleh: <span class="font-medium text-slate-700">{{ $plugin->developer ?? 'EduSaaS Official' }}</span></p>
                        
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-50 border border-slate-100 text-sm text-slate-600">
                                <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                {{ ucfirst($plugin->category ?? 'General') }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-50 border border-slate-100 text-sm text-slate-600">
                                <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                {{ number_format($plugin->installations->count() ?? 0) }} Installs
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Deskripsi Modul</h2>
                <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                    {{ $plugin->description ?? 'Tidak ada deskripsi yang tersedia untuk modul ini.' }}
                </div>
            </div>

            <!-- Features -->
            @if($plugin->features && is_array($plugin->features) && count($plugin->features) > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                    <h2 class="text-xl font-bold text-slate-900 mb-6">Fitur Unggulan</h2>
                    <ul class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($plugin->features as $feature)
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-slate-700">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Sidebar / Action Card -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sticky top-6">
                <!-- Pricing Info -->
                <div class="text-center mb-6 pb-6 border-b border-slate-100">
                    <p class="text-sm text-slate-500 font-medium mb-1">Harga Berlangganan</p>
                    <div class="flex items-end justify-center gap-1">
                        @if($plugin->price > 0)
                            <span class="text-3xl font-bold text-slate-900">Rp {{ number_format($plugin->price, 0, ',', '.') }}</span>
                            <span class="text-slate-500 font-medium">/bulan</span>
                        @else
                            <span class="text-3xl font-bold text-emerald-600">Gratis</span>
                            <span class="text-slate-500 font-medium">selamanya</span>
                        @endif
                    </div>
                </div>

                <!-- Action Button -->
                @if(isset($isInstalled) && $isInstalled)
                    <!-- Already Installed -->
                    <div class="space-y-3">
                        <div class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Plugin Terinstall
                        </div>
                        <form action="{{ route('tenant.marketplace.uninstall', $plugin) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan plugin ini?')">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-red-50 hover:bg-red-100 text-red-700 font-bold rounded-xl transition-all border border-red-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Nonaktifkan Plugin
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Install/Subscribe Button -->
                    @if(isset($purchaseSummary) && $purchaseSummary['can_install'])
                        <form action="{{ route('tenant.marketplace.purchase', $plugin) }}" method="POST" id="purchaseForm">
                            @csrf
                            @if(isset($purchaseSummary['payment']))
                                <!-- Paid Plugin -->
                                <div class="mb-4 p-4 bg-blue-50 rounded-xl border border-blue-200">
                                    <div class="text-center">
                                        <p class="text-sm text-blue-600 font-medium mb-2">Detail Pembayaran</p>
                                        <div class="text-2xl font-bold text-blue-900 mb-2">
                                            {{ $purchaseSummary['payment']['formatted_amount'] }}
                                        </div>
                                        <div class="flex flex-wrap gap-2 justify-center text-xs text-blue-600">
                                            @foreach($purchaseSummary['payment']['payment_methods'] as $method)
                                                <span class="px-2 py-1 bg-blue-100 rounded-full">{{ ucfirst(str_replace('_', ' ', $method)) }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" 
                                        id="purchaseButton"
                                        class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-1 relative overflow-hidden">
                                    <span class="button-content">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Berlangganan Sekarang
                                    </span>
                                    <span class="loading-content hidden">
                                        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Memproses...
                                    </span>
                                </button>
                                
                                <div class="mt-3 text-center">
                                    <p class="text-xs text-slate-500">
                                        <i class="fas fa-lock mr-1"></i>
                                        Pembayaran aman dengan transfer bank
                                    </p>
                                </div>
                            @else
                                <!-- Free Plugin -->
                                <button type="submit" 
                                        id="purchaseButton"
                                        class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-1 relative overflow-hidden">
                                    <span class="button-content">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        Install Modul (Gratis)
                                    </span>
                                    <span class="loading-content hidden">
                                        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Menginstall...
                                    </span>
                                </button>
                            @endif
                        </form>
                        
                        <p class="text-xs text-center text-slate-500 mt-4">
                            Dengan melanjutkan, Anda menyetujui <a href="#" class="text-primary-600 hover:underline">Syarat & Ketentuan</a> YayasanEdu.
                        </p>
                    @else
                        <!-- Cannot Install -->
                        <div class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-gray-100 text-gray-500 font-bold rounded-xl cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            Tidak Tersedia
                        </div>
                    @endif
                @endif

                <!-- Requirements -->
                @if($plugin->requirements && is_array($plugin->requirements) && count($plugin->requirements) > 0)
                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <h4 class="text-sm font-bold text-slate-900 mb-3">Persyaratan Sistem</h4>
                        <ul class="space-y-2">
                            @foreach($plugin->requirements as $requirement)
                                <li class="flex items-center gap-2 text-sm text-slate-600">
                                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $requirement }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if($plugin->documentation_url)
                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <a href="{{ $plugin->documentation_url }}" target="_blank" class="flex items-center justify-between group p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-white group-hover:shadow-sm transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 group-hover:text-primary-600 transition-colors">Baca Dokumentasi</p>
                                    <p class="text-xs text-slate-500">Panduan penggunaan modul</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const purchaseForm = document.getElementById('purchaseForm');
            const purchaseButton = document.getElementById('purchaseButton');
            
            if (purchaseForm && purchaseButton) {
                purchaseForm.addEventListener('submit', function(e) {
                    // Disable button and show loading state
                    purchaseButton.disabled = true;
                    
                    const buttonContent = purchaseButton.querySelector('.button-content');
                    const loadingContent = purchaseButton.querySelector('.loading-content');
                    
                    if (buttonContent && loadingContent) {
                        buttonContent.classList.add('hidden');
                        loadingContent.classList.remove('hidden');
                    }
                    
                    // Add visual loading effect
                    purchaseButton.classList.add('opacity-75', 'cursor-not-allowed');
                    purchaseButton.style.transform = 'scale(0.98)';
                });
            }
        });
    </script>
@endsection
