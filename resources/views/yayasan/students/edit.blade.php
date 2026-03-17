@extends('layouts.dashboard')

@section('title', 'Edit Siswa')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ $schoolSlug ? route('tenant.school.students.show', ['school' => $schoolSlug, 'student' => $student->id]) : route('tenant.students.show', $student->id) }}" class="p-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 transition">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Data Siswa</h1>
            <p class="text-slate-500 text-sm mt-0.5">{{ $student->name }}</p>
        </div>
    </div>

    <form action="{{ $schoolSlug ? route('tenant.school.students.update', ['school' => $schoolSlug, 'student' => $student->id]) : route('tenant.students.update', $student->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

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
                    <input type="text" name="nis" value="{{ old('nis', $student->nis) }}" placeholder="Nomor Induk Siswa"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-blue-500 transition @error('nis') border-red-400 bg-red-50 @enderror">
                    @error('nis')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- NISN --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn', $student->nisn) }}" placeholder="Nomor Induk Siswa Nasional"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-blue-500 transition @error('nisn') border-red-400 bg-red-50 @enderror">
                    @error('nisn')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Status Siswa</label>
                    <select name="status" class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-blue-500 transition @error('status') border-red-400 @enderror">
                        @foreach(['Aktif','Lulus','Pindah','Drop Out'] as $s)
                        <option value="{{ $s }}" {{ old('status', $student->status) == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Kelas --}}
                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Kelas</label>
                    <select name="classroom_id" class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">— Belum ditentukan —</option>
                        @foreach($classrooms as $class)
                            <option value="{{ $class->id }}" {{ old('classroom_id', $student->classroom_id) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
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
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik', $student->nik) }}" placeholder="16 digit NIK KTP/KK"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-emerald-500 transition @error('nik') border-red-400 bg-red-50 @enderror">
                    @error('nik')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Nama --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}" placeholder="Nama sesuai akta kelahiran"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-emerald-500 transition @error('name') border-red-400 bg-red-50 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Gender --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Jenis Kelamin</label>
                    <div class="flex gap-3 mt-1">
                        @foreach(['L' => 'Laki-laki', 'P' => 'Perempuan'] as $val => $label)
                        <label class="flex-1 flex items-center gap-2.5 p-3 rounded-xl border-2 cursor-pointer transition
                            {{ old('gender', $student->gender) == $val ? ($val == 'L' ? 'border-blue-500 bg-blue-50' : 'border-pink-500 bg-pink-50') : 'border-slate-200 bg-slate-50' }}">
                            <input type="radio" name="gender" value="{{ $val }}" {{ old('gender', $student->gender) == $val ? 'checked' : '' }}
                                class="{{ $val == 'L' ? 'text-blue-600' : 'text-pink-600' }} focus:ring-2">
                            <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Tempat & Tanggal Lahir --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Tempat, Tanggal Lahir</label>
                    <div class="flex gap-2">
                        <input type="text" name="birth_place" value="{{ old('birth_place', $student->birth_place) }}" placeholder="Kota"
                            class="flex-1 rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-emerald-500 transition">
                        <input type="date" name="birth_date" value="{{ old('birth_date', $student->birth_date) }}"
                            class="flex-1 rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-emerald-500 transition">
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Alamat Lengkap</label>
                    <textarea name="address" rows="2" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-emerald-500 transition">{{ old('address', $student->address) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── SEKSI 3: Orang Tua / Wali ── --}}
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
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Nama Ayah</label>
                    <input type="text" name="father_name" value="{{ old('father_name', $student->father_name) }}" placeholder="Nama lengkap ayah"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Nama Ibu</label>
                    <input type="text" name="mother_name" value="{{ old('mother_name', $student->mother_name) }}" placeholder="Nama lengkap ibu"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Nama Wali</label>
                    <input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}" placeholder="Nama wali siswa"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Nama Kontak Utama</label>
                    <input type="text" name="parent_name" value="{{ old('parent_name', $student->parent_name) }}" placeholder="Nama yang bisa dihubungi"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">No. HP Orang Tua/Wali</label>
                    <input type="text" name="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}" placeholder="08xx-xxxx-xxxx"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:ring-2 focus:ring-amber-500 transition">
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-2">
            <a href="{{ $schoolSlug ? route('tenant.school.students.show', ['school' => $schoolSlug, 'student' => $student->id]) : route('tenant.students.show', $student->id) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 text-sm font-medium hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-7 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
