@extends('layouts.dashboard')

@section('title', 'Input Presensi — ' . $classroom->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('tenant.attendance.index') }}" class="p-2 bg-white rounded-xl border border-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Presensi: {{ $classroom->name }}</h1>
            <p class="text-slate-500 mt-1">Tanggal: {{ date('d F Y') }} • Total Siswa: {{ $students->count() }}</p>
        </div>
    </div>

    <form action="{{ route('tenant.attendance.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-[10px]">Nama Siswa</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-center text-[10px]">Hadir</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-center text-[10px]">Izin</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-center text-[10px]">Sakit</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-center text-[10px]">Alfa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($students as $student)
                    <tr>
                        <td class="px-8 py-5">
                            <span class="font-bold text-slate-900">{{ $student->name }}</span>
                            <p class="text-[10px] text-slate-400 font-medium tracking-wider">{{ $student->nisn }}</p>
                        </td>
                        @foreach(['H', 'I', 'S', 'A'] as $status)
                        <td class="px-8 py-5 text-center">
                            <input type="radio" name="attendance[{{ $student->id }}]" value="{{ $status }}" {{ $status == 'H' ? 'checked' : '' }} class="w-5 h-5 text-primary-600 bg-slate-50 border-slate-200 focus:ring-primary-500">
                        </td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-medium italic">Belum ada siswa di kelas ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="px-10 py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-primary-600 transition-all shadow-xl shadow-slate-200">
                Simpan Presensi Hari Ini
            </button>
        </div>
    </form>
</div>
@endsection
