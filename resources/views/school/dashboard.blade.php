@extends('layouts.tenant-platform')

@section('title', 'Dashboard Unit - ' . $school->name)

@section('content')
<div class="space-y-8 pb-12">
    <!-- Unit Header -->
    <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-8">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 bg-primary-50 rounded-3xl flex items-center justify-center text-primary-600 border border-primary-100 overflow-hidden shadow-inner">
                @if($school->logo)
                    <img src="{{ tenant_asset('storage/' . $school->logo) }}" class="w-full h-full object-cover">
                @else
                    <span class="text-3xl font-black">{{ substr($school->name, 0, 1) }}</span>
                @endif
            </div>
            <div>
                <div class="inline-flex items-center px-2.5 py-1 bg-primary-100 text-primary-700 rounded-lg text-[10px] font-bold uppercase tracking-widest mb-2">Unit {{ $school->level }}</div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $school->name }}</h1>
                <p class="text-slate-500 mt-1 font-medium">{{ $school->city }}, {{ $school->province }} • NPSN: {{ $school->npsn ?? '-' }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-2xl shrink-0">
             <div class="px-6 py-2 text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status Unit</p>
                <div class="flex items-center gap-2 text-emerald-600 font-bold mt-1">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    Aktif
                </div>
             </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group hover:border-primary-200 transition-all">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-500/5 rounded-full group-hover:scale-150 transition-transform"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Peserta Didik</p>
                    <h3 class="text-4xl font-black text-slate-900">{{ $stats['total_students'] }}</h3>
                    <p class="text-xs text-blue-600 font-bold mt-2 flex items-center gap-1 leading-none">
                        Terdaftar di sistem
                    </p>
                </div>
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group hover:border-primary-200 transition-all">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/5 rounded-full group-hover:scale-150 transition-transform"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Tenaga Pendidik</p>
                    <h3 class="text-4xl font-black text-slate-900">{{ $stats['total_teachers'] }}</h3>
                    <p class="text-xs text-emerald-600 font-bold mt-2 flex items-center gap-1">
                         Aktif mengajar
                    </p>
                </div>
                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group hover:border-primary-200 transition-all">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-500/5 rounded-full group-hover:scale-150 transition-transform"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Rombongan Belajar</p>
                    <h3 class="text-4xl font-black text-slate-900">{{ $stats['total_classes'] }}</h3>
                    <p class="text-xs text-amber-600 font-bold mt-2 flex items-center gap-1">
                        Kelas aktif
                    </p>
                </div>
                <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Plugins Widget -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Plugin Aktif</h3>
                <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-widest">Fitur yang tersedia</p>
            </div>
            <a href="{{ route('tenant.plugin.active') }}" class="text-primary-600 font-bold text-sm hover:underline transition-all">Kelola Plugin</a>
        </div>
        <div class="p-8">
            @php
                $foundationId = \App\Models\Foundation::where('tenant_id', tenant('id'))->first()?->id ?? 1;
                $activePlugins = \App\Models\PluginInstallation::where('foundation_id', $foundationId)
                    ->where('is_active', true)
                    ->with('plugin')
                    ->get();
            @endphp
            
            @if($activePlugins->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($activePlugins as $installation)
                        <div class="flex items-center gap-4 p-4 bg-emerald-50 rounded-2xl border border-emerald-200">
                            <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center text-white font-bold">
                                {{ substr($installation->plugin->name, 0, 2) }}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-900">{{ $installation->plugin->name }}</h4>
                                <p class="text-sm text-emerald-700">Aktif</p>
                            </div>
                            <a href="{{ route('tenant.plugin.show', $installation->plugin->id) }}" class="p-2 text-emerald-600 hover:bg-emerald-100 rounded-xl transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Plugin Aktif</h4>
                    <p class="text-slate-500 mb-4">Unit sekolah ini belum memiliki plugin yang aktif.</p>
                    <a href="{{ route('tenant.marketplace.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white font-bold rounded-2xl shadow-lg shadow-primary-200 hover:bg-primary-700 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Beli Plugin
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Features Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Aktivitas Terbaru</h3>
                    <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-widest">Update unit sekolah</p>
                </div>
                <button class="text-primary-600 font-bold text-sm hover:underline transition-all">Lihat Semua</button>
            </div>
            <div class="p-8 space-y-6 flex-1">
                <div class="flex gap-4">
                    <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-700 leading-snug">Sistem Unit berhasil diaktifkan oleh Yayasan.</p>
                        <p class="text-[10px] text-slate-400 font-bold mt-1">Baru saja</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-slate-200">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500/10 rounded-full blur-3xl"></div>
            <h3 class="text-xl font-bold mb-2">Butuh Bantuan?</h3>
            <p class="text-slate-400 text-sm leading-relaxed mb-8">Pusat bantuan kami tersedia jika Anda memiliki kendala teknis atau pertanyaan seputar penggunaan fitur di unit sekolah.</p>
            <div class="space-y-4">
                <a href="#" class="flex items-center justify-between p-5 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center font-bold">📖</div>
                        <span class="font-bold text-sm">Panduan Pengguna</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="#" class="flex items-center justify-between p-5 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center font-bold">💬</div>
                        <span class="font-bold text-sm">WhatsApp Support</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
