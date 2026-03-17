@extends('layouts.dashboard')

@section('title', 'Mata Pelajaran — ' . $school->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Mata Pelajaran</h1>
            <p class="text-slate-500 mt-1">Kelola kurikulum dan daftar mata pelajaran di {{ $school->name }}.</p>
        </div>
        <button onclick="location.href='{{ $schoolSlug ? route('tenant.school.subjects.create', ['school' => $schoolSlug]) : route('tenant.subjects.create', ['school' => $school->slug]) }}'" class="px-6 py-3 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Mapel
        </button>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 premium-shadow text-center py-24">
        <div class="w-20 h-20 bg-amber-50 text-amber-600 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-900">Belum Ada Mata Pelajaran</h2>
        <p class="text-slate-500 max-w-sm mx-auto mt-2">Silakan mulai dengan menambahkan mata pelajaran pertama untuk unit sekolah ini.</p>
    </div>
</div>
@endsection
