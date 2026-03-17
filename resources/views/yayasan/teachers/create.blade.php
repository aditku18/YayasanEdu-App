@extends('layouts.dashboard')

@section('title', 'Tambah Guru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ $schoolSlug ? route('tenant.school.teachers.index', ['school' => $schoolSlug]) : route('tenant.teachers.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 transition">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tambah Data Guru</h1>
            <p class="text-slate-500 text-sm mt-0.5">Lengkapi informasi guru baru di bawah ini.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4">
        <ul class="list-disc pl-5 text-sm text-red-600 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ $schoolSlug ? route('tenant.school.teachers.store', ['school' => $schoolSlug]) : route('tenant.teachers.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Identitas Guru --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800 text-sm">Identitas Guru</h2>
                    <p class="text-xs text-slate-500">Data personal dan akademik guru</p>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Nama --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap guru"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white transition @error('name') border-red-400 bg-red-50 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- NIP --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">NIP</label>
                    <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition @error('nip') border-red-400 bg-red-50 @enderror">
                    @error('nip')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Gender --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="gender" class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 transition @error('gender') border-red-400 @enderror">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@sekolah.id"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 transition @error('email') border-red-400 bg-red-50 @enderror">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 transition @error('phone') border-red-400 bg-red-50 @enderror">
                    @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Unit Sekolah --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Unit Sekolah</label>
                    <select name="school_id" class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 transition">
                        <option value="">Belum Ditempatkan</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Status</label>
                    <select name="is_active" class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 transition">
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                {{-- Alamat --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Alamat</label>
                    <textarea name="address" rows="3" placeholder="Alamat lengkap guru"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 transition @error('address') border-red-400 bg-red-50 @enderror">{{ old('address') }}</textarea>
                    @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ $schoolSlug ? route('tenant.school.teachers.index', ['school' => $schoolSlug]) : route('tenant.teachers.index') }}"
                class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit"
                class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Data Guru
            </button>
        </div>
    </form>
</div>
@endsection
