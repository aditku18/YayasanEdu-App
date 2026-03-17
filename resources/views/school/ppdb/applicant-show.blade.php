@extends('layouts.dashboard')

@section('title', 'Detail Pendaftar — ' . $applicant->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ $schoolSlug ? route('tenant.school.ppdb.applicants', ['school' => $schoolSlug]) : route('tenant.ppdb.applicants') }}" class="p-2 bg-white rounded-xl border border-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div class="flex-1">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $applicant->name }}</h1>
            <p class="text-slate-500 mt-1">Reg: <span class="font-mono font-bold text-slate-700">{{ $applicant->registration_number }}</span></p>
        </div>
        @php
            $statusConfig = [
                'pending'  => ['label' => 'Menunggu',       'bg' => 'bg-amber-50',   'text' => 'text-amber-600',   'border' => 'border-amber-100'],
                'verified' => ['label' => 'Berkas Lengkap', 'bg' => 'bg-blue-50',    'text' => 'text-blue-600',    'border' => 'border-blue-100'],
                'accepted' => ['label' => 'Diterima',       'bg' => 'bg-green-50',   'text' => 'text-green-600',   'border' => 'border-green-100'],
                'rejected' => ['label' => 'Ditolak',        'bg' => 'bg-red-50',     'text' => 'text-red-600',     'border' => 'border-red-100'],
                'enrolled' => ['label' => 'Terdaftar',      'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100'],
            ];
            $s = $statusConfig[$applicant->status] ?? $statusConfig['pending'];
        @endphp
        <span class="px-5 py-2 {{ $s['bg'] }} {{ $s['text'] }} {{ $s['border'] }} border rounded-full text-xs font-black uppercase tracking-widest">{{ $s['label'] }}</span>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl font-bold flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- LEFT: Biodata + Family --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Biodata --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/30">
                    <h3 class="font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Data Pribadi
                    </h3>
                </div>
                <div class="p-8">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-5">
                        @foreach([
                            'Nama Lengkap' => $applicant->name,
                            'NIK' => $applicant->nik ?? '-',
                            'NISN' => $applicant->nisn ?? '-',
                            'Email' => $applicant->email ?? '-',
                            'Telepon' => $applicant->phone ?? '-',
                            'Tempat Lahir' => $applicant->pob ?? '-',
                            'Tanggal Lahir' => $applicant->dob ? \Carbon\Carbon::parse($applicant->dob)->format('d M Y') : '-',
                            'Jenis Kelamin' => ($applicant->gender == 'L' ? 'Laki-laki' : ($applicant->gender == 'P' ? 'Perempuan' : '-')),
                            'Alamat' => $applicant->address ?? '-',
                            'Asal Sekolah' => $applicant->previous_school ?? '-',
                        ] as $label => $value)
                        <div>
                            <dt class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $label }}</dt>
                            <dd class="text-sm font-bold text-slate-900 mt-1">{{ $value }}</dd>
                        </div>
                        @endforeach
                    </dl>
                </div>
            </div>

            {{-- Data Keluarga --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/30">
                    <h3 class="font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Data Keluarga
                    </h3>
                </div>
                <div class="p-8">
                    <dl class="grid grid-cols-1 md:grid-cols-3 gap-x-10 gap-y-5">
                        @foreach([
                            'Nama Ayah' => $applicant->father_name ?? '-',
                            'Nama Ibu' => $applicant->mother_name ?? '-',
                            'Nama Wali' => $applicant->guardian_name ?? '-',
                        ] as $label => $value)
                        <div>
                            <dt class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $label }}</dt>
                            <dd class="text-sm font-bold text-slate-900 mt-1">{{ $value }}</dd>
                        </div>
                        @endforeach
                    </dl>
                </div>
            </div>

            {{-- Dokumen --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/30">
                    <h3 class="font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Dokumen & Berkas
                    </h3>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                        @foreach([
                            'document_kk'          => ['label' => 'Kartu Keluarga',    'icon' => 'KK'],
                            'document_akta'        => ['label' => 'Akta Kelahiran',    'icon' => 'AK'],
                            'document_ijazah'      => ['label' => 'Ijazah / SKL',      'icon' => 'IJ'],
                            'document_foto'        => ['label' => 'Pas Foto',          'icon' => 'FT'],
                            'payment_proof'        => ['label' => 'Bukti Bayar Form',  'icon' => '$$'],
                            'final_payment_proof'  => ['label' => 'Bukti Daftar Ulang','icon' => 'RD'],
                        ] as $field => $meta)
                        <div class="rounded-2xl border {{ $applicant->$field ? 'border-green-200 bg-green-50/30' : 'border-slate-100 bg-slate-50/50' }} p-5 flex flex-col items-center gap-3 transition-all hover:shadow-md">
                            <div class="w-12 h-12 rounded-xl {{ $applicant->$field ? 'bg-green-100 text-green-600' : 'bg-slate-100 text-slate-300' }} flex items-center justify-center font-black text-sm">
                                {{ $meta['icon'] }}
                            </div>
                            <p class="text-[10px] font-bold {{ $applicant->$field ? 'text-green-700' : 'text-slate-400' }} uppercase tracking-widest text-center">{{ $meta['label'] }}</p>
                            @if($applicant->$field)
                                <a href="{{ tenant_asset($applicant->$field) }}" target="_blank" class="px-3 py-1 bg-green-600 text-white rounded-lg text-[10px] font-bold hover:bg-green-700 transition-colors">
                                    Lihat Berkas
                                </a>
                            @else
                                <span class="text-[10px] text-slate-300 font-bold italic">Belum Diunggah</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Status & Action Panel --}}
        <div class="space-y-8">
            {{-- Status & Action Card --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/30">
                    <h3 class="font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Aksi Admin
                    </h3>
                </div>
                <div class="p-8 space-y-5">
                    {{-- Info Cards --}}
                    <div class="space-y-3">
                        <div class="flex justify-between text-xs">
                            <span class="font-bold text-slate-400">Gelombang</span>
                            <span class="font-bold text-slate-900">{{ $applicant->wave->name ?? '-' }}</span>
                        </div>
                        @if($applicant->major)
                        <div class="flex justify-between text-xs">
                            <span class="font-bold text-slate-400">Jurusan</span>
                            <span class="font-bold text-slate-900">{{ $applicant->major->name }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-xs">
                            <span class="font-bold text-slate-400">Total Tagihan</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($applicant->total_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="font-bold text-slate-400">Pembayaran</span>
                            @if($applicant->payment_status == 'paid')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-green-100 text-green-600">Lunas</span>
                            @elseif($applicant->payment_status == 'partial')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-cyan-100 text-cyan-600">DP 50%</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-amber-100 text-amber-600">Belum Bayar</span>
                            @endif
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="font-bold text-slate-400">Tanggal Daftar</span>
                            <span class="font-bold text-slate-900">{{ $applicant->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    {{-- Action Buttons --}}
                    @if($applicant->status == 'pending')
                        <form action="{{ $schoolSlug ? route('tenant.school.ppdb.applicants.status', ['school' => $schoolSlug, 'id' => $applicant->id]) : route('tenant.ppdb.applicants.status', $applicant->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="verified">
                            <button type="submit" class="w-full px-6 py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-600/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Verifikasi Berkas
                            </button>
                        </form>
                    @elseif($applicant->status == 'verified')
                        <div class="space-y-3">
                            <form action="{{ route('tenant.ppdb.applicants.status', $applicant->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="w-full px-6 py-4 bg-green-600 text-white font-bold rounded-2xl hover:bg-green-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-green-600/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Terima Siswa
                                </button>
                            </form>
                            <form action="{{ route('tenant.ppdb.applicants.status', $applicant->id) }}" method="POST" x-data="{ showConfirm: false }">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="button" @click="showConfirm = !showConfirm" class="w-full px-6 py-3 bg-red-50 text-red-600 border border-red-100 font-bold rounded-2xl hover:bg-red-100 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Tolak Pendaftar
                                </button>
                                <div x-show="showConfirm" x-cloak class="mt-3 p-4 bg-red-50 rounded-xl border border-red-100 space-y-3">
                                    <p class="text-xs font-bold text-red-700">Yakin ingin menolak pendaftar ini?</p>
                                    <div class="flex gap-2">
                                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white font-bold rounded-xl text-xs hover:bg-red-700 transition-colors">Ya, Tolak</button>
                                        <button type="button" @click="showConfirm = false" class="flex-1 px-4 py-2 bg-white text-slate-600 font-bold rounded-xl text-xs border border-slate-200 hover:bg-slate-50 transition-colors">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @elseif($applicant->status == 'accepted')
                        @if($applicant->final_payment_proof)
                            <div class="space-y-3">
                                <form action="{{ route('tenant.ppdb.applicants.verify-payment', $applicant->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="payment_type" value="partial">
                                    <button type="submit" class="w-full px-6 py-3 bg-cyan-600 text-white font-bold rounded-2xl hover:bg-cyan-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-cyan-600/20">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Konfirmasi DP 50%
                                    </button>
                                </form>
                                <form action="{{ route('tenant.ppdb.applicants.verify-payment', $applicant->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="payment_type" value="full">
                                    <button type="submit" class="w-full px-6 py-4 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-600/20">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Konfirmasi Lunas (100%)
                                    </button>
                                </form>
                                @if($applicant->payment_status == 'partial')
                                <div class="p-4 bg-cyan-50 rounded-xl border border-cyan-100 text-center">
                                    <p class="text-[10px] font-black text-cyan-700">DP 50% sudah dikonfirmasi. Menunggu pelunasan sisa.</p>
                                </div>
                                @endif
                            </div>
                        @else
                            <div class="p-5 bg-amber-50 rounded-2xl border border-amber-100 text-center">
                                <p class="text-xs font-bold text-amber-700">Menunggu siswa mengunggah bukti pembayaran daftar ulang.</p>
                            </div>
                        @endif
                    @elseif($applicant->status == 'enrolled')
                        <div class="p-5 bg-emerald-50 rounded-2xl border border-emerald-100 text-center space-y-2">
                            <div class="w-12 h-12 bg-emerald-500 text-white rounded-full flex items-center justify-center mx-auto">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-sm font-black text-emerald-700">Siswa Resmi Terdaftar</p>
                            <p class="text-[10px] font-bold text-emerald-500">Pelunasan telah dikonfirmasi</p>
                        </div>
                    @elseif($applicant->status == 'rejected')
                        <div class="p-5 bg-red-50 rounded-2xl border border-red-100 text-center space-y-2">
                            <div class="w-12 h-12 bg-red-500 text-white rounded-full flex items-center justify-center mx-auto">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <p class="text-sm font-black text-red-700">Pendaftar Ditolak</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Timeline --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/30">
                    <h3 class="font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Riwayat Status
                    </h3>
                </div>
                <div class="p-8">
                    <div class="relative pl-8 space-y-6">
                        <div class="absolute left-3 top-2 bottom-2 w-0.5 bg-slate-100"></div>
                        @foreach($timeline as $event)
                        <div class="relative">
                            <div class="absolute -left-5 top-1 w-4 h-4 rounded-full {{ $loop->last ? 'bg-primary-500 ring-4 ring-primary-100' : 'bg-slate-300' }}"></div>
                            <div>
                                <p class="text-xs font-black text-slate-900">{{ $event['status'] }}</p>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $event['description'] }}</p>
                                <p class="text-[10px] text-slate-300 font-mono mt-1">
                                    {{ $event['date'] ? \Carbon\Carbon::parse($event['date'])->format('d M Y, H:i') : '-' }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Rincian Biaya --}}
            @if($applicant->wave && $applicant->wave->fees->count() > 0)
            <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/30">
                    <h3 class="font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Rincian Biaya
                    </h3>
                </div>
                <div class="p-8 space-y-3">
                    @php $subTotal = 0; @endphp
                    @foreach($applicant->wave->fees->filter(fn($f) => is_null($f->major_id) || $f->major_id == $applicant->major_id) as $fee)
                    <div class="flex justify-between items-center text-xs py-2 border-b border-slate-50 last:border-0">
                        <span class="font-bold text-slate-500">
                            {{ $fee->component->name }}
                            @if($fee->major_id)
                                <span class="ml-1 px-1.5 py-0.5 bg-amber-50 text-amber-600 rounded text-[9px] uppercase font-black">{{ $applicant->major->name ?? 'Jurusan' }}</span>
                            @endif
                        </span>
                        <span class="font-black text-slate-900">Rp {{ number_format($fee->amount, 0, ',', '.') }}</span>
                        @php $subTotal += $fee->amount; @endphp
                    </div>
                    @endforeach
                    <div class="pt-3 flex justify-between items-center">
                        <span class="font-black text-slate-900">Total</span>
                        <span class="text-lg font-black text-primary-600">Rp {{ number_format($subTotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
