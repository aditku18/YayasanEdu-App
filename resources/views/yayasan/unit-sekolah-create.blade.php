@extends('layouts.tenant-platform')

@section('title', 'Tambah Unit Sekolah Baru')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
    <!-- Header Section -->
    <div class="max-w-6xl mx-auto mb-8">
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl border border-white/50 shadow-xl p-8">
            <div class="flex items-center justify-between">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Tambah Unit Sekolah Baru</h1>
                            <p class="text-slate-500 text-sm">Lengkapi data profil dan buat akun administrator unit</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('tenant.units.index') }}" class="group flex items-center gap-2 px-6 py-3 bg-white/60 hover:bg-white/80 text-slate-600 hover:text-slate-800 font-medium rounded-2xl transition-all duration-200 shadow-sm hover:shadow-md border border-slate-200/50">
                    <svg class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="max-w-6xl mx-auto mb-6">
        <div class="bg-gradient-to-r from-rose-50 to-red-50 border border-rose-200 p-6 rounded-3xl flex items-center gap-4 shadow-lg">
            <div class="w-10 h-10 bg-rose-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-rose-800">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('tenant.units.store') }}" method="POST" class="max-w-6xl mx-auto space-y-8">
        @csrf

        <!-- Progress Bar -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl border border-white/50 shadow-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary-500 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                    <span class="text-sm font-medium text-slate-700">Profil Sekolah</span>
                </div>
                <div class="flex-1 mx-4">
                    <div class="h-1 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-primary-500 to-primary-600 rounded-full" style="width: 25%"></div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center text-sm font-bold">2</div>
                    <span class="text-sm text-slate-400">Lokasi</span>
                </div>
                <div class="flex-1 mx-4">
                    <div class="h-1 bg-slate-200 rounded-full"></div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center text-sm font-bold">3</div>
                    <span class="text-sm text-slate-400">Kepala Sekolah</span>
                </div>
                <div class="flex-1 mx-4">
                    <div class="h-1 bg-slate-200 rounded-full"></div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center text-sm font-bold">4</div>
                    <span class="text-sm text-slate-400">Admin</span>
                </div>
            </div>
        </div>

        <!-- Section 1: Profil Sekolah -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl border border-white/50 shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">1</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Profil & Identitas Sekolah</h3>
                        <p class="text-primary-100 text-sm">Informasi dasar dan identitas sekolah</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H6a2 2 0 100 4h2a2 2 0 100-4h2a1 1 0 100-2 2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2H6z" clip-rule="evenodd"/>
                            </svg>
                            Nama Sekolah
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-50 border-2 border-slate-200 focus:border-primary-500 rounded-2xl px-6 py-4 text-slate-800 font-semibold focus:ring-4 focus:ring-primary-100 transition-all duration-200 @error('name') border-rose-400 bg-rose-50 @enderror" placeholder="Contoh: SMA IT Bina Bangsa">
                        @error('name') <p class="text-xs font-bold text-rose-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm2 0h12v8H4V6z" clip-rule="evenodd"/>
                            </svg>
                            Jenjang Sekolah
                        </label>
                        <select name="level" required class="w-full bg-slate-50 border-2 border-slate-200 focus:border-primary-500 rounded-2xl px-6 py-4 text-slate-800 font-semibold focus:ring-4 focus:ring-primary-100 transition-all duration-200 @error('level') border-rose-400 bg-rose-50 @enderror">
                            <option value="">Pilih Jenjang</option>
                            <option value="TK" {{ old('level') == 'TK' ? 'selected' : '' }}>TK / PAUD</option>
                            <option value="SD" {{ old('level') == 'SD' ? 'selected' : '' }}>SD / MI</option>
                            <option value="SMP" {{ old('level') == 'SMP' ? 'selected' : '' }}>SMP / MTs</option>
                            <option value="SMA" {{ old('level') == 'SMA' ? 'selected' : '' }}>SMA / MA</option>
                            <option value="SMK" {{ old('level') == 'SMK' ? 'selected' : '' }}>SMK</option>
                        </select>
                        @error('level') <p class="text-xs font-bold text-rose-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            NPSN
                        </label>
                        <input type="text" name="npsn" value="{{ old('npsn') }}" class="w-full bg-slate-50 border-2 border-slate-200 focus:border-primary-500 rounded-2xl px-6 py-4 text-slate-800 font-semibold focus:ring-4 focus:ring-primary-100 transition-all duration-200 @error('npsn') border-rose-400 bg-rose-50 @enderror" placeholder="Nomor Pokok Sekolah Nasional">
                        @error('npsn') <p class="text-xs font-bold text-rose-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            Email Sekolah
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-slate-50 border-2 border-slate-200 focus:border-primary-500 rounded-2xl px-6 py-4 text-slate-800 font-semibold focus:ring-4 focus:ring-primary-100 transition-all duration-200 @error('email') border-rose-400 bg-rose-50 @enderror" placeholder="sekolah@contoh.com">
                        @error('email') <p class="text-xs font-bold text-rose-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            Nomor Telepon
                        </label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full bg-slate-50 border-2 border-slate-200 focus:border-primary-500 rounded-2xl px-6 py-4 text-slate-800 font-semibold focus:ring-4 focus:ring-primary-100 transition-all duration-200 @error('phone') border-rose-400 bg-rose-50 @enderror" placeholder="021-XXXXXXX">
                        @error('phone') <p class="text-xs font-bold text-rose-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Alamat Lengkap -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl border border-white/50 shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">2</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Lokasi & Alamat</h3>
                        <p class="text-emerald-100 text-sm">Informasi lokasi dan alamat lengkap sekolah</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            Alamat Lengkap
                        </label>
                        <textarea name="address" required rows="3" class="w-full bg-slate-50 border-2 border-slate-200 focus:border-emerald-500 rounded-2xl px-6 py-4 text-slate-800 font-semibold focus:ring-4 focus:ring-emerald-100 transition-all duration-200 @error('address') border-rose-400 bg-rose-50 @enderror" placeholder="Jl. Raya No. XX...">{{ old('address') }}</textarea>
                        @error('address') <p class="text-xs font-bold text-rose-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0h8v12H6V4z" clip-rule="evenodd"/>
                            </svg>
                            Provinsi
                        </label>
                        <input type="text" name="province" value="{{ old('province') }}" required class="w-full bg-slate-50 border-2 border-slate-200 focus:border-emerald-500 rounded-2xl px-6 py-4 text-slate-800 font-semibold focus:ring-4 focus:ring-emerald-100 transition-all duration-200 @error('province') border-rose-400 bg-rose-50 @enderror" placeholder="Contoh: Jawa Barat">
                        @error('province') <p class="text-xs font-bold text-rose-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0h8v12H6V4z" clip-rule="evenodd"/>
                            </svg>
                            Kabupaten / Kota
                        </label>
                        <input type="text" name="city" value="{{ old('city') }}" required class="w-full bg-slate-50 border-2 border-slate-200 focus:border-emerald-500 rounded-2xl px-6 py-4 text-slate-800 font-semibold focus:ring-4 focus:ring-emerald-100 transition-all duration-200 @error('city') border-rose-400 bg-rose-50 @enderror" placeholder="Contoh: Bandung">
                        @error('city') <p class="text-xs font-bold text-rose-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0h8v12H6V4z" clip-rule="evenodd"/>
                            </svg>
                            Kecamatan
                        </label>
                        <input type="text" name="district" value="{{ old('district') }}" required class="w-full bg-slate-50 border-2 border-slate-200 focus:border-emerald-500 rounded-2xl px-6 py-4 text-slate-800 font-semibold focus:ring-4 focus:ring-emerald-100 transition-all duration-200 @error('district') border-rose-400 bg-rose-50 @enderror" placeholder="Contoh: Coblong">
                        @error('district') <p class="text-xs font-bold text-rose-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            Kode Pos
                        </label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" required class="w-full bg-slate-50 border-2 border-slate-200 focus:border-emerald-500 rounded-2xl px-6 py-4 text-slate-800 font-semibold focus:ring-4 focus:ring-emerald-100 transition-all duration-200 @error('postal_code') border-rose-400 bg-rose-50 @enderror" placeholder="XXXXX">
                        @error('postal_code') <p class="text-xs font-bold text-rose-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Kepala Sekolah -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl border border-white/50 shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">3</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Data Kepala Sekolah</h3>
                        <p class="text-amber-100 text-sm">Informasi kepala sekolah dan kontak</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Nama Lengkap Kepala Sekolah
                        </label>
                        <input type="text" name="principal_name" value="{{ old('principal_name') }}" required class="w-full bg-slate-50 border-2 border-slate-200 focus:border-amber-500 rounded-2xl px-6 py-4 text-slate-800 font-semibold focus:ring-4 focus:ring-amber-100 transition-all duration-200 @error('principal_name') border-rose-400 bg-rose-50 @enderror" placeholder="Nama Lengkap & Gelar">
                        @error('principal_name') <p class="text-xs font-bold text-rose-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            Email Kepala Sekolah
                        </label>
                        <input type="email" name="principal_email" value="{{ old('principal_email') }}" required class="w-full bg-slate-50 border-2 border-slate-200 focus:border-amber-500 rounded-2xl px-6 py-4 text-slate-800 font-semibold focus:ring-4 focus:ring-amber-100 transition-all duration-200 @error('principal_email') border-rose-400 bg-rose-50 @enderror" placeholder="kepsek@sekolah.com">
                        @error('principal_email') <p class="text-xs font-bold text-rose-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            No. HP Kepala Sekolah
                        </label>
                        <input type="text" name="principal_phone" value="{{ old('principal_phone') }}" required class="w-full bg-slate-50 border-2 border-slate-200 focus:border-amber-500 rounded-2xl px-6 py-4 text-slate-800 font-semibold focus:ring-4 focus:ring-amber-100 transition-all duration-200 @error('principal_phone') border-rose-400 bg-rose-50 @enderror" placeholder="08XXXXXXXXX">
                        @error('principal_phone') <p class="text-xs font-bold text-rose-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: Akun Admin -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl border border-white/50 shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-rose-500 to-rose-600 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">4</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Akun Administrator Unit</h3>
                        <p class="text-rose-100 text-sm">Data akun admin untuk mengelola sekolah</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Nama Admin Sekolah
                        </label>
                        <input type="text" name="admin_name" value="{{ old('admin_name') }}" required class="w-full bg-slate-50 border-2 border-slate-200 focus:border-rose-500 rounded-2xl px-6 py-4 text-slate-800 font-semibold focus:ring-4 focus:ring-rose-100 transition-all duration-200 @error('admin_name') border-rose-400 bg-rose-50 @enderror" placeholder="Nama Admin">
                        @error('admin_name') <p class="text-xs font-bold text-rose-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            Email Login Admin
                        </label>
                        <input type="email" name="admin_email" value="{{ old('admin_email') }}" required class="w-full bg-slate-50 border-2 border-slate-200 focus:border-rose-500 rounded-2xl px-6 py-4 text-slate-800 font-semibold focus:ring-4 focus:ring-rose-100 transition-all duration-200 @error('admin_email') border-rose-400 bg-rose-50 @enderror" placeholder="admin@sekolah.com">
                        @error('admin_email') <p class="text-xs font-bold text-rose-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            Password
                        </label>
                        <input type="password" name="admin_password" required class="w-full bg-slate-50 border-2 border-slate-200 focus:border-rose-500 rounded-2xl px-6 py-4 text-slate-800 font-semibold focus:ring-4 focus:ring-rose-100 transition-all duration-200 @error('admin_password') border-rose-400 bg-rose-50 @enderror" placeholder="********">
                        @error('admin_password') <p class="text-xs font-bold text-rose-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3 h-3 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            Konfirmasi Password
                        </label>
                        <input type="password" name="admin_password_confirmation" required class="w-full bg-slate-50 border-2 border-slate-200 focus:border-rose-500 rounded-2xl px-6 py-4 text-slate-800 font-semibold focus:ring-4 focus:ring-rose-100 transition-all duration-200" placeholder="********">
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl border border-white/50 shadow-xl p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-slate-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-700">Periksa kembali data sebelum menyimpan</p>
                        <p class="text-xs text-slate-500">Pastikan semua informasi sudah benar dan lengkap</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button type="reset" class="group px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 font-medium rounded-2xl transition-all duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset Form
                    </button>
                    <button type="submit" class="group px-12 py-4 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold rounded-2xl transition-all duration-200 shadow-xl shadow-primary-100 hover:shadow-2xl hover:shadow-primary-200 flex items-center gap-3 transform hover:scale-105">
                        <svg class="w-5 h-5 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan & Daftarkan Unit</span>
                        <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
