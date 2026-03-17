@extends('layouts.tenant-platform')

@section('title', 'Manajemen Unit Sekolah')

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
    .animate-slide-in-left {
        animation: slideInLeft 0.8s ease-out forwards;
        opacity: 0;
    }
    .animate-pulse-slow {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    .animate-delay-1 { animation-delay: 0.1s; }
    .animate-delay-2 { animation-delay: 0.2s; }
    .animate-delay-3 { animation-delay: 0.3s; }
    .glass-effect {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
    <!-- Hero / Header Section -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary-600 via-primary-500 to-indigo-500 p-8 md:p-10 text-white animate-fade-in-up shadow-2xl shadow-primary-500/20">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2 animate-pulse-slow" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 right-1/4 w-32 h-32 bg-indigo-300/20 rounded-full blur-xl animate-pulse-slow" style="animation-delay: 2s;"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div class="max-w-xl">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold">Manajemen Unit Sekolah</h1>
                    </div>
                    <p class="text-primary-100 text-base leading-relaxed mb-6">
                        Pusat kontrol seluruh ekosistem pendidikan Anda. Pantau performa, kelola staf, dan optimalkan operasional di setiap unit sekolah.
                    </p>
                    <div class="flex flex-wrap items-center gap-6">
                        <div class="flex flex-col">
                            <span class="text-3xl font-bold">{{ $schools->count() }}</span>
                            <span class="text-xs font-semibold text-primary-200 mt-1 uppercase tracking-wider">Total Unit</span>
                        </div>
                        <div class="h-10 w-px bg-primary-400"></div>
                        <div class="flex flex-col">
                            <span class="text-3xl font-bold">{{ $schools->where('status', 'active')->count() }}</span>
                            <span class="text-xs font-semibold text-emerald-300 mt-1 uppercase tracking-wider">Unit Aktif</span>
                        </div>
                        <div class="h-10 w-px bg-primary-400"></div>
                        <div class="flex flex-col">
                            <span class="text-3xl font-bold">{{ $schools->sum('students_count') }}</span>
                            <span class="text-xs font-semibold text-primary-200 mt-1 uppercase tracking-wider">Total Siswa</span>
                        </div>
                    </div>
                </div>
                <div class="shrink-0">
                    <a href="{{ route('tenant.units.create') }}" 
                       class="group inline-flex items-center gap-3 px-6 py-3.5 bg-white hover:bg-slate-50 text-primary-600 font-bold rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                        <div class="w-8 h-8 bg-primary-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v12m6-6H6"/>
                            </svg>
                        </div>
                        Tambah Unit Sekolah
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-6 animate-fade-in-up animate-delay-1">
        <div class="glass-effect p-6 rounded-2xl shadow-lg hover-lift">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-50 to-blue-100 text-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">TK / PAUD</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ $schools->where('level', 'TK')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="glass-effect p-6 rounded-2xl shadow-lg hover-lift">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-50 to-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">SD / MI</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ $schools->where('level', 'SD')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="glass-effect p-6 rounded-2xl shadow-lg hover-lift">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-50 to-amber-100 text-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">SMP / MTs</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ $schools->where('level', 'SMP')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="glass-effect p-6 rounded-2xl shadow-lg hover-lift">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-rose-50 to-rose-100 text-rose-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">SMA / SMK</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ $schools->whereIn('level', ['SMA', 'SMK'])->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Container -->
    <div class="max-w-7xl mx-auto glass-effect rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up animate-delay-2">
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    Daftar Unit Sekolah
                </h3>
                <p class="text-sm text-slate-500 mt-1">Gunakan fitur pencarian atau filter untuk menemukan unit sekolah tertentu.</p>
            </div>
            <form action="{{ route('tenant.units.index') }}" method="GET" class="flex items-center gap-3">
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NPSN..." class="bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 w-full md:w-64 transition-all">
                </div>
                <button type="submit" class="p-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-all flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Identitas Sekolah</th>
                        <th class="px-6 py-4">Data SDM</th>
                        <th class="px-6 py-4">Lokasi</th>
                        <th class="px-6 py-4">Kepala Sekolah</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($schools as $school)
                    <tr class="hover:bg-slate-50/50 transition-all duration-200 group animate-slide-in-left">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl flex items-center justify-center font-bold text-slate-400 text-lg border-2 border-white shadow-lg shrink-0 group-hover:scale-110 transition-transform">
                                    @if($school->logo)
                                        <img src="{{ tenant_asset('storage/' . $school->logo) }}" class="w-full h-full object-cover rounded-xl">
                                    @else
                                        {{ substr($school->name, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-primary-50 to-primary-100 text-primary-600 rounded-lg text-xs font-bold mb-2 shadow-sm">{{ $school->level ?? $school->jenjang }}</div>
                                    <p class="font-bold text-slate-900 group-hover:text-primary-600 transition-colors text-lg">{{ $school->name }}</p>
                                    <p class="text-xs text-slate-500 mt-1 font-medium flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        NPSN: {{ $school->npsn ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-2">
                                <div class="flex -space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 border-2 border-white flex items-center justify-center text-[10px] font-bold text-blue-600 shadow-sm" title="Guru">G</div>
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-200 border-2 border-white flex items-center justify-center text-[10px] font-bold text-emerald-600 shadow-sm" title="Siswa">S</div>
                                </div>
                                <div class="bg-slate-50 rounded-lg px-3 py-1.5">
                                    <p class="text-xs font-bold text-slate-700">{{ $school->teachers_count }} <span class="text-slate-500">Guru</span></p>
                                    <p class="text-xs font-bold text-slate-700">{{ $school->students_count }} <span class="text-slate-500">Siswa</span></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="bg-gradient-to-r from-slate-50 to-slate-100 rounded-lg p-3">
                                <p class="text-sm font-bold text-slate-700 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $school->city ?? '-' }}
                                </p>
                                <p class="text-xs text-slate-500 font-medium mt-1">{{ $school->province ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-50 to-amber-100 text-amber-600 flex items-center justify-center text-xs font-bold shadow-sm">{{ substr($school->principal_name ?? '?', 0, 1) }}</div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700">{{ Str::limit($school->principal_name, 15) ?? '-' }}</p>
                                    <p class="text-xs text-slate-500">Kepala Sekolah</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusMap = [
                                    'draft' => ['bg-gradient-to-r from-slate-100 to-slate-200', 'text-slate-700', 'border-slate-300', 'Draft'],
                                    'setup' => ['bg-gradient-to-r from-amber-100 to-amber-200', 'text-amber-700', 'border-amber-300', 'Setup'],
                                    'active' => ['bg-gradient-to-r from-emerald-100 to-emerald-200', 'text-emerald-700', 'border-emerald-300', 'Aktif'],
                                    'nonactive' => ['bg-gradient-to-r from-rose-100 to-rose-200', 'text-rose-700', 'border-rose-300', 'Nonaktif'],
                                ];
                                $st = $statusMap[$school->status] ?? ['bg-gradient-to-r from-slate-100 to-slate-200', 'text-slate-700', 'border-slate-300', $school->status];
                            @endphp
                            <div class="inline-flex items-center gap-2 px-4 py-2 {{ $st[0] }} {{ $st[1] }} border {{ $st[2] }} rounded-full text-xs font-bold shadow-sm">
                                <div class="w-2 h-2 rounded-full bg-current animate-pulse"></div>
                                {{ $st[2] }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($school->status !== 'active')
                                    <form action="{{ route('tenant.units.activate', $school) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="group px-4 py-2 bg-gradient-to-r from-emerald-50 to-emerald-100 text-emerald-600 text-xs font-bold rounded-lg hover:from-emerald-100 hover:to-emerald-200 transition-all shadow-sm hover:shadow-md flex items-center gap-2">
                                            <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Aktivasi
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('tenant.school.dashboard', ['school' => $school->slug ?? $school->id]) }}" 
                                       class="group px-4 py-2 bg-gradient-to-r from-slate-900 to-slate-800 text-white text-xs font-bold rounded-lg hover:from-slate-800 hover:to-slate-700 transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                        </svg>
                                        Masuk Unit
                                    </a>
                                @endif
                                
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" @click.away="open = false" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                        </svg>
                                    </button>
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         class="absolute right-0 mt-2 w-52 glass-effect rounded-xl shadow-2xl z-20 overflow-hidden py-2">
                                        <a href="{{ route('tenant.units.edit', $school) }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors group">
                                            <svg class="w-4 h-4 text-slate-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            <span class="font-medium">Edit Profil</span>
                                        </a>
                                        @if($school->status === 'active')
                                        <form action="{{ route('tenant.units.deactivate', $school) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-3 text-sm text-rose-600 hover:bg-rose-50 transition-colors group">
                                                <svg class="w-4 h-4 text-rose-400 group-hover:text-rose-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                                <span class="font-medium">Nonaktifkan</span>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center animate-fade-in-up">
                                <div class="w-20 h-20 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full flex items-center justify-center text-slate-300 mb-6 shadow-lg">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-slate-900 mb-2">Belum ada Unit Sekolah</h4>
                                <p class="text-sm text-slate-500 mt-2 max-w-md mb-8">Daftarkan unit sekolah pertama Anda untuk mulai mengelola ekosistem akademik yang lengkap dan terintegrasi.</p>
                                <a href="{{ route('tenant.units.create') }}" class="group px-8 py-3.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-sm font-bold rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all shadow-lg hover:shadow-xl flex items-center gap-3 transform hover:scale-105">
                                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/>
                                    </svg>
                                    Tambah Unit Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t border-slate-100 bg-gradient-to-r from-slate-50 to-slate-100 text-center">
            <div class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H6a2 2 0 100 4h2a2 2 0 100-4h2a1 1 0 100-2 2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2H6z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-bold text-slate-600">Menampilkan {{ $schools->count() }} unit sekolah</span>
            </div>
        </div>
    </div>
</div>
@endsection
