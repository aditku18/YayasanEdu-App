@extends('layouts.dashboard')

@section('title', 'Presensi Siswa')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Presensi Siswa</h1>
            <p class="text-slate-500 mt-1">Pantau dan kelola kehadiran harian siswa per kelas.</p>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Kelas</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Level</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Stats Hari Ini</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($classrooms as $classroom)
                <tr class="hover:bg-slate-50/30 transition-colors group">
                    <td class="px-8 py-5">
                        <span class="font-bold text-slate-900">{{ $classroom->name }}</span>
                    </td>
                    <td class="px-8 py-5">
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-bold uppercase tracking-widest">{{ $classroom->level }}</span>
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            <span class="text-xs font-bold text-slate-500 italic">Belum Diinput</span>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <a href="{{ route('tenant.attendance.create', $classroom->id) }}" class="px-4 py-2 bg-primary-50 text-primary-600 font-bold rounded-xl hover:bg-primary-600 hover:text-white transition-all text-xs">
                            Input Presensi
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
