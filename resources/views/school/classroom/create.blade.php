@extends('layouts.dashboard')

@section('title', 'Tambah Kelas Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ $schoolSlug ? route('tenant.school.classrooms.index', ['school' => $schoolSlug]) : route('tenant.classrooms.index') }}" class="p-2 bg-white rounded-xl border border-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Tambah Kelas</h1>
            <p class="text-slate-500 mt-1">Buat rombongan belajar baru untuk tahun ajaran aktif.</p>
        </div>
    </div>

    <form action="{{ $schoolSlug ? route('tenant.school.classrooms.store', ['school' => $schoolSlug]) : route('tenant.classrooms.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 premium-shadow space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <label class="text-sm font-bold text-slate-700 ml-1">Nama / Nama Kelas</label>
                    <input type="text" name="name" placeholder="Contoh: X RPL 1" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 transition-all font-medium">
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-bold text-slate-700 ml-1">Tingkat / Level</label>
                    <select name="level" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 transition-all font-medium appearance-none">
                        <option value="">Pilih Tingkat</option>
                        <option value="10">Kelas 10</option>
                        <option value="11">Kelas 11</option>
                        <option value="12">Kelas 12</option>
                    </select>
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-sm font-bold text-slate-700 ml-1">Wali Kelas</label>
                <select name="teacher_id" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3 text-slate-900 focus:ring-2 focus:ring-primary-500 transition-all font-medium appearance-none">
                    <option value="">Pilih Wali Kelas (Opsional)</option>
                    @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ $schoolSlug ? route('tenant.school.classrooms.index', ['school' => $schoolSlug]) : route('tenant.classrooms.index') }}" class="px-6 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">Batalkan</a>
            <button type="submit" class="px-10 py-3 bg-slate-900 text-white font-bold rounded-2xl hover:bg-primary-600 transition-all shadow-xl shadow-slate-200">
                Simpan Kelas
            </button>
        </div>
    </form>
</div>
@endsection
