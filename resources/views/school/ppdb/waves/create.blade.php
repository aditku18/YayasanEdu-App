@extends('layouts.dashboard')

@section('title', 'Tambah Gelombang PPDB')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ $schoolSlug ? route('tenant.school.ppdb.settings', ['school' => $schoolSlug]) : route('tenant.ppdb.settings') }}" class="p-2 bg-white rounded-xl border border-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Tambah Gelombang</h1>
            <p class="text-slate-500 mt-1">Buat periode pendaftaran baru.</p>
        </div>
    </div>

    <form action="{{ $schoolSlug ? route('tenant.school.ppdb.waves.store', ['school' => $schoolSlug]) : route('tenant.ppdb.waves.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 premium-shadow space-y-6">
            <div class="space-y-1">
                <label class="text-sm font-bold text-slate-700 ml-1">Nama Gelombang</label>
                <input type="text" name="name" placeholder="Contoh: Gelombang 1 (Reguler)" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 transition-all font-medium">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <label class="text-sm font-bold text-slate-700 ml-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 transition-all font-medium">
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-bold text-slate-700 ml-1">Tanggal Selesai</label>
                    <input type="date" name="end_date" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 transition-all font-medium">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <label class="text-sm font-bold text-slate-700 ml-1">Tahun Ajaran</label>
                    <select name="academic_year_id" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 transition-all font-medium appearance-none">
                        <option value="">Pilih Tahun Ajaran (Opsional)</option>
                        @foreach($academicYears as $year)
                        <option value="{{ $year->id }}">{{ $year->name }} - {{ $year->semester }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-bold text-slate-700 ml-1">Jurusan (Pilihan Khusus)</label>
                    <select name="major_id" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 transition-all font-medium appearance-none">
                        <option value="">-- Berlaku untuk Semua Jurusan --</option>
                        @foreach($majors as $major)
                        <option value="{{ $major->id }}">Khusus {{ $major->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <label class="text-sm font-bold text-slate-700 ml-1">Biaya Pendaftaran (Rp)</label>
                    <input type="number" name="registration_fee" placeholder="Contoh: 200000" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 transition-all font-medium">
                    <p class="text-[10px] text-slate-400 mt-1 ml-1">Ditagihkan setelah pengisian formulir.</p>
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-bold text-slate-700 ml-1">Batas Kuota Gelombang Ini (Opsional)</label>
                    <input type="number" name="quota" min="1" placeholder="Misal: 40" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 transition-all font-medium">
                    <p class="text-[10px] text-slate-400 mt-1 ml-1">Pendaftaran ditutup otomatis jika kuota penuh.</p>
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-sm font-bold text-slate-700 ml-1">Keterangan / Deskripsi</label>
                <textarea name="description" rows="3" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 transition-all font-medium"></textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ $schoolSlug ? route('tenant.school.ppdb.settings', ['school' => $schoolSlug]) : route('tenant.ppdb.settings') }}" class="px-6 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">Batalkan</a>
            <button type="submit" class="px-10 py-3 bg-slate-900 text-white font-bold rounded-2xl hover:bg-primary-600 transition-all shadow-xl shadow-slate-200">
                Simpan Gelombang
            </button>
        </div>
    </form>
</div>
@endsection
