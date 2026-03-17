@extends('layouts.ppdb')

@section('title', 'Lengkapi Berkas Pendaftaran')

@section('content')
<div class="max-w-4xl mx-auto py-12 space-y-12">
    {{-- Header Info --}}
    <div class="flex items-center justify-between gap-8 flex-wrap">
        <div class="space-y-2">
            <h1 class="text-3xl font-display font-black text-slate-900 leading-tight">
                @if($applicant->status == 'pending')
                    Lengkapi Berkas Pendaftaran
                @elseif($applicant->status == 'verified')
                    Berkas Sudah Diverifikasi
                @elseif($applicant->status == 'accepted')
                    Selamat, Anda Diterima!
                @elseif($applicant->status == 'rejected')
                    Status Pendaftaran
                @elseif($applicant->status == 'enrolled')
                    Anda Sudah Terdaftar!
                @endif
            </h1>
            <p class="text-slate-500 font-medium">
                @if($applicant->status == 'pending')
                    Silakan unggah pindaian dokumen asli Anda untuk keperluan verifikasi.
                @elseif($applicant->status == 'verified')
                    Dokumen Anda sedang ditinjau oleh panitia seleksi.
                @elseif($applicant->status == 'accepted')
                    Silakan lakukan pelunasan biaya pendidikan untuk mengamankan kursi Anda.
                @elseif($applicant->status == 'rejected')
                    Mohon maaf, pendaftaran Anda tidak dapat dilanjutkan.
                @elseif($applicant->status == 'enrolled')
                    Proses pendaftaran Anda telah selesai sepenuhnya.
                @endif
            </p>
        </div>
        <div class="px-6 py-4 bg-primary-600 text-white rounded-[2rem] shadow-xl shadow-primary-600/20">
            <p class="text-[10px] font-black uppercase tracking-widest opacity-80">Nomor Pendaftaran</p>
            <p class="text-2xl font-black font-display tracking-tight">{{ $applicant->registration_number }}</p>
        </div>
    </div>

    {{-- New: always show tagihan jika belum lunas --}}
    @if(in_array($applicant->payment_status, ['unpaid', 'partial']))
        <div class="mt-6 bg-slate-50 rounded-[2rem] p-6 border border-slate-200">
            <h2 class="text-lg font-black text-slate-900 mb-4">Rincian Tagihan</h2>
            <div class="space-y-2">
                @php
                    $filteredFees = $applicant->wave->fees->filter(function($fee) use ($applicant) {
                        return is_null($fee->major_id) || $fee->major_id == $applicant->major_id;
                    });
                    $total = $applicant->fee_sub_total ?? $filteredFees->sum('amount');
                @endphp

                @foreach($filteredFees as $fee)
                    <div class="flex justify-between">
                        <span class="text-slate-700">{{ $fee->component->name }}@if($fee->major_id)<span class="ml-1 text-xs text-amber-600">(Jurusan)</span>@endif</span>
                        <span class="font-black">Rp {{ number_format($fee->amount,0,',','.') }}</span>
                    </div>
                @endforeach

                <div class="flex justify-between pt-4 border-t border-slate-200">
                    <span class="font-black">Total Tagihan</span>
                    <span class="font-black">Rp {{ number_format($total,0,',','.') }}</span>
                </div>
                <div class="flex justify-between pt-2 italic text-sm text-slate-500">
                    <span>Minimal Pembayaran (50%)</span>
                    <span>Rp {{ number_format(($applicant->fee_minimum ?? ($total * .5)),0,',','.') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="p-5 bg-green-50 border border-green-100 text-green-700 rounded-3xl font-bold flex items-center gap-4">
            <div class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p>Berhasil!</p>
                <p class="text-sm font-medium opacity-80">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-5 bg-red-50 border border-red-100 text-red-700 rounded-3xl font-bold space-y-2">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <p>Terjadi Kesalahan!</p>
                    <p class="text-sm font-medium opacity-80">Beberapa berkas gagal diunggah. Silakan cek rincian di bawah:</p>
                </div>
            </div>
            <ul class="text-xs list-disc list-inside ml-14 opacity-80">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ======================== STATUS: REJECTED ======================== --}}
    @if($applicant->status == 'rejected')
    <div class="premium-card rounded-[3rem] overflow-hidden">
        <div class="p-12 bg-white text-center space-y-6">
            <div class="w-20 h-20 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <h2 class="text-2xl font-display font-black text-red-900">Pendaftaran Ditolak</h2>
            <p class="text-slate-500 font-medium max-w-lg mx-auto">Mohon maaf, setelah dilakukan peninjauan oleh panitia seleksi, pendaftaran Anda <strong>tidak dapat dilanjutkan</strong>. Silakan hubungi pihak sekolah untuk informasi lebih lanjut.</p>
            <a href="{{ route('tenant.ppdb.public.index') }}" class="inline-block px-8 py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition-all">
                Kembali ke Portal PPDB
            </a>
        </div>
    </div>

    {{-- ======================== STATUS: ENROLLED ======================== --}}
    @elseif($applicant->status == 'enrolled')
    <div class="premium-card rounded-[3rem] overflow-hidden">
        <div class="p-12 bg-white text-center space-y-6">
            <div class="w-20 h-20 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="text-2xl font-display font-black text-emerald-900">Selamat! Anda Resmi Terdaftar 🎉</h2>
            <p class="text-slate-500 font-medium max-w-lg mx-auto">Pembayaran daftar ulang Anda telah <strong>dikonfirmasi oleh admin</strong>. Anda kini resmi terdaftar sebagai siswa baru. Silakan tunggu informasi selanjutnya dari pihak sekolah.</p>
            <div class="inline-flex items-center gap-2 px-5 py-2 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 text-sm font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Status Pembayaran: LUNAS
            </div>
        </div>
    </div>

    {{-- ======================== STATUS: VERIFIED ======================== --}}
    @elseif($applicant->status == 'verified')
    <div class="premium-card rounded-[3rem] overflow-hidden">
        <div class="p-12 bg-white space-y-8">
            <div class="p-8 bg-blue-50 rounded-[2.5rem] border border-blue-100 flex items-start gap-6">
                <div class="w-16 h-16 bg-blue-500 text-white rounded-3xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-blue-500/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-display font-black text-blue-900 leading-tight">Berkas Telah Diverifikasi ✓</h2>
                    <p class="text-blue-700 font-medium mt-1">Seluruh dokumen pendaftaran Anda telah diperiksa dan dinyatakan <strong>lengkap</strong> oleh admin. Saat ini, berkas Anda sedang dalam <strong>proses seleksi</strong> oleh panitia.</p>
                </div>
            </div>

            <div class="text-center space-y-3">
                <div class="inline-flex items-center gap-3 px-6 py-3 bg-amber-50 text-amber-700 rounded-2xl border border-amber-100">
                    <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span class="text-sm font-bold">Menunggu Hasil Seleksi...</span>
                </div>
                <p class="text-xs text-slate-400 font-medium">Silakan cek kembali halaman ini secara berkala untuk mengetahui hasil seleksi Anda.</p>
            </div>
        </div>
    </div>

    {{-- ======================== STATUS: ACCEPTED (Daftar Ulang) ======================== --}}
    @elseif($applicant->status == 'accepted')
    <div class="premium-card rounded-[3rem] overflow-hidden">
        <form action="{{ route('tenant.ppdb.public.store-docs') }}" method="POST" enctype="multipart/form-data" class="divide-y divide-slate-100">
            @csrf
            <input type="hidden" name="registration_number" value="{{ $applicant->registration_number }}">
            
            <div class="p-10 space-y-10 bg-white">
                <div class="p-8 bg-green-50 rounded-[2.5rem] border border-green-100 flex items-start gap-6">
                    <div class="w-16 h-16 bg-green-500 text-white rounded-3xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-green-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-display font-black text-green-900 leading-tight">Selamat! Anda Dinyatakan Lulus Seleksi</h2>
                        <p class="text-green-700 font-medium mt-1">Silakan lakukan pembayaran biaya pendidikan (Daftar Ulang) untuk mengamankan kursi Anda. <strong>Minimal pembayaran 50%</strong> dari total tagihan.</p>
                    </div>
                </div>

                @if($applicant->payment_status == 'partial')
                <div class="p-5 bg-cyan-50 rounded-2xl border border-cyan-100 flex items-center gap-4">
                    <div class="w-10 h-10 bg-cyan-500 text-white rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-black text-cyan-800">DP 50% Sudah Dikonfirmasi ✓</p>
                        <p class="text-xs font-medium text-cyan-600 mt-0.5">Silakan unggah bukti pelunasan sisa tagihan untuk menyelesaikan proses daftar ulang.</p>
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    {{-- Rincian Biaya --}}
                    <div class="space-y-6">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-xl flex items-center justify-center text-xs">01</span>
                            Rincian Biaya Pendidikan
                        </h3>
                        <div class="bg-slate-50 rounded-[2rem] p-8 space-y-4 border border-slate-100">
                            @php 
                                $subTotal = 0;
                                $filteredFees = $applicant->wave->fees->filter(function($fee) use ($applicant) {
                                    return is_null($fee->major_id) || $fee->major_id == $applicant->major_id;
                                });
                            @endphp
                            @foreach($filteredFees as $fee)
                            <div class="flex justify-between items-center py-2 border-b border-slate-100 last:border-0">
                                <span class="text-slate-500 font-bold">
                                    {{ $fee->component->name }}
                                    @if($fee->major_id)
                                        <span class="ml-2 px-2 py-0.5 bg-amber-50 text-amber-600 rounded-lg text-[9px] uppercase tracking-widest font-black shrink-0">Khusus {{ $applicant->major->name ?? 'Jurusan' }}</span>
                                    @endif
                                </span>
                                <span class="text-slate-900 font-black shrink-0">Rp {{ number_format($fee->amount, 0, ',', '.') }}</span>
                                @php $subTotal += $fee->amount; @endphp
                            </div>
                            @endforeach
                            <div class="pt-4 flex justify-between items-center">
                                <span class="text-lg font-black text-slate-900">Total Tagihan</span>
                                <span class="text-2xl font-display font-black text-primary-600">Rp {{ number_format($subTotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="mt-2 pt-2 border-t border-slate-100 flex justify-between items-center italic">
                                <span class="text-xs font-bold text-slate-400">Minimal Pembayaran (50%)</span>
                                <span class="text-sm font-black text-slate-500">Rp {{ number_format($subTotal * 0.5, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <div class="p-6 bg-amber-50 rounded-2xl border border-amber-100">
                            <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-2">Instruksi Pembayaran</p>
                            <p class="text-xs font-bold text-amber-700 leading-relaxed">Silakan transfer total tagihan ke Rekening Bank BRI: <span class="text-slate-900">0123-4567-8901 (YAYASAN PELITA HATI)</span>. Gunakan nomor pendaftaran sebagai berita transfer. <br><br> <span class="text-amber-900 underline font-black">Catatan: Minimal pembayaran adalah 50% dari total tagihan (Rp {{ number_format($subTotal * 0.5, 0, ',', '.') }}) untuk divalidasi sebagai siswa aktif.</span></p>
                        </div>
                    </div>

                    {{-- Unggah Bukti Pembayaran --}}
                    <div x-data="{ fileName: '' }" class="space-y-6">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-xl flex items-center justify-center text-xs">02</span>
                            {{ $applicant->payment_status == 'partial' ? 'Unggah Bukti Pelunasan Sisa' : 'Unggah Bukti Pembayaran' }}
                        </h3>
                        <div class="relative group">
                            <input type="file" name="final_payment_proof" @change="fileName = $event.target.files[0].name" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                            <div class="w-full h-full min-h-[250px] border-2 border-dashed {{ $applicant->final_payment_proof ? 'border-green-200 bg-green-50/30' : 'border-slate-200 bg-white shadow-sm' }} rounded-[2.5rem] flex flex-col items-center justify-center gap-4 transition-all group-hover:border-primary-300 group-hover:bg-primary-50/30"
                                 :class="fileName ? 'border-primary-500 bg-primary-50' : ''">
                                
                                @if($applicant->final_payment_proof)
                                <div class="p-4 bg-green-100 text-green-600 rounded-full">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <p class="text-xs font-black text-green-700 tracking-tight">Bukti Pembayaran Berhasil Diunggah!</p>
                                <a href="{{ tenant_asset($applicant->final_payment_proof) }}" target="_blank" class="text-[10px] font-bold text-primary-600 hover:underline">Lihat Bukti Terunggah</a>
                                <p class="text-[10px] text-slate-400 font-medium">Klik area ini untuk mengganti dengan bukti baru</p>
                                @else
                                <div class="p-4 bg-slate-100 text-slate-400 group-hover:bg-primary-100 group-hover:text-primary-600 transition-colors">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm1-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <p class="text-xs font-bold text-slate-400 group-hover:text-primary-600 text-center px-8" x-text="fileName ? 'File terpilih: ' + fileName : 'Klik atau seret struk transfer pembayaran (Min 50% / Max 2MB)'"></p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-10 bg-slate-50 flex flex-wrap gap-4 items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center flex-shrink-0 animate-pulse">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <p class="text-xs font-bold text-slate-500 max-w-sm">Segera selesaikan pembayaran untuk mengunci status siswa baru Anda.</p>
                </div>
                <button type="submit" class="px-10 py-5 bg-slate-900 text-white font-black rounded-2xl hover:bg-primary-600 hover:shadow-2xl hover:shadow-primary-600/30 transition-all flex items-center gap-3">
                    Kirim Bukti Daftar Ulang
                    <svg class="w-5 h-5 font-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
            </div>
        </form>
    </div>

    {{-- ======================== STATUS: PENDING (Upload Berkas) ======================== --}}
    @else
    <div class="premium-card rounded-[3rem] overflow-hidden">
        <form action="{{ route('tenant.ppdb.public.store-docs') }}" method="POST" enctype="multipart/form-data" class="divide-y divide-slate-100">
            @csrf
            <input type="hidden" name="registration_number" value="{{ $applicant->registration_number }}">
            
            <div class="p-10 space-y-10 bg-white">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    {{-- Kartu Keluarga --}}
                    <div x-data="{ fileName: '' }" class="space-y-4 group">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest">1. Kartu Keluarga (KK)</label>
                            @if($applicant->document_kk)
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 bg-green-100 text-green-600 rounded-full text-[10px] font-black uppercase tracking-tighter">Ok</span>
                                    <a href="{{ tenant_asset($applicant->document_kk) }}" target="_blank" class="text-[10px] font-bold text-primary-600 hover:underline">Lihat Berkas</a>
                                </div>
                            @endif
                        </div>
                        <div class="relative group-hover:scale-[1.02] transition-transform">
                            <input type="file" name="document_kk" @change="fileName = $event.target.files[0].name" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full py-8 border-2 border-dashed {{ $applicant->document_kk ? 'border-green-200 bg-green-50/30' : 'border-slate-200 bg-slate-50' }} rounded-3xl flex flex-col items-center justify-center gap-3 group-hover:bg-primary-50 group-hover:border-primary-200 transition-colors"
                                :class="fileName ? 'border-primary-300 bg-primary-50/50' : ''">
                                <svg class="w-8 h-8 {{ $applicant->document_kk ? 'text-green-400' : 'text-slate-300' }} group-hover:text-primary-400" 
                                    :class="fileName ? 'text-primary-500' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <p class="text-[10px] font-bold text-slate-400 group-hover:text-primary-600 px-4 text-center"
                                    x-text="fileName ? 'File terpilih: ' + fileName : '{{ $applicant->document_kk ? 'Klik untuk ganti file' : 'Klik atau seret file PDF/Gambar (Max 2MB)' }}'"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Akta Kelahiran --}}
                    <div x-data="{ fileName: '' }" class="space-y-4 group">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest">2. Akta Kelahiran</label>
                            @if($applicant->document_akta)
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 bg-green-100 text-green-600 rounded-full text-[10px] font-black uppercase tracking-tighter">Ok</span>
                                    <a href="{{ tenant_asset($applicant->document_akta) }}" target="_blank" class="text-[10px] font-bold text-primary-600 hover:underline">Lihat Berkas</a>
                                </div>
                            @endif
                        </div>
                        <div class="relative group-hover:scale-[1.02] transition-transform">
                            <input type="file" name="document_akta" @change="fileName = $event.target.files[0].name" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full py-8 border-2 border-dashed {{ $applicant->document_akta ? 'border-green-200 bg-green-50/30' : 'border-slate-200 bg-slate-50' }} rounded-3xl flex flex-col items-center justify-center gap-3 group-hover:bg-primary-50 group-hover:border-primary-200 transition-colors"
                                :class="fileName ? 'border-primary-300 bg-primary-50/50' : ''">
                                <svg class="w-8 h-8 {{ $applicant->document_akta ? 'text-green-400' : 'text-slate-300' }} group-hover:text-primary-400" 
                                    :class="fileName ? 'text-primary-500' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <p class="text-[10px] font-bold text-slate-400 group-hover:text-primary-600 px-4 text-center"
                                    x-text="fileName ? 'File terpilih: ' + fileName : '{{ $applicant->document_akta ? 'Klik untuk ganti file' : 'Klik atau seret file PDF/Gambar (Max 2MB)' }}'"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Ijazah / SKL --}}
                    <div x-data="{ fileName: '' }" class="space-y-4 group">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest">3. Ijazah Terakhir / SKL</label>
                            @if($applicant->document_ijazah)
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 bg-green-100 text-green-600 rounded-full text-[10px] font-black uppercase tracking-tighter">Ok</span>
                                    <a href="{{ tenant_asset($applicant->document_ijazah) }}" target="_blank" class="text-[10px] font-bold text-primary-600 hover:underline">Lihat Berkas</a>
                                </div>
                            @endif
                        </div>
                        <div class="relative group-hover:scale-[1.02] transition-transform">
                            <input type="file" name="document_ijazah" @change="fileName = $event.target.files[0].name" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full py-8 border-2 border-dashed {{ $applicant->document_ijazah ? 'border-green-200 bg-green-50/30' : 'border-slate-200 bg-slate-50' }} rounded-3xl flex flex-col items-center justify-center gap-3 group-hover:bg-primary-50 group-hover:border-primary-200 transition-colors"
                                :class="fileName ? 'border-primary-300 bg-primary-50/50' : ''">
                                <svg class="w-8 h-8 {{ $applicant->document_ijazah ? 'text-green-400' : 'text-slate-300' }} group-hover:text-primary-400" 
                                    :class="fileName ? 'text-primary-500' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <p class="text-[10px] font-bold text-slate-400 group-hover:text-primary-600 px-4 text-center"
                                    x-text="fileName ? 'File terpilih: ' + fileName : '{{ $applicant->document_ijazah ? 'Klik untuk ganti file' : 'Klik atau seret file PDF/Gambar (Max 2MB)' }}'"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Pas Foto --}}
                    <div x-data="{ fileName: '' }" class="space-y-4 group">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest">4. Pas Foto (3x4)</label>
                            @if($applicant->document_foto)
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 bg-green-100 text-green-600 rounded-full text-[10px] font-black uppercase tracking-tighter">Ok</span>
                                    <a href="{{ tenant_asset($applicant->document_foto) }}" target="_blank" class="text-[10px] font-bold text-primary-600 hover:underline">Lihat Berkas</a>
                                </div>
                            @endif
                        </div>
                        <div class="relative group-hover:scale-[1.02] transition-transform">
                            <input type="file" name="document_foto" @change="fileName = $event.target.files[0].name" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full py-8 border-2 border-dashed {{ $applicant->document_foto ? 'border-green-200 bg-green-50/30' : 'border-slate-200 bg-slate-50' }} rounded-3xl flex flex-col items-center justify-center gap-3 group-hover:bg-primary-50 group-hover:border-primary-200 transition-colors"
                                :class="fileName ? 'border-primary-300 bg-primary-50/50' : ''">
                                <svg class="w-8 h-8 {{ $applicant->document_foto ? 'text-green-400' : 'text-slate-300' }} group-hover:text-primary-400" 
                                    :class="fileName ? 'text-primary-500' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <p class="text-[10px] font-bold text-slate-400 group-hover:text-primary-600 px-4 text-center"
                                    x-text="fileName ? 'File terpilih: ' + fileName : '{{ $applicant->document_foto ? 'Klik untuk ganti file' : 'Klik atau seret file Gambar (Max 1MB)' }}'"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Bukti Pembayaran Form --}}
                    <div x-data="{ fileName: '' }" class="space-y-4 group md:col-span-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest">5. Bukti Transfer Uang Formulir</label>
                            @if($applicant->payment_proof)
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 bg-green-100 text-green-600 rounded-full text-[10px] font-black uppercase tracking-tighter">Sudah Unggah</span>
                                    <a href="{{ tenant_asset($applicant->payment_proof) }}" target="_blank" class="text-[10px] font-bold text-primary-600 hover:underline">Lihat Struk</a>
                                </div>
                            @endif
                        </div>
                        <div class="relative group-hover:scale-[1.01] transition-transform">
                            <input type="file" name="payment_proof" @change="fileName = $event.target.files[0].name" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full py-10 border-2 border-dashed {{ $applicant->payment_proof ? 'border-amber-200 bg-amber-50/30' : 'border-slate-200 bg-slate-50' }} rounded-3xl flex flex-col items-center justify-center gap-3 group-hover:bg-amber-50 group-hover:border-amber-300 transition-colors"
                                :class="fileName ? 'border-primary-300 bg-primary-50/50' : ''">
                                <svg class="w-10 h-10 {{ $applicant->payment_proof ? 'text-amber-400' : 'text-slate-300' }} group-hover:text-amber-500" 
                                    :class="fileName ? 'text-primary-500' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <p class="text-[11px] font-black text-slate-500 group-hover:text-amber-600 px-4 text-center tracking-tight"
                                    x-text="fileName ? 'Bukti terpilih: ' + fileName : '{{ $applicant->payment_proof ? 'Klik untuk mengganti Bukti Pembayaran' : 'Klik atau seret struk transfer formulir (Max 2MB)' }}'"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Bukti Pembayaran Daftar Ulang --}}
                    @if($applicant->payment_status != 'paid')
                    <div x-data="{ fileName: '' }" class="space-y-4 group md:col-span-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest">6. Bukti Pembayaran Daftar Ulang</label>
                            @if($applicant->final_payment_proof)
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 bg-green-100 text-green-600 rounded-full text-[10px] font-black uppercase tracking-tighter">Sudah Unggah</span>
                                    <a href="{{ tenant_asset('storage/' . $applicant->final_payment_proof) }}" target="_blank" class="text-[10px] font-bold text-primary-600 hover:underline">Lihat Bukti</a>
                                </div>
                            @endif
                        </div>
                        <div class="relative group-hover:scale-[1.01] transition-transform">
                            <input type="file" name="final_payment_proof" @change="fileName = $event.target.files[0].name" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full py-10 border-2 border-dashed {{ $applicant->final_payment_proof ? 'border-green-200 bg-green-50/30' : 'border-slate-200 bg-slate-50' }} rounded-3xl flex flex-col items-center justify-center gap-3 group-hover:bg-green-50 group-hover:border-green-300 transition-colors"
                                :class="fileName ? 'border-primary-300 bg-primary-50/50' : ''">
                                <svg class="w-10 h-10 {{ $applicant->final_payment_proof ? 'text-green-400' : 'text-slate-300' }} group-hover:text-green-500" 
                                    :class="fileName ? 'text-primary-500' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <p class="text-[11px] font-black text-slate-500 group-hover:text-green-600 px-4 text-center tracking-tight"
                                    x-text="fileName ? 'Bukti terpilih: ' + fileName : '{{ $applicant->final_payment_proof ? 'Klik untuk mengganti Bukti Pembayaran' : 'Klik atau seret bukti pembayaran daftar ulang (Max 2MB)' }}'"></p>
                            </div>
                        </div>
                    </div>
                    @endif

            <div class="p-10 bg-slate-50 flex flex-wrap gap-4 items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center flex-shrink-0 animate-pulse">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <p class="text-xs font-bold text-slate-500 max-w-sm">Pastikan seluruh dokumen terlihat jelas untuk mempercepat verifikasi.</p>
                </div>
                <button type="submit" class="px-10 py-5 bg-slate-900 text-white font-black rounded-2xl hover:bg-primary-600 hover:shadow-2xl hover:shadow-primary-600/30 transition-all flex items-center gap-3">
                    Unggah Seluruh Berkas
                    <svg class="w-5 h-5 font-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection
