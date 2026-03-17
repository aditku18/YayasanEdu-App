@extends('layouts.dashboard')

@section('title', 'Atur Rincian Biaya — ' . $wave->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ $schoolSlug ? route('tenant.school.ppdb.settings', ['school' => $schoolSlug]) : route('tenant.ppdb.settings') }}" class="p-2 bg-white rounded-xl border border-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Rincian Biaya: {{ $wave->name }}</h1>
            <p class="text-slate-500 mt-1">Tentukan nominal untuk setiap komponen biaya pada gelombang ini.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl font-bold flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Master Komponen Biaya --}}
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 premium-shadow">
                <h3 class="font-bold text-slate-900 mb-4">Tambah Komponen</h3>
                <form action="{{ $schoolSlug ? route('tenant.school.ppdb.fee-components.store', ['school' => $schoolSlug]) : route('tenant.ppdb.fee-components.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Komponen</label>
                        <input type="text" name="name" placeholder="Misal: Uang Gedung" required class="w-full bg-slate-50 border-none rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                    <button type="submit" class="w-full py-2 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all text-sm">
                        + Tambah Master
                    </button>
                </form>
            </div>

            <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-200 border-dashed">
                <h3 class="font-bold text-slate-400 text-xs uppercase tracking-widest mb-4 text-center">Master Tersedia</h3>
                <div class="space-y-2">
                    @forelse($components as $comp)
                    <div class="flex items-center justify-between px-4 py-2 bg-white rounded-lg border border-slate-100 group/item transition-all hover:border-red-100">
                        <span class="text-sm font-bold text-slate-600">{{ $comp->name }}</span>
                        <form action="{{ route('tenant.ppdb.fee-components.destroy', $comp->id) }}" method="POST" onsubmit="return confirm('Hapus komponen ini? Semua rincian nominal di tiap gelombang juga akan terhapus.')" class="opacity-0 group-hover/item:opacity-100 transition-opacity">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                    @empty
                    <p class="text-[10px] text-slate-400 text-center italic">Belum ada komponen.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Form Nominal Per Gelombang --}}
        <div class="md:col-span-2" x-data="{ activeTab: 'all' }">
            <form action="{{ $schoolSlug ? route('tenant.school.ppdb.waves.fees.update', ['school' => $schoolSlug, 'wave' => $wave->id]) : route('tenant.ppdb.waves.fees.update', $wave->id) }}" method="POST" class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
                @csrf
                <div class="px-8 pt-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                    <h3 class="font-bold text-slate-900">Setting Nominal Gelombang</h3>
                    <span class="px-3 py-1 bg-primary-50 text-primary-600 rounded-full text-[10px] font-bold uppercase tracking-widest">Wajib Diisi</span>
                </div>

                {{-- Tab Navigation --}}
                <div class="px-8 bg-slate-50/30 border-b border-slate-50 flex items-center gap-1 overflow-x-auto no-scrollbar">
                    <button type="button" 
                            @click="activeTab = 'all'"
                            :class="activeTab === 'all' ? 'border-primary-500 text-primary-600 bg-white' : 'border-transparent text-slate-400 hover:text-slate-600'"
                            class="px-6 py-4 text-xs font-black uppercase tracking-widest border-b-2 transition-all whitespace-nowrap">
                        Umum (Semua Jurusan)
                    </button>
                    @foreach($majors as $major)
                    <button type="button" 
                            @click="activeTab = '{{ $major->id }}'"
                            :class="activeTab === '{{ $major->id }}' ? 'border-primary-500 text-primary-600 bg-white' : 'border-transparent text-slate-400 hover:text-slate-600'"
                            class="px-6 py-4 text-xs font-black uppercase tracking-widest border-b-2 transition-all whitespace-nowrap">
                        {{ $major->name }}
                    </button>
                    @endforeach
                </div>
                
                <div class="p-8 space-y-6">
                    {{-- Common Fees --}}
                    <div x-show="activeTab === 'all'" class="space-y-6 animate-in fade-in duration-300">
                        <div class="p-4 bg-primary-50/50 rounded-2xl border border-primary-100 mb-8">
                            <p class="text-[10px] font-bold text-primary-600 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                INFO
                            </p>
                            <p class="text-[11px] text-primary-700 font-medium mt-1">Biaya di tab ini akan dibebankan kepada **SELURUH** pendaftar di gelombang ini.</p>
                        </div>
                        @forelse($components as $comp)
                        @php
                            $waveFee = $wave->fees->where('ppdb_fee_component_id', $comp->id)->where('major_id', null)->first();
                        @endphp
                        <div class="flex items-center justify-between gap-6">
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-900">{{ $comp->name }}</h4>
                                <p class="text-[10px] text-slate-400">Berlaku untuk semua jurusan.</p>
                            </div>
                            <div class="w-48 relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">Rp</span>
                                <input type="number" 
                                       name="fees[all][{{ $comp->id }}]" 
                                       value="{{ $waveFee ? (int)$waveFee->amount : '' }}" 
                                       placeholder="0"
                                       class="w-full bg-slate-50 border-none rounded-xl pl-10 pr-4 py-3 text-right font-bold text-slate-900 focus:ring-2 focus:ring-primary-500 transition-all">
                            </div>
                        </div>
                        @empty
                        <div class="py-12 text-center">
                            <p class="text-slate-400 font-medium italic">Silakan tambah master komponen biaya terlebih dahulu di sebelah kiri.</p>
                        </div>
                        @endforelse
                    </div>

                    {{-- Major Specific Fees --}}
                    @foreach($majors as $major)
                    <div x-show="activeTab === '{{ $major->id }}'" class="space-y-6 animate-in fade-in duration-300" style="display: none;">
                        <div class="p-4 bg-amber-50/50 rounded-2xl border border-amber-100 mb-8">
                            <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                KHUSUS: {{ $major->name }}
                            </p>
                            <p class="text-[11px] text-amber-700 font-medium mt-1">Biaya di tab ini **HANYA** akan ditambahkan untuk pendaftar yang memilih jurusan **{{ $major->name }}**.</p>
                        </div>
                        @foreach($components as $comp)
                        @php
                            $waveFee = $wave->fees->where('ppdb_fee_component_id', $comp->id)->where('major_id', $major->id)->first();
                        @endphp
                        <div class="flex items-center justify-between gap-6">
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-900">{{ $comp->name }}</h4>
                                <p class="text-[10px] text-slate-400">Tambahan khusus {{ $major->name }}.</p>
                            </div>
                            <div class="w-48 relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">Rp</span>
                                <input type="number" 
                                       name="fees[{{ $major->id }}][{{ $comp->id }}]" 
                                       value="{{ $waveFee ? (int)$waveFee->amount : '' }}" 
                                       placeholder="-"
                                       class="w-full bg-slate-50 border-none rounded-xl pl-10 pr-4 py-3 text-right font-bold text-slate-900 focus:ring-2 focus:ring-primary-500 transition-all">
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>

                <div class="p-8 bg-slate-50/50 border-t border-slate-50 flex justify-end">
                    <button type="submit" @if($components->isEmpty()) disabled @endif class="px-8 py-3 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-100 disabled:opacity-50">
                        Simpan Perubahan Biaya
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
