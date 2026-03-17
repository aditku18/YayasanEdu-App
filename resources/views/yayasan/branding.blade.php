@extends('layouts.tenant-platform')

@section('title', 'Branding Yayasan')

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
    .branding-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }
    .branding-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border-color: rgba(99, 102, 241, 0.3);
    }
    .color-preview {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .color-preview::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.6s;
    }
    .color-preview:hover::before {
        left: 100%;
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
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto mb-12">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600 via-pink-500 to-indigo-500 p-8 md:p-12 text-white animate-fade-in-up shadow-2xl shadow-purple-500/20 animate-gradient">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2 animate-pulse-slow" style="animation-delay: 1s;"></div>
            <div class="absolute top-0 left-1/4 w-32 h-32 bg-white/5 rounded-full animate-pulse-slow" style="animation-delay: 2s;"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-6">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-pink-400 to-purple-500 rounded-2xl opacity-20 blur-xl"></div>
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center relative">
                            <svg class="w-8 h-8 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-white to-pink-100 bg-clip-text text-transparent">
                            Branding Yayasan
                        </h1>
                        <p class="text-purple-100 text-xl leading-relaxed mt-2">
                            Kelola logo dan warna utama untuk tampilan yayasan Anda
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
            <div class="branding-card rounded-3xl p-8 animate-fade-in-up animate-delay-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900">Pengaturan Branding</h2>
                </div>
                
                <form action="{{ route('tenant.yayasan.branding.update') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <!-- Logo Section -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Logo URL / Teks Logo
                            </label>
                            <input type="text" 
                                   name="logo" 
                                   value="{{ old('logo', $yayasan->logo) }}" 
                                   class="input-glass w-full px-6 py-4 rounded-2xl outline-none placeholder:text-slate-400 text-slate-900 font-medium" 
                                   placeholder="Logo atau URL...">
                            <p class="text-sm text-slate-600 mt-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Anda juga dapat mengunggah file logo pada halaman Profil.
                            </p>
                        </div>

                        <!-- Color Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                    </svg>
                                    Warna Utama (Primary)
                                </label>
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <input type="color" 
                                               name="primary_color" 
                                               value="{{ old('primary_color', $yayasan->primary_color ?? '#f59e0b') }}" 
                                               class="w-16 h-16 rounded-2xl cursor-pointer border-0 p-0 color-preview shadow-lg"
                                               oninput="this.nextElementSibling.value = this.value">
                                        <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-white rounded-full shadow-lg flex items-center justify-center">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <input type="text" 
                                               name="primary_color" 
                                               value="{{ old('primary_color', $yayasan->primary_color ?? '#f59e0b') }}" 
                                               class="input-glass flex-1 px-4 py-3 rounded-2xl outline-none placeholder:text-slate-400 text-slate-900 font-mono text-sm" 
                                               placeholder="#f59e0b" 
                                               oninput="this.previousElementSibling.value = this.value">
                                        <div class="mt-2 h-2 rounded-full" style="background-color: {{ old('primary_color', $yayasan->primary_color ?? '#f59e0b') }}"></div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                    </svg>
                                    Warna Sekunder (Secondary)
                                </label>
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <input type="color" 
                                               name="secondary_color" 
                                               value="{{ old('secondary_color', $yayasan->secondary_color ?? '#3b82f6') }}" 
                                               class="w-16 h-16 rounded-2xl cursor-pointer border-0 p-0 color-preview shadow-lg"
                                               oninput="this.nextElementSibling.value = this.value">
                                        <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-white rounded-full shadow-lg flex items-center justify-center">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <input type="text" 
                                               name="secondary_color" 
                                               value="{{ old('secondary_color', $yayasan->secondary_color ?? '#3b82f6') }}" 
                                               class="input-glass flex-1 px-4 py-3 rounded-2xl outline-none placeholder:text-slate-400 text-slate-900 font-mono text-sm" 
                                               placeholder="#3b82f6" 
                                               oninput="this.previousElementSibling.value = this.value">
                                        <div class="mt-2 h-2 rounded-full" style="background-color: {{ old('secondary_color', $yayasan->secondary_color ?? '#3b82f6') }}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6 border-t border-slate-200">
                        <button type="submit" 
                                class="px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-500 hover:from-purple-700 hover:to-pink-600 text-white rounded-2xl font-bold transition-all duration-200 shadow-lg shadow-purple-500/25 hover:shadow-xl hover:shadow-purple-500/40 hover:-translate-y-1 flex items-center gap-3">
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
            <!-- Preview Card -->
            <div class="branding-card rounded-3xl p-6 animate-slide-in-left animate-delay-1">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Preview Branding
                </h3>
                <div class="space-y-4">
                    <div class="p-4 rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-blue-50">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-sm" style="background-color: {{ old('primary_color', $yayasan->primary_color ?? '#f59e0b') }}">
                                {{ substr($yayasan->nama_yayasan ?? 'YAY', 0, 3) }}
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">{{ $yayasan->nama_yayasan ?? 'Nama Yayasan' }}</p>
                                <p class="text-xs text-slate-600">Contoh Tampilan</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <div class="w-8 h-8 rounded" style="background-color: {{ old('primary_color', $yayasan->primary_color ?? '#f59e0b') }}"></div>
                            <div class="w-8 h-8 rounded" style="background-color: {{ old('secondary_color', $yayasan->secondary_color ?? '#3b82f6') }}"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="branding-card rounded-3xl p-6 animate-slide-in-left animate-delay-2">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    Tips Branding
                </h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-xs font-bold text-purple-600">1</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-900">Pilih Warna Kontras</p>
                            <p class="text-xs text-slate-600 mt-1">Pastikan warna primer dan sekunder memiliki kontras yang baik.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-xs font-bold text-purple-600">2</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-900">Logo yang Jelas</p>
                            <p class="text-xs text-slate-600 mt-1">Gunakan logo dengan resolusi tinggi dan mudah dikenali.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-xs font-bold text-purple-600">3</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-900">Konsistensi</p>
                            <p class="text-xs text-slate-600 mt-1">Gunakan warna yang sama di semua platform untuk konsistensi brand.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="branding-card rounded-3xl p-6 animate-slide-in-left animate-delay-3">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Aksi Cepat
                </h3>
                <div class="space-y-3">
                    <button class="w-full px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-500 text-white rounded-xl hover:from-purple-700 hover:to-pink-600 transition-all duration-200 text-sm font-medium flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset ke Default
                    </button>
                    <button class="w-full px-4 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-all duration-200 text-sm font-medium flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m9.032 4.026a9.001 9.001 0 01-7.432 0m9.032-4.026A9.001 9.001 0 0112 3c-4.474 0-8.268 2.943-9.543 7a9.97 9.97 0 011.842 3.026m9.032-4.026A9.97 9.97 0 0112 21c-4.474 0-8.268-2.943-9.543-7"/>
                        </svg>
                        Export Pengaturan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
