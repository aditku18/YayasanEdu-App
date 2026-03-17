@extends('layouts.dashboard')

@section('title', 'Tambah Komponen Nilai')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Tambah Komponen Nilai</h1>
            <p class="text-slate-500 mt-1">Buat komponen penilaian baru seperti UH, UTS, UAS, atau Tugas.</p>
        </div>
        <a href="{{ route('tenant.school.grades.index', ['school' => $schoolSlug]) }}" class="text-sm font-bold text-slate-400 hover:text-slate-600">Kembali</a>
    </div>

    <!-- Form -->
    <form action="{{ route('tenant.school.grades.store-component', ['school' => $schoolSlug]) }}" method="POST" class="space-y-6">
        @csrf

        <!-- Basic Info -->
        <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 premium-shadow space-y-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center font-bold">1</div>
                <h3 class="text-lg font-bold text-slate-900">Informasi Komponen</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Nama Komponen</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: UH 1, UAS Ganjil" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all @error('name') ring-2 ring-rose-500 @enderror">
                    @error('name') <p class="text-xs font-bold text-rose-500 px-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Kode</label>
                    <input type="text" name="code" value="{{ old('code') }}" required placeholder="Contoh: UH1, UAS" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all @error('code') ring-2 ring-rose-500 @enderror">
                    @error('code') <p class="text-xs font-bold text-rose-500 px-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Jenis Nilai</label>
                    <select name="type" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all @error('type') ring-2 ring-rose-500 @enderror">
                        <option value="">Pilih Jenis</option>
                        <option value="daily" {{ old('type') == 'daily' ? 'selected' : '' }}>Ulangan Harian (UH)</option>
                        <option value="assignment" {{ old('type') == 'assignment' ? 'selected' : '' }}>Tugas</option>
                        <option value="midterm" {{ old('type') == 'midterm' ? 'selected' : '' }}>UTS</option>
                        <option value="final" {{ old('type') == 'final' ? 'selected' : '' }}>UAS</option>
                        <option value="project" {{ old('type') == 'project' ? 'selected' : '' }}>Proyek</option>
                    </select>
                    @error('type') <p class="text-xs font-bold text-rose-500 px-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Bobot (%)</label>
                    <input type="number" name="weight" value="{{ old('weight', 0) }}" required min="0" max="100" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all @error('weight') ring-2 ring-rose-500 @enderror">
                    @error('weight') <p class="text-xs font-bold text-rose-500 px-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Nilai Maksimal</label>
                    <input type="number" name="max_score" value="{{ old('max_score', 100) }}" required min="1" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all @error('max_score') ring-2 ring-rose-500 @enderror">
                    @error('max_score') <p class="text-xs font-bold text-rose-500 px-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Semester</label>
                    <select name="semester" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all">
                        <option value="">Pilih Semester</option>
                        <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="Genap" {{ old('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Academic Info -->
        <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 premium-shadow space-y-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center font-bold">2</div>
                <h3 class="text-lg font-bold text-slate-900">Tahun Ajaran & Mapel</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Tahun Ajaran</label>
                    <select name="academic_year_id" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all @error('academic_year_id') ring-2 ring-rose-500 @enderror">
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('academic_year_id') <p class="text-xs font-bold text-rose-500 px-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Mata Pelajaran</label>
                    <select name="subject_id" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all">
                        <option value="">Semua Mapel</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2 md:col-span-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Kelas</label>
                    <select name="class_room_id" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all">
                        <option value="">Semua Kelas</option>
                        @foreach($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" {{ old('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                            {{ $classRoom->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-4">
            <a href="{{ route('tenant.school.grades.index', ['school' => $schoolSlug]) }}" class="px-8 py-4 text-slate-400 font-bold hover:text-slate-600 transition-all">Batal</a>
            <button type="submit" class="px-10 py-4 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-100 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Komponen
            </button>
        </div>
    </form>
</div>
@endsection
