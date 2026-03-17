@extends('layouts.ppdb')

@section('title', 'Penerimaan Siswa Baru')

@section('content')
<div class="space-y-24">
    <!-- Hero Section -->
    <div class="relative py-12">
        <div class="text-center space-y-8 max-w-4xl mx-auto relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 rounded-full border border-primary-100 mb-4 floating">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                </span>
                <span class="text-[10px] font-extrabold text-primary-700 uppercase tracking-widest">Penerimaan Siswa Baru 2025/2026</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-display font-extrabold text-slate-900 leading-tight">
                Membentuk <span class="gradient-text">Generasi Unggul</span><br>Mulai Dari Sini
            </h1>
            
            <p class="text-slate-500 text-xl leading-relaxed max-w-2xl mx-auto font-medium">
                Pilih gelombang pendaftaran yang sesuai dan bergabunglah dengan komunitas pembelajar inovatif kami. Proses mandiri, transparan, dan terpercaya.
            </p>

            <div class="flex flex-wrap justify-center gap-4 pt-4">
                <a href="#gelombang" class="px-8 py-4 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 hover:shadow-2xl hover:shadow-primary-600/30 transition-all flex items-center gap-2 group">
                    Daftar Sekarang
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="#" class="px-8 py-4 bg-white text-slate-700 font-bold rounded-2xl border border-slate-200 hover:border-primary-100 hover:bg-primary-50 transition-all">Pelajari Alur</a>
            </div>
        </div>

        <!-- Decorative Elements -->
        <div class="absolute -top-10 -left-10 w-64 h-64 bg-primary-100/30 rounded-full blur-[100px] -z-10"></div>
        <div class="absolute -bottom-10 -right-10 w-96 h-96 bg-accent-100/20 rounded-full blur-[100px] -z-10"></div>
    </div>

    <!-- Active Waves -->
    <div id="gelombang" class="space-y-12">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-2">
                <h2 class="text-3xl font-display font-extrabold text-slate-900">Gelombang Pendaftaran</h2>
                <p class="text-slate-500 font-medium">Buka pintu masa depanmu dengan memilih gelombang yang tersedia.</p>
            </div>
            <div class="px-4 py-2 bg-white rounded-xl border border-slate-100 text-xs font-bold text-slate-400">
                TOTAL: <span class="text-primary-600">{{ count($waves) }}</span> GELOMBANG AKTIF
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @forelse($waves as $wave)
            <div class="relative group">
                <!-- Outer Glow Effect -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary-400 to-primary-600 rounded-[3rem] blur-xl opacity-0 group-hover:opacity-40 transition-opacity duration-500"></div>
                
                <div class="relative bg-white rounded-[3rem] flex flex-col h-full border border-slate-100 overflow-hidden shadow-2xl shadow-slate-200/50 group-hover:-translate-y-2 transition-transform duration-500">
                    <!-- Subtle background decoration inside the card -->
                    <div class="absolute top-0 right-0 w-48 h-48 bg-gradient-to-br from-primary-50 to-primary-100 rounded-bl-[5rem] opacity-50 -z-0"></div>
                    
                    <div class="relative z-10 p-8 space-y-8 flex-1 flex flex-col">
                        <!-- Card Header -->
                        <div class="flex justify-between items-start">
                            <div class="w-14 h-14 bg-gradient-to-br from-white to-slate-50 rounded-2xl flex items-center justify-center text-primary-500 border border-slate-100 shadow-sm group-hover:shadow-md group-hover:scale-110 transition-all duration-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                @if($wave->is_full)
                                    <span class="px-3 py-1 bg-gradient-to-r from-rose-500 to-red-500 text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm shadow-rose-200/50">PENUH</span>
                                @else
                                    <span class="px-3 py-1 bg-gradient-to-r from-emerald-500 to-green-500 text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm shadow-emerald-200/50">TERSEDIA</span>
                                @endif
                                @if($wave->major_id)
                                    <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[9px] uppercase tracking-widest font-black border border-amber-200/50">Khusus {{ $wave->major->name ?? 'Jurusan' }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Core Information -->
                        <div class="space-y-4 flex-1">
                            <h3 class="text-3xl font-display font-black text-slate-800 tracking-tight leading-tight group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-slate-900 group-hover:to-primary-700 transition-all duration-300">
                                {{ $wave->name }}
                            </h3>
                            <div class="flex items-center gap-2 text-slate-500">
                                <span class="p-1.5 bg-slate-50 rounded-lg text-primary-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </span>
                                <span class="text-xs font-bold">{{ date('d M Y', strtotime($wave->start_date)) }} — {{ date('d M Y', strtotime($wave->end_date)) }}</span>
                            </div>

                            {{-- Quota Indicator --}}
                            @if($wave->quota !== null)
                                @php
                                    $remaining = max(0, $wave->quota - $wave->applicants_count);
                                    $pct = $wave->quota > 0 ? min(100, round(($wave->applicants_count / $wave->quota) * 100)) : 0;
                                    $barColor = $pct >= 100 ? 'bg-rose-500' : ($pct >= 75 ? 'bg-amber-500' : 'bg-primary-500');
                                @endphp
                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100/80 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Kuota</span>
                                        @if($wave->is_full)
                                            <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest">Penuh</span>
                                        @else
                                            <span class="text-xs font-black text-primary-600">Sisa {{ $remaining }} kursi</span>
                                        @endif
                                    </div>
                                    <div class="w-full bg-slate-200/60 rounded-full h-2">
                                        <div class="{{ $barColor }} h-2 rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-medium text-right">{{ $wave->applicants_count }} / {{ $wave->quota }} pendaftar</div>
                                </div>
                            @endif
                        </div>

                        <!-- Price Breakdown -->
                        <div class="bg-gradient-to-br from-slate-50 to-slate-100/50 rounded-[2rem] p-6 border border-slate-100/80 mt-auto">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Biaya Formulir</span>
                                <span class="text-sm font-black text-slate-700">Rp {{ number_format($wave->registration_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="pt-5 mt-4 border-t border-slate-200/60">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Tagihan Awal</p>
                                <div class="flex items-end justify-between">
                                    <span class="text-3xl font-black text-slate-900 tracking-tighter group-hover:text-primary-600 transition-colors">
                                        <span class="text-lg text-slate-400 group-hover:text-primary-400">Rp</span> {{ number_format($wave->registration_fee + $wave->fees->sum('amount'), 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- CTA Button -->
                        <div class="pt-2">
                            @if($wave->is_full)
                            <div class="w-full relative overflow-hidden rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center p-5">
                                <span class="text-rose-600 font-black tracking-wider uppercase text-sm">KUOTA PENUH</span>
                            </div>
                            @else
                            <a href="{{ route('tenant.ppdb.public.register', $wave->id) }}" class="w-full relative focus:outline-none overflow-hidden rounded-2xl group/btn block">
                                <!-- Button Background -->
                                <div class="absolute inset-0 bg-slate-900 group-hover/btn:bg-primary-600 transition-colors duration-300"></div>
                                <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-primary-500 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                                
                                <!-- Button Content -->
                                <div class="relative z-10 px-6 py-5 flex items-center justify-center gap-3">
                                    <span class="text-white font-black tracking-wider uppercase text-sm">Ambil Antrian</span>
                                    <svg class="w-5 h-5 text-slate-400 group-hover/btn:text-white group-hover/btn:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </div>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-32 bg-white rounded-[3rem] border border-dashed border-slate-200 text-center space-y-6">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto text-slate-200">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div class="max-w-xs mx-auto">
                    <h3 class="text-xl font-bold text-slate-900">Pendaftaran Belum Dibuka</h3>
                    <p class="text-slate-400 text-sm mt-1">Kami sedang mempersiapkan gelombang pendaftaran terbaik untuk Anda.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Features Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center pt-12">
        <div class="space-y-12">
            <div class="space-y-4">
                <h2 class="text-4xl font-display font-extrabold text-slate-900 leading-tight">Mengapa Memilih<br>Program Kami?</h2>
                <div class="w-20 h-1.5 bg-primary-600 rounded-full"></div>
            </div>

            <div class="space-y-8">
                <div class="flex gap-6 items-start group">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 group-hover:scale-110 transition-transform flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-slate-900">Verifikasi Instan</h4>
                        <p class="text-slate-500 text-sm leading-relaxed mt-1">Status pendaftaran Anda dapat dipantau secara real-time melalui portal mandiri.</p>
                    </div>
                </div>
                <div class="flex gap-6 items-start group">
                    <div class="w-14 h-14 rounded-2xl bg-primary-50 flex items-center justify-center text-primary-500 group-hover:scale-110 transition-transform flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-slate-900">Keamanan Data Terjamin</h4>
                        <p class="text-slate-500 text-sm leading-relaxed mt-1">Seluruh berkas pendaftaran dienkripsi dengan standar keamanan data nasional.</p>
                    </div>
                </div>
                <div class="flex gap-6 items-start group">
                    <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center text-green-500 group-hover:scale-110 transition-transform flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-slate-900">Informasi Transparan</h4>
                        <p class="text-slate-500 text-sm leading-relaxed mt-1">Pemisahan yang jelas antara Uang Formulir dan Biaya Pendaftaran akhir tanpa biaya tersembunyi.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative">
            <div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-[4rem] aspect-square flex items-center justify-center p-12 overflow-hidden premium-shadow">
                <div class="text-center space-y-6 text-white relative z-10 transition-transform hover:scale-105 duration-700 cursor-default">
                    <div class="w-24 h-24 bg-white/20 backdrop-blur-md rounded-3xl flex items-center justify-center mx-auto mb-4 border border-white/20">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-3xl font-display font-black tracking-tight uppercase">Smart PPDB</h3>
                    <p class="text-primary-100 font-medium text-sm">Pendaftaran lebih mudah,<br>kapan saja dan di mana saja.</p>
                </div>
                
                <!-- Decorations in box -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-slate-900/20 rounded-full -ml-20 -mb-20 blur-3xl"></div>
            </div>
        </div>
    </div>
</div>
@endsection
