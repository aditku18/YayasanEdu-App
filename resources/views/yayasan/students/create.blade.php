@extends('layouts.dashboard')

@section('title', 'Tambah Siswa')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ $schoolSlug ? route('tenant.school.students.index', ['school' => $schoolSlug]) : route('tenant.students.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 transition">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tambah Data Siswa</h1>
            <p class="text-slate-500 text-sm mt-0.5">Lengkapi informasi siswa baru di bawah ini.</p>
        </div>
    </div>

    <form action="{{ $schoolSlug ? route('tenant.school.students.store', ['school' => $schoolSlug]) : route('tenant.students.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- ── SEKSI 1: Identitas Akademik ── --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800 text-sm">Identitas Akademik</h2>
                    <p class="text-xs text-slate-500">Nomor induk dan data registrasi sekolah</p>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                {{-- NIS --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">NIS</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                        </span>
                        <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Nomor Induk Siswa"
                            class="pl-9 block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition @error('nis') border-red-400 bg-red-50 @enderror">
                    </div>
                    @error('nis')<p class="text-red-500 text-xs mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p>@enderror
                </div>

                {{-- NISN --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">NISN</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                        </span>
                        <input type="text" name="nisn" value="{{ old('nisn') }}" placeholder="Nomor Induk Siswa Nasional"
                            class="pl-9 block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition @error('nisn') border-red-400 bg-red-50 @enderror">
                    </div>
                    @error('nisn')<p class="text-red-500 text-xs mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p>@enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Status Siswa</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        <select name="status" class="pl-9 block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition @error('status') border-red-400 bg-red-50 @enderror">
                            <option value="Aktif" {{ old('status','Aktif')=='Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Lulus" {{ old('status')=='Lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="Pindah" {{ old('status')=='Pindah' ? 'selected' : '' }}>Pindah</option>
                            <option value="Drop Out" {{ old('status')=='Drop Out' ? 'selected' : '' }}>Drop Out</option>
                        </select>
                    </div>
                    @error('status')<p class="text-red-500 text-xs mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p>@enderror
                </div>

                {{-- Kelas --}}
                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Kelas <span class="text-slate-400 font-normal normal-case">(opsional)</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </span>
                        <select name="classroom_id" class="pl-9 block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition">
                            <option value="">— Belum ditentukan —</option>
                            @foreach($classrooms as $class)
                                <option value="{{ $class->id }}" {{ old('classroom_id')==$class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('classroom_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- ── SEKSI 2: Data Pribadi ── --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800 text-sm">Data Pribadi</h2>
                    <p class="text-xs text-slate-500">Identitas diri dan kependudukan siswa</p>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- NIK --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">NIK <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                            </svg>
                        </span>
                        <input type="text" name="nik" value="{{ old('nik') }}" placeholder="16 digit NIK KTP/KK"
                            class="pl-9 block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition @error('nik') border-red-400 bg-red-50 @enderror">
                    </div>
                    @error('nik')<p class="text-red-500 text-xs mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p>@enderror
                </div>

                {{-- Nama Lengkap --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Nama Lengkap <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </span>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama sesuai akta kelahiran"
                            class="pl-9 block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition @error('name') border-red-400 bg-red-50 @enderror">
                    </div>
                    @error('name')<p class="text-red-500 text-xs mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p>@enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Jenis Kelamin</label>
                    <div class="flex gap-3 mt-1">
                        <label class="flex-1 flex items-center gap-2.5 p-3 rounded-xl border-2 cursor-pointer transition
                            {{ old('gender')=='L' ? 'border-blue-500 bg-blue-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                            <input type="radio" name="gender" value="L" {{ old('gender')=='L' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-sm font-medium text-slate-700">Laki-laki</span>
                        </label>
                        <label class="flex-1 flex items-center gap-2.5 p-3 rounded-xl border-2 cursor-pointer transition
                            {{ old('gender')=='P' ? 'border-pink-500 bg-pink-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                            <input type="radio" name="gender" value="P" {{ old('gender')=='P' ? 'checked' : '' }} class="text-pink-600 focus:ring-pink-500">
                            <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-sm font-medium text-slate-700">Perempuan</span>
                        </label>
                    </div>
                    @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Tempat & Tanggal Lahir --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Tempat, Tanggal Lahir</label>
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </span>
                            <input type="text" name="birth_place" value="{{ old('birth_place') }}" placeholder="Kota"
                                class="pl-9 block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition @error('birth_place') border-red-400 @enderror">
                        </div>
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                                class="pl-9 block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition @error('birth_date') border-red-400 @enderror">
                        </div>
                    </div>
                    @error('birth_place')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    @error('birth_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Alamat --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Alamat Lengkap</label>
                    <div class="relative">
                        <span class="absolute top-3 left-3 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </span>
                        <textarea name="address" rows="2" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota"
                            class="pl-9 block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition @error('address') border-red-400 bg-red-50 @enderror">{{ old('address') }}</textarea>
                    </div>
                    @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- ── SEKSI 3: Data Orang Tua / Wali ── --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800 text-sm">Data Orang Tua / Wali</h2>
                    <p class="text-xs text-slate-500">Informasi kontak keluarga siswa</p>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Nama Ayah --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Nama Ayah</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <input type="text" name="father_name" value="{{ old('father_name') }}" placeholder="Nama lengkap ayah"
                            class="pl-9 block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white transition @error('father_name') border-red-400 bg-red-50 @enderror">
                    </div>
                    @error('father_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Nama Ibu --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Nama Ibu</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <input type="text" name="mother_name" value="{{ old('mother_name') }}" placeholder="Nama lengkap ibu"
                            class="pl-9 block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white transition @error('mother_name') border-red-400 bg-red-50 @enderror">
                    </div>
                    @error('mother_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Nama Wali --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Nama Wali <span class="text-slate-400 font-normal normal-case">(jika ada)</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" placeholder="Nama wali siswa"
                            class="pl-9 block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white transition">
                    </div>
                    @error('guardian_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Nama Orang Tua/Wali (kontak utama) --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Nama Kontak Utama</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </span>
                        <input type="text" name="parent_name" value="{{ old('parent_name') }}" placeholder="Nama orang tua/wali yang bisa dihubungi"
                            class="pl-9 block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white transition @error('parent_name') border-red-400 bg-red-50 @enderror">
                    </div>
                    @error('parent_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Telepon Orang Tua --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Nomor Telepon Orang Tua/Wali</label>
                    <div class="relative max-w-sm">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </span>
                        <input type="text" name="parent_phone" value="{{ old('parent_phone') }}" placeholder="08xx-xxxx-xxxx"
                            class="pl-9 block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white transition @error('parent_phone') border-red-400 bg-red-50 @enderror">
                    </div>
                    @error('parent_phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- ── Action Buttons ── --}}
        <div class="flex items-center justify-between pt-2">
            <a href="{{ $schoolSlug ? route('tenant.school.students.index', ['school' => $schoolSlug]) : route('tenant.students.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 text-sm font-medium hover:bg-slate-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-7 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm hover:shadow-md transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Data Siswa
            </button>
        </div>
    </form>
</div>
@endsection
