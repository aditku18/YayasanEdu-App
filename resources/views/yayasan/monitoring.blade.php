@extends('layouts.dashboard')

@section('title', 'Monitoring Unit')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Monitoring Unit</h1>
            <p class="text-slate-500 mt-1">Pantau aktivitas dan statistik seluruh unit sekolah secara real-time.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 premium-shadow">
            <h3 class="text-slate-500 font-bold text-sm uppercase tracking-widest">Login Hari Ini</h3>
            <p class="text-4xl font-extrabold text-slate-900 mt-2">124</p>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 premium-shadow">
            <h3 class="text-slate-500 font-bold text-sm uppercase tracking-widest">Data Input Baru</h3>
            <p class="text-4xl font-extrabold text-slate-900 mt-2">1,202</p>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 premium-shadow">
            <h3 class="text-slate-500 font-bold text-sm uppercase tracking-widest">Health Score</h3>
            <p class="text-4xl font-extrabold text-green-500 mt-2">98%</p>
        </div>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 premium-shadow text-center py-20">
        <p class="text-slate-400 font-bold italic">Visualisasi grafik monitoring sedang disiapkan...</p>
    </div>
</div>
@endsection
