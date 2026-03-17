@extends('layouts.dashboard')

@section('title', 'Detail Kelas — ' . $classroom->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ $schoolSlug ? route('tenant.school.classrooms.index', ['school' => $schoolSlug]) : route('tenant.classrooms.index') }}" class="p-2 bg-white rounded-xl border border-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $classroom->name }}</h1>
            <p class="text-slate-500 mt-1">Level {{ $classroom->level }} • Wali Kelas: {{ $classroom->homeroomTeacher?->name ?? 'Belum Ditentukan' }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ $schoolSlug ? route('tenant.school.classrooms.edit', ['school' => $schoolSlug, 'classroom' => $classroom->id]) : route('tenant.classrooms.edit', $classroom->id) }}" class="px-4 py-2 bg-primary-600 text-white text-sm font-bold rounded-xl hover:bg-primary-700 transition-all">
                Edit Kelas
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 premium-shadow">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Siswa</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ $classroom->students->count() }}</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 premium-shadow">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Presensi Hari Ini</p>
            <p class="text-3xl font-extrabold text-amber-500 mt-2">-- %</p>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <h3 class="font-bold text-slate-900 text-lg">Daftar Siswa di Kelas Ini</h3>
            <button class="text-primary-600 font-bold text-sm hover:underline">+ Tambah Siswa ke Kelas</button>
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/20">
                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">NISN</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Lengkap</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($classroom->students as $student)
                <tr>
                    <td class="px-8 py-4 text-sm font-medium text-slate-500">{{ $student->nisn }}</td>
                    <td class="px-8 py-4 font-bold text-slate-900">{{ $student->name }}</td>
                    <td class="px-8 py-4">
                        <span class="px-2 py-0.5 bg-green-50 text-green-600 rounded text-[10px] font-bold uppercase tracking-widest">Aktif</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-8 py-12 text-center text-slate-400 font-medium italic italic">Belum ada siswa yang dimasukkan ke kelas ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
