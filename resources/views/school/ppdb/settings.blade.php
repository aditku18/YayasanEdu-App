@extends('layouts.dashboard')

@section('title', 'Pengaturan Gelombang PPDB')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ $schoolSlug ? route('tenant.school.ppdb.index', ['school' => $schoolSlug]) : route('tenant.ppdb.index') }}" class="p-2 bg-white rounded-xl border border-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pengaturan Gelombang</h1>
            <p class="text-slate-500 mt-1">Kelola periode pendaftaran siswa baru untuk tahun ajaran aktif.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl text-sm font-bold flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-700 px-6 py-4 rounded-2xl text-sm font-bold flex items-center gap-3">
        <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
    @endif

    @if($activeWaves->count() > 0)
    <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
            <h3 class="font-bold text-slate-900">Daftar Gelombang</h3>
            <a href="{{ $schoolSlug ? route('tenant.school.ppdb.waves.create', ['school' => $schoolSlug]) : route('tenant.ppdb.waves.create') }}" class="px-5 py-2 bg-primary-600 text-white font-bold text-sm rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Gelombang
            </a>
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Gelombang</th>
                    <th class="px-4 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Pendaftar / Kuota</th>
                    <th class="px-4 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Biaya</th>
                    <th class="px-4 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Periode</th>
                    <th class="px-4 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($activeWaves as $wave)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-8 py-4">
                        <div class="font-bold text-slate-900">{{ $wave->name }}</div>
                        @if($wave->major_id)
                            <span class="inline-block mt-1 px-2 py-0.5 bg-amber-50 text-amber-600 rounded-lg text-[9px] uppercase tracking-widest font-black">Khusus {{ $wave->major->name ?? 'Jurusan' }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-center">
                        @if($wave->quota)
                            @php
                                $pct = $wave->quota > 0 ? min(100, round(($wave->applicants_count / $wave->quota) * 100)) : 0;
                                $colorClass = $pct >= 100 ? 'bg-rose-500' : ($pct >= 75 ? 'bg-amber-500' : 'bg-emerald-500');
                            @endphp
                            <div class="font-bold text-sm text-slate-700">{{ $wave->applicants_count }} / {{ $wave->quota }}</div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-1.5">
                                <div class="{{ $colorClass }} h-1.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                            </div>
                            @if($pct >= 100)
                                <span class="text-[9px] font-black text-rose-500 uppercase tracking-widest mt-1 inline-block">Penuh</span>
                            @else
                                <span class="text-[9px] text-slate-400 font-medium mt-1 inline-block">Sisa {{ max(0, $wave->quota - $wave->applicants_count) }}</span>
                            @endif
                        @else
                            <div class="font-bold text-sm text-slate-700">{{ $wave->applicants_count }}</div>
                            <span class="text-[9px] text-slate-400 font-medium">Tanpa Batas</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-sm font-bold text-slate-700 text-right">Rp {{ number_format($wave->registration_fee, 0, ',', '.') }}</td>
                    <td class="px-4 py-4 text-sm text-slate-500 text-center">{{ date('d M Y', strtotime($wave->start_date)) }} — {{ date('d M Y', strtotime($wave->end_date)) }}</td>
                    <td class="px-4 py-4 text-center">
                        <form action="{{ $schoolSlug ? route('tenant.school.ppdb.waves.toggle-status', ['school' => $schoolSlug, 'wave' => $wave->id]) : route('tenant.ppdb.waves.toggle-status', $wave->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" title="Klik untuk toggle status" class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest cursor-pointer hover:shadow-md transition-all {{ $wave->status === 'active' ? 'bg-green-50 text-green-600 hover:bg-green-100' : 'bg-slate-100 text-slate-400 hover:bg-slate-200' }}">
                                {{ $wave->status === 'active' ? 'Aktif' : 'Ditutup' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-8 py-4 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ $schoolSlug ? route('tenant.school.ppdb.waves.fees', ['school' => $schoolSlug, 'wave' => $wave->id]) : route('tenant.ppdb.waves.fees', $wave->id) }}" title="Atur Biaya" class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </a>
                            <a href="{{ $schoolSlug ? route('tenant.school.ppdb.waves.edit', ['school' => $schoolSlug, 'wave' => $wave->id]) : route('tenant.ppdb.waves.edit', $wave->id) }}" title="Edit Gelombang" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ $schoolSlug ? route('tenant.school.ppdb.waves.destroy', ['school' => $schoolSlug, 'wave' => $wave->id]) : route('tenant.ppdb.waves.destroy', $wave->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus gelombang ini? Gelombang yang sudah memiliki pendaftar tidak dapat dihapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus Gelombang" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="bg-white p-12 rounded-[3rem] border border-slate-100 premium-shadow text-center">
        <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-900">Belum Ada Gelombang</h3>
        <p class="text-slate-500 mt-2 max-w-xs mx-auto">Silakan tambahkan gelombang pendaftaran pertama Anda untuk mulai menerima calon siswa baru.</p>
        <a href="{{ $schoolSlug ? route('tenant.school.ppdb.waves.create', ['school' => $schoolSlug]) : route('tenant.ppdb.waves.create') }}" class="inline-block mt-8 px-8 py-3 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">
            + Tambah Gelombang Baru
        </a>
    </div>
    @endif
</div>
@endsection
