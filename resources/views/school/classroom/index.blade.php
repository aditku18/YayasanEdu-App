@extends('layouts.dashboard')

@section('title', 'Manajemen Rombel / Kelas')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Rombongan Belajar</h1>
            <p class="text-slate-500 mt-1">Daftar kelas dan wali kelas aktif di unit Anda.</p>
        </div>
        <a href="{{ $schoolSlug ? route('tenant.school.classrooms.create', ['school' => $schoolSlug]) : route('tenant.classrooms.create') }}" class="px-6 py-3 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kelas
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($classrooms as $classroom)
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 premium-shadow group hover:border-primary-100 transition-all">
            <div class="flex items-start justify-between">
                <div class="w-14 h-14 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center font-bold text-xl group-hover:scale-110 transition-transform">
                    {{ $classroom->level }}
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-bold uppercase tracking-widest">Aktif</span>
                </div>
            </div>
            
            <div class="mt-6">
                <h3 class="text-xl font-bold text-slate-900">{{ $classroom->name }}</h3>
                <p class="text-sm text-slate-500 mt-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Wali: {{ $classroom->homeroomTeacher?->name ?? 'Belum Ditentukan' }}
                </p>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-50 flex items-center justify-between">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                    {{ $classroom->students->count() }} Siswa
                </div>
                <a href="{{ $schoolSlug ? route('tenant.school.classrooms.show', ['school' => $schoolSlug, 'classroom' => $classroom->id]) : route('tenant.classrooms.show', $classroom->id) }}" class="text-primary-600 font-bold text-sm hover:underline">Detail Kelas &rarr;</a>
            </div>
        </div>
        @empty
        <div class="col-span-3 py-20 bg-slate-50 border-2 border-dashed border-slate-200 rounded-[3rem] text-center">
            <p class="text-slate-400 font-bold">Belum ada kelas yang terdaftar.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
