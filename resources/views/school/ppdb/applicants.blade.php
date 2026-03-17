@extends('layouts.dashboard')

@section('title', 'Daftar Pendaftar PPDB')

@section('content')
<div class="max-w-6xl mx-auto py-8 space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ $schoolSlug ? route('tenant.school.ppdb.index', ['school' => $schoolSlug]) : route('tenant.ppdb.index') }}" class="p-2 bg-white rounded-xl border border-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Calon Siswa Baru</h1>
            <p class="text-slate-500 mt-1">Daftar pendaftar yang masuk melalui portal PPDB.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl font-bold flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden overflow-x-auto">
        <form action="{{ url()->current() }}" method="GET" class="px-8 py-6 border-b border-slate-50 flex flex-wrap items-center justify-between gap-4 bg-slate-50/20">
            <div class="flex items-center flex-wrap gap-3">
                @if($isFoundation)
                <select name="school_unit_id" onchange="this.form.submit()" class="bg-white border border-slate-100 rounded-xl text-xs font-bold text-slate-600 px-4 py-2 appearance-none cursor-pointer">
                    <option value="">Semua Unit Sekolah</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ request('school_unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                    @endforeach
                </select>
                @endif

                <select name="ppdb_wave_id" onchange="this.form.submit()" class="bg-white border border-slate-100 rounded-xl text-xs font-bold text-slate-600 px-4 py-2 appearance-none cursor-pointer">
                    <option value="">Semua Gelombang</option>
                    @foreach($waves as $wave)
                        <option value="{{ $wave->id }}" {{ request('ppdb_wave_id') == $wave->id ? 'selected' : '' }}>{{ $wave->name }}</option>
                    @endforeach
                </select>

                <select name="status" onchange="this.form.submit()" class="bg-white border border-slate-100 rounded-xl text-xs font-bold text-slate-600 px-4 py-2 appearance-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Berkas Lengkap</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Diterima</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Terdaftar</option>
                </select>

                <select name="payment_status" onchange="this.form.submit()" class="bg-white border border-slate-100 rounded-xl text-xs font-bold text-slate-600 px-4 py-2 appearance-none cursor-pointer">
                    <option value="">Semua Pembayaran</option>
                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum</option>
                    <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>DP 50%</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                </select>

                @if(request()->anyFilled(['search', 'ppdb_wave_id', 'status', 'school_unit_id']))
                    <a href="{{ url()->current() }}" class="text-[10px] font-bold text-red-500 hover:text-red-700 uppercase tracking-wider">Reset</a>
                @endif
            </div>

            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / nomor registrasi..." 
                       class="bg-white border border-slate-100 rounded-xl px-10 py-2 text-xs font-medium w-64 focus:ring-2 focus:ring-primary-500 transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <button type="submit" class="hidden">Cari</button>
            </div>
        </form>

        <table class="w-full text-left border-collapse table-auto">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Pendaftar</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Gelombang</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Berkas</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Pembayaran</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Total Tagihan</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @php
                    $statusConfig = [
                        'pending'  => ['label' => 'Menunggu',       'bg' => 'bg-amber-50',   'text' => 'text-amber-600'],
                        'verified' => ['label' => 'Berkas Lengkap', 'bg' => 'bg-blue-50',    'text' => 'text-blue-600'],
                        'accepted' => ['label' => 'Diterima',       'bg' => 'bg-green-50',   'text' => 'text-green-600'],
                        'rejected' => ['label' => 'Ditolak',        'bg' => 'bg-red-50',     'text' => 'text-red-600'],
                        'enrolled' => ['label' => 'Terdaftar',      'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
                    ];
                @endphp
                @forelse($applicants as $applicant)
                <tr class="hover:bg-slate-50/30 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-full flex items-center justify-center font-bold text-xs font-mono">
                                {{ substr($applicant->name, 0, 1) }}
                            </div>
                            <div>
                                <a href="{{ $schoolSlug ? route('tenant.school.ppdb.applicants.show', ['school' => $schoolSlug, 'id' => $applicant->id]) : route('tenant.ppdb.applicants.show', $applicant->id) }}" class="font-bold text-slate-900 leading-none hover:text-primary-600 transition-colors">{{ $applicant->name }}</a>
                                <p class="text-[10px] text-slate-400 font-medium mt-1">Reg: {{ $applicant->registration_number }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">{{ $applicant->wave->name ?? 'N/A' }}</span>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            @foreach([
                                'document_kk' => 'KK',
                                'document_akta' => 'AK',
                                'document_ijazah' => 'IJ',
                                'document_foto' => 'FT',
                                'payment_proof' => '$$',
                                'final_payment_proof' => 'RD'
                            ] as $field => $label)
                                @if($applicant->$field)
                                    <a href="{{ tenant_asset($applicant->$field) }}" target="_blank" 
                                       class="w-6 h-6 rounded bg-green-100 text-green-600 flex items-center justify-center text-[9px] font-black hover:bg-green-600 hover:text-white transition-all shadow-sm"
                                       title="Lihat {{ $label }}">
                                        {{ $label }}
                                    </a>
                                @else
                                    <div class="w-6 h-6 rounded bg-slate-100 text-slate-300 flex items-center justify-center text-[9px] font-black cursor-help"
                                         title="{{ $label }} Belum Diunggah">
                                        {{ $label }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center">
                        @php $sc = $statusConfig[$applicant->status] ?? $statusConfig['pending']; @endphp
                        <span class="px-3 py-1 {{ $sc['bg'] }} {{ $sc['text'] }} rounded-full text-[10px] font-bold uppercase tracking-widest">{{ $sc['label'] }}</span>
                    </td>
                    <td class="px-8 py-5 text-center">
                        @if($applicant->payment_status == 'paid')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-green-100 text-green-600">Lunas</span>
                        @elseif($applicant->payment_status == 'partial')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-cyan-100 text-cyan-600">DP 50%</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-slate-100 text-slate-400">Belum</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-sm font-bold text-slate-900 text-right">
                        Rp {{ number_format($applicant->total_fee, 0, ',', '.') }}
                    </td>
                    <td class="px-8 py-5 text-right">
                        <a href="{{ $schoolSlug ? route('tenant.school.ppdb.applicants.show', ['school' => $schoolSlug, 'id' => $applicant->id]) : route('tenant.ppdb.applicants.show', $applicant->id) }}" class="px-3 py-1.5 bg-slate-900 text-white font-bold rounded-lg hover:bg-primary-600 transition-all text-[10px] uppercase tracking-wider inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-20 text-center text-slate-400 italic font-medium">Belum ada pendaftar masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
