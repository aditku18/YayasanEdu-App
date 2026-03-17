@extends('layouts.dashboard')

@section('title', 'Tagihan Siswa')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('tenant.finance.index') }}" class="p-2 bg-white rounded-xl border border-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Tagihan & Iuran Siswa</h1>
            <p class="text-slate-500 mt-1">Kelola SPP dan kewajiban finansial siswa lainnya.</p>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Siswa</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">NISN</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Status Pembayaran</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($students as $student)
                <tr class="hover:bg-slate-50/30 transition-colors group">
                    <td class="px-8 py-5">
                        <span class="font-bold text-slate-900">{{ $student->name }}</span>
                    </td>
                    <td class="px-8 py-5 text-sm font-medium text-slate-500">{{ $student->nisn }}</td>
                    <td class="px-8 py-5">
                        <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-full text-[10px] font-bold uppercase tracking-widest text-white-space-nowrap">Belum Lunas</span>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <button class="px-4 py-2 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all text-xs">
                            Kirim Tagihan
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-medium italic">Belum ada data siswa yang ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-6">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
