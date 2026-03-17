@extends('layouts.dashboard')

@section('title', 'Manajemen Guru' . ($schoolSlug ? ' - ' . ucfirst($schoolSlug) : ' Global'))

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Guru</h1>
            <p class="text-slate-500 mt-1">Data agregat seluruh tenaga pendidik di bawah yayasan.</p>
        </div>
        <a href="{{ $schoolSlug ? route('tenant.school.teachers.create', ['school' => $schoolSlug]) : route('tenant.teachers.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Guru
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-6 rounded-[2rem] border border-slate-100 premium-shadow flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Cari nama atau NIP..." class="bg-slate-50 border-none rounded-xl pl-10 pr-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-primary-500 w-64">
            </div>
            <select class="bg-slate-50 border-none rounded-xl px-4 py-2.5 text-sm font-bold text-slate-500 focus:ring-2 focus:ring-primary-500">
                <option value="">Semua Unit Sekolah</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="px-6 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export Guru
        </button>
    </div>

    <!-- Teachers Table -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-[0.15em]">
                        <th class="px-8 py-5">Nama Guru</th>
                        <th class="px-6 py-5">NIP / NUPTK</th>
                        <th class="px-6 py-5">Unit Sekolah</th>
                        <th class="px-6 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($teachers as $teacher)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary-100 text-primary-600 rounded-xl flex items-center justify-center font-bold">
                                    {{ substr($teacher->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 leading-none">{{ $teacher->name }}</p>
                                    <p class="text-xs text-slate-400 mt-1">{{ $teacher->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm font-bold text-slate-600 tracking-wider">{{ $teacher->nip ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg">{{ $teacher->school->name ?? 'Belum Ditempatkan' }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase rounded-full">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                Aktif
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <button class="p-2 text-slate-300 hover:text-primary-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                            <button class="p-2 text-slate-300 hover:text-indigo-600 transition-colors ml-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m4-4l-4-4"/></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-10 text-center text-slate-400 font-medium italic">Belum ada data guru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
