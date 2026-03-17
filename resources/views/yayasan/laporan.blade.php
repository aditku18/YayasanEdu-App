@extends('layouts.dashboard')

@section('title', 'Laporan Yayasan')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Laporan & Statistik</h1>
            <p class="text-slate-500 mt-1">Laporan agregat performa pendidikan seluruh unit sekolah.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-6 py-3 bg-white border border-slate-200 text-slate-900 font-bold rounded-2xl hover:bg-slate-50 transition-all flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/></svg>
                Excel Agregat
            </button>
            <button class="px-6 py-3 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition-all flex items-center gap-2 shadow-xl shadow-slate-200">
                <svg class="w-5 h-5 text-rose-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                PDF Yayasan
            </button>
        </div>
    </div>

    <!-- Stats Table Card -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
        <div class="p-8 border-b border-slate-50">
            <h3 class="text-xl font-bold text-slate-900">Statistik per Unit Sekolah</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-[0.15em]">
                        <th class="px-8 py-5">Unit Sekolah</th>
                        <th class="px-6 py-5">Jenjang</th>
                        <th class="px-6 py-5 text-center">Jumlah Guru</th>
                        <th class="px-6 py-5 text-center">Jumlah Siswa</th>
                        <th class="px-6 py-5 text-center">Rasio Guru:Siswa</th>
                        <th class="px-8 py-5 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm font-medium">
                    @foreach($schoolStats as $stat)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5 text-slate-900 font-bold">{{ $stat->name }}</td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg">{{ $stat->jenjang ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-5 text-center text-slate-600">{{ $stat->teachers_count }}</td>
                        <td class="px-6 py-5 text-center text-slate-600">{{ number_format($stat->students_count) }}</td>
                        <td class="px-6 py-5 text-center">
                            @php
                                $ratio = $stat->students_count > 0 ? round($stat->students_count / max($stat->teachers_count, 1), 1) : 0;
                            @endphp
                            <span class="text-slate-900 font-bold">1:{{ $ratio }}</span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase rounded-full">Aktif</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-slate-900 text-white font-bold">
                        <td class="px-8 py-5" colspan="2">TOTAL YAYASAN</td>
                        <td class="px-6 py-5 text-center">{{ $schoolStats->sum('teachers_count') }}</td>
                        <td class="px-6 py-5 text-center">{{ number_format($schoolStats->sum('students_count')) }}</td>
                        <td class="px-6 py-5 text-center">-</td>
                        <td class="px-8 py-5 text-right">-</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
