@extends('layouts.tenant-platform')

@section('title', 'Domain Yayasan')

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
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
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
    .animate-gradient {
        background-size: 200% 200%;
        animation: gradientShift 6s ease-in-out infinite;
    }
    .animate-delay-1 { animation-delay: 0.1s; }
    .animate-delay-2 { animation-delay: 0.2s; }
    .animate-delay-3 { animation-delay: 0.3s; }
    .glass-effect {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
    }
    .domain-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }
    .domain-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border-color: rgba(99, 102, 241, 0.3);
    }
    .input-glass {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }
    .input-glass:focus {
        background: rgba(255, 255, 255, 0.95);
        border-color: rgba(99, 102, 241, 0.5);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    .success-message {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 4px 20px -8px rgba(16, 185, 129, 0.4);
    }
    .domain-status {
        transition: all 0.3s ease;
    }
    .domain-status.active {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    .domain-status.pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    .domain-status.inactive {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto mb-12">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-cyan-500 to-teal-500 p-8 md:p-12 text-white animate-fade-in-up shadow-2xl shadow-blue-500/20 animate-gradient">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2 animate-pulse-slow" style="animation-delay: 1s;"></div>
            <div class="absolute top-0 left-1/4 w-32 h-32 bg-white/5 rounded-full animate-pulse-slow" style="animation-delay: 2s;"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-6">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-2xl opacity-20 blur-xl"></div>
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center relative">
                            <svg class="w-8 h-8 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-white to-cyan-100 bg-clip-text text-transparent">
                            Domain Yayasan
                        </h1>
                        <p class="text-blue-100 text-xl leading-relaxed mt-2">
                            Kelola custom domain untuk portal layanan yayasan Anda
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="max-w-7xl mx-auto mb-8">
        <div class="success-message rounded-3xl p-6 text-white animate-fade-in-up animate-delay-1">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Section -->
        <div class="lg:col-span-2 space-y-6">
            <div class="domain-card rounded-3xl p-8 animate-fade-in-up animate-delay-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900">Pengaturan Domain</h2>
                </div>
                
                <form action="{{ route('tenant.yayasan.domain.update') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <!-- Domain Input Section -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                                Custom Domain
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       name="custom_domain" 
                                       value="{{ old('custom_domain', $yayasan->custom_domain ?? '') }}" 
                                       class="input-glass w-full px-6 py-4 rounded-2xl outline-none placeholder:text-slate-400 text-slate-900 font-medium pr-16" 
                                       placeholder="contoh: portal.yayasananda.com">
                                <div class="absolute right-4 top-1/2 -translate-y-1/2">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-sm text-slate-600 mt-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Masukkan domain kustom yang telah diarahkan ke server kami melalui A Record atau CNAME.
                            </p>
                        </div>

                        <!-- Current Domain Status -->
                        @if($yayasan->custom_domain)
                        <div class="p-6 bg-gradient-to-br from-slate-50 to-blue-50 rounded-2xl border border-slate-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-slate-900">Status Domain Saat Ini</h3>
                                <div class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium flex items-center gap-1">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                    Aktif
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-white rounded-xl">
                                    <span class="text-sm text-slate-600">Domain</span>
                                    <span class="text-sm font-medium text-slate-900">{{ $yayasan->custom_domain }}</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white rounded-xl">
                                    <span class="text-sm text-slate-600">Subdomain Default</span>
                                    <span class="text-sm font-medium text-slate-900">{{ request()->getHost() }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6 border-t border-slate-200">
                        <button type="submit" 
                                class="px-8 py-4 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white rounded-2xl font-bold transition-all duration-200 shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/40 hover:-translate-y-1 flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Domain Guide -->
            <div class="domain-card rounded-3xl p-6 animate-slide-in-left animate-delay-1">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Panduan Domain
                </h3>
                <div class="space-y-4">
                    <div class="p-4 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl border border-blue-200">
                        <h4 class="font-semibold text-slate-900 mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold">1</span>
                            Arahkan DNS
                        </h4>
                        <p class="text-sm text-slate-600">Tambahkan A Record atau CNAME yang menunjuk ke server kami.</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border border-amber-200">
                        <h4 class="font-semibold text-slate-900 mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 bg-amber-500 text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                            Tunggu Propagasi
                        </h4>
                        <p class="text-sm text-slate-600">DNS memerlukan waktu 24-48 jam untuk propagasi global.</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl border border-emerald-200">
                        <h4 class="font-semibold text-slate-900 mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 bg-emerald-500 text-white rounded-full flex items-center justify-center text-xs font-bold">3</span>
                            Verifikasi
                        </h4>
                        <p class="text-sm text-slate-600">Sistem akan otomatis memverifikasi domain Anda.</p>
                    </div>
                </div>
            </div>

            <!-- DNS Records Info -->
            <div class="domain-card rounded-3xl p-6 animate-slide-in-left animate-delay-2">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                    Informasi DNS
                </h3>
                <div class="space-y-3">
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <p class="text-xs font-semibold text-slate-600 mb-1">A Record</p>
                        <p class="text-sm font-mono text-slate-900">192.168.1.1</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <p class="text-xs font-semibold text-slate-600 mb-1">CNAME</p>
                        <p class="text-sm font-mono text-slate-900">{{ request()->getHost() }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <p class="text-xs font-semibold text-slate-600 mb-1">TTL</p>
                        <p class="text-sm font-mono text-slate-900">3600 (1 jam)</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="domain-card rounded-3xl p-6 animate-slide-in-left animate-delay-3">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Aksi Cepat
                </h3>
                <div class="space-y-3">
                    <button class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-xl hover:from-blue-700 hover:to-cyan-600 transition-all duration-200 text-sm font-medium flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        Test DNS
                    </button>
                    <button class="w-full px-4 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-all duration-200 text-sm font-medium flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m9.032 4.026a9.001 9.001 0 01-7.432 0m9.032-4.026A9.001 9.001 0 0112 3c-4.474 0-8.268 2.943-9.543 7a9.97 9.97 0 011.842 3.026m9.032-4.026A9.97 9.97 0 0112 21c-4.474 0-8.268-2.943-9.543-7"/>
                        </svg>
                        Bantuan DNS
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
