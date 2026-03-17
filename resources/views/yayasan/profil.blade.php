@extends('layouts.tenant-platform')

@section('title', 'Profil Yayasan')

@section('header', 'Profil Yayasan')

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
    .profile-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }
    .profile-card:hover {
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
    .error-message {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        box-shadow: 0 4px 20px -8px rgba(239, 68, 68, 0.4);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto mb-12">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-purple-500 to-pink-500 p-8 md:p-12 text-white animate-fade-in-up shadow-2xl shadow-indigo-500/20 animate-gradient">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2 animate-pulse-slow" style="animation-delay: 1s;"></div>
            <div class="absolute top-0 left-1/4 w-32 h-32 bg-white/5 rounded-full animate-pulse-slow" style="animation-delay: 2s;"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-6">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-400 to-pink-500 rounded-2xl opacity-20 blur-xl"></div>
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center relative">
                            <svg class="w-8 h-8 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-white to-purple-100 bg-clip-text text-transparent">
                            Manajemen Yayasan
                        </h1>
                        <p class="text-purple-100 text-xl leading-relaxed mt-2">
                            Atur identitas, visi, misi, dan informasi legalitas yayasan Anda agar tampil profesional di seluruh platform EduSaaS
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
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

    @if ($errors->any())
    <div class="max-w-7xl mx-auto mb-8">
        <div class="error-message rounded-3xl p-6 text-white animate-fade-in-up animate-delay-1">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0 mt-1">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold mb-3">Terdapat kesalahan pada input Anda:</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto" x-data="{ 
        editing: false,
        logoPreview: '{{ $yayasan->logo ? Storage::url($yayasan->logo) : '' }}',
        handleLogoChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.logoPreview = URL.createObjectURL(file);
            }
        }
    }">
        
        <!-- Edit Button -->
        <div class="flex justify-end mb-6">
            <button 
                x-show="!editing" 
                @click="editing = true"
                class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-500 text-white font-medium rounded-xl hover:from-indigo-700 hover:to-purple-600 transition-all shadow-lg shadow-indigo-500/25 hover:shadow-xl hover:shadow-indigo-500/40 hover:-translate-y-1"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit Profil
            </button>
        </div>

    <!-- View Mode -->
    <div x-show="!editing" x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
        
        <!-- Hero Profile Card -->
        <div class="profile-card rounded-3xl overflow-hidden animate-fade-in-up animate-delay-2">
            <div class="h-48 bg-gradient-to-r from-indigo-600 via-purple-500 to-pink-500 relative">
                <!-- Decorative pattern -->
                <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='1' fill-rule='evenodd'%3E%3Ccircle cx='3' cy='3' r='3'/%3E%3Ccircle cx='13' cy='13' r='3'/%3E%3C/g%3E%3C/svg%3E');"></div>
            </div>
            
            <div class="px-8 pb-8">
                <div class="relative flex justify-between items-end -mt-20 mb-6">
                    <div class="w-40 h-40 rounded-2xl bg-white p-3 shadow-2xl ring-4 ring-white/50 relative z-10 hover-lift">
                        <div class="w-full h-full rounded-xl bg-gradient-to-br from-slate-50 to-blue-50 flex items-center justify-center overflow-hidden border border-slate-200">
                            @if($yayasan->logo)
                                <img src="{{ Storage::url($yayasan->logo) }}" alt="{{ $yayasan->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-5xl font-bold text-indigo-300">{{ substr($yayasan->name ?? 'Y', 0, 1) }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    <div class="lg:col-span-2 space-y-8">
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 mb-3">{{ $yayasan->name ?? 'Nama Yayasan Belum Diatur' }}</h2>
                            <p class="text-slate-600 text-lg flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $yayasan->address ?? 'Alamat belum diatur' }}
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <h3 class="text-lg font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 text-white flex items-center justify-center shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </span>
                                    Visi
                                </h3>
                                <div class="p-6 rounded-2xl bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200 italic text-slate-700 text-base leading-relaxed">
                                    "{{ $yayasan->vision ?? 'Visi yayasan belum diatur.' }}"
                                </div>
                            </div>
                            <div class="space-y-4">
                                <h3 class="text-lg font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 text-white flex items-center justify-center shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </span>
                                    Misi
                                </h3>
                                <div class="p-6 rounded-2xl bg-gradient-to-br from-emerald-50 to-green-50 border border-emerald-200 text-slate-700 text-base leading-relaxed whitespace-pre-line">
                                    {{ $yayasan->mission ?? 'Misi yayasan belum diatur.' }}
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-500 to-amber-600 text-white flex items-center justify-center shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </span>
                                Sejarah
                            </h3>
                            <div class="p-6 rounded-2xl bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 text-slate-700 text-base leading-relaxed whitespace-pre-line">
                                {{ $yayasan->history ?? 'Sejarah yayasan belum diatur.' }}
                            </div>
                        </div>

                    </div>

                    <div class="space-y-6">
                        <div class="profile-card rounded-2xl p-6 space-y-5 animate-slide-in-left animate-delay-1">
                            <h3 class="text-lg font-bold text-slate-900 border-b border-slate-200 pb-3">Kontak & Info</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-400 uppercase">Email Resmi</p>
                                        <p class="text-base font-medium text-slate-900 mt-1">{{ $yayasan->email ?? '-' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center text-green-600 shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-400 uppercase">Telepon</p>
                                        <p class="text-base font-medium text-slate-900 mt-1">{{ $yayasan->phone ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-50 to-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3M9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-400 uppercase">Website</p>
                                        <a href="{{ $yayasan->website ?? '#' }}" target="_blank" class="text-base font-medium text-indigo-600 hover:text-indigo-700 mt-1">{{ $yayasan->website ?? '-' }}</a>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-card rounded-2xl p-6 space-y-4 animate-slide-in-left animate-delay-2">
                                <h3 class="text-lg font-bold text-slate-900 border-b border-slate-200 pb-3">Legalitas & Akta</h3>
                                <div class="text-base text-slate-700 font-mono bg-gradient-to-br from-slate-50 to-blue-50 p-4 rounded-xl border border-slate-200">
                                    {{ $yayasan->legalitas ?? 'Nomor akta belum diatur.' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Mode -->
    <form 
        x-show="editing" 
        x-cloak
        x-transition:enter="transition-all duration-300 ease-out" 
        x-transition:enter-start="opacity-0 translate-y-4" 
        x-transition:enter-end="opacity-100 translate-y-0"
        action="{{ route('tenant.yayasan.profil.update') }}" 
        method="POST" 
        enctype="multipart/form-data" 
        class="space-y-8"
        style="display: none;"
    >
        @csrf
        
        <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100 p-8 space-y-10">
            <!-- Brand & Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
                <div class="md:col-span-4 lg:col-span-3 space-y-4">
                    <h3 class="text-lg font-bold text-slate-900">Logo Yayasan</h3>
                    <p class="text-sm text-slate-500">Unggah logo resmi yayasan. Disarankan menggunakan format PNG transparan dengan ukuran rasion 1:1. Maksimal 2MB.</p>
                    
                    @if(session('error'))
                    <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mt-2 border border-red-100">
                        {{ session('error') }}
                    </div>
                    @endif
                    @error('logo')
                    <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mt-2 border border-red-100">
                        {{ $message }}
                    </div>
                    @enderror

                    <div class="relative group mt-6 inline-block">
                        <div class="w-36 h-36 bg-slate-50 rounded-2xl border-2 border-dashed {{ $errors->has('logo') || session('error') ? 'border-red-400 bg-red-50' : 'border-slate-200' }} flex items-center justify-center overflow-hidden transition-all group-hover:border-primary-400 group-hover:bg-primary-50/50">
                            <template x-if="logoPreview">
                                <img :src="logoPreview" alt="Logo Preview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!logoPreview">
                                <div class="text-center p-4">
                                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-xs text-slate-400 font-medium">Pilih Gambar</span>
                                </div>
                            </template>
                        </div>
                        <label for="logo-input" class="absolute -bottom-3 -right-3 bg-white p-3 rounded-full shadow-lg shadow-slate-200 border border-slate-100 cursor-pointer hover:bg-slate-50 hover:scale-110 transition-all text-primary-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <input type="file" id="logo-input" name="logo" class="sr-only" accept="image/*" @change="handleLogoChange">
                        </label>
                    </div>
                </div>


                <div class="md:col-span-8 lg:col-span-9">
                    <h3 class="text-lg font-bold text-slate-900 mb-6 border-b border-slate-100 pb-4">Informasi Utama</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700">Nama Yayasan <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ $yayasan->name ?? '' }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700">Email Resmi</label>
                            <input type="email" name="email" value="{{ $yayasan->email ?? '' }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700">Telepon</label>
                            <input type="text" name="phone" value="{{ $yayasan->phone ?? '' }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700">Website URL</label>
                            <input type="url" name="website" value="{{ $yayasan->website ?? '' }}" placeholder="https://" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-sm font-semibold text-slate-700">Alamat Lengkap</label>
                            <textarea name="address" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">{{ $yayasan->address ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100 border-dashed">

            <!-- Detail Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3">Identitas & Legalitas</h3>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Legalitas / Akta Pendirian</label>
                        <textarea name="legalitas" rows="3" placeholder="Contoh: Akta Notaris No. X Tahun XXXX..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none font-mono text-sm leading-relaxed">{{ $yayasan->legalitas ?? '' }}</textarea>
                        <p class="text-xs text-slate-500">Maksimum 500 karakter</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Sejarah Singkat</label>
                        <textarea name="history" rows="6" placeholder="Ceritakan bagaimana yayasan ini didirikan..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none leading-relaxed">{{ $yayasan->history ?? '' }}</textarea>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3">Arah & Tujuan</h3>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Visi Yayasan</label>
                        <textarea name="vision" rows="3" placeholder="Tujuan besar yayasan..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none leading-relaxed">{{ $yayasan->vision ?? '' }}</textarea>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Misi Yayasan</label>
                        <textarea name="mission" rows="6" placeholder="Langkah-langkah strategis untuk mencapai visi..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none leading-relaxed">{{ $yayasan->mission ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-50 p-6 -mx-8 -my-8 mt-8 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-3xl">
                <button type="button" @click="editing = false" class="px-6 py-2.5 text-slate-500 font-semibold hover:bg-slate-200/50 rounded-xl transition-colors">Batal</button>
                <button type="submit" class="px-8 py-2.5 bg-primary-600 text-white font-semibold rounded-xl hover:bg-primary-700 transition-all shadow-sm focus:ring-4 focus:ring-primary-100 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
