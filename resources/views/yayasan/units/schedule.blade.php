@extends('layouts.dashboard')

@section('title', 'Jadwal Pelajaran — ' . $school->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Jadwal Pelajaran</h1>
            <p class="text-slate-500 mt-1">Atur jadwal kegiatan belajar mengajar untuk setiap kelas.</p>
        </div>
        <button onclick="location.href='{{ $schoolSlug ? route('tenant.school.schedule.create', ['school' => $schoolSlug]) : route('tenant.schedule.create', ['school' => $school->slug]) }}'" class="px-6 py-3 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Buat Jadwal
        </button>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 premium-shadow text-center py-24">
        <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-900">Jadwal Belum Terbit</h2>
        <p class="text-slate-500 max-w-sm mx-auto mt-2">Pilih kelas dan tahun ajaran untuk mulai menyusun jadwal pelajaran.</p>
    </div>
</div>
@endsection
