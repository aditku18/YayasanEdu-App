@extends('layouts.dashboard')

@section('title', 'Struktur Organisasi')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Struktur Organisasi</h1>
            <p class="text-slate-500 mt-1">Hierarki kepemimpinan dan manajemen yayasan.</p>
        </div>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 premium-shadow space-y-6 text-center py-20">
        <div class="w-20 h-20 bg-primary-50 text-primary-600 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-900">Modul Struktur Organisasi</h2>
        <p class="text-slate-500 max-w-sm mx-auto">Halaman ini sedang dalam pengembangan untuk mendukung visualisasi bagan organisasi yang dinamis.</p>
    </div>
</div>
@endsection
