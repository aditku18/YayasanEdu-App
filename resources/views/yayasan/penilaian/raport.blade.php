@extends('layouts.dashboard')

@section('title', 'Raport Siswa')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Raport Siswa</h1>
            <p class="text-slate-500 mt-1">Lihat dan cetak raport siswa.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 premium-shadow">
        <form method="GET" class="flex items-center gap-4 flex-wrap">
            <div class="flex-1 min-w-[200px]">
                <label class="text-sm font-bold text-slate-600 mb-1 block">Kelas</label>
                <select name="classroom" onchange="this.form.submit()" class="w-full bg-slate-50 border-none rounded-xl px-4 py-2 text-sm font-bold focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Kelas</option>
                    @foreach($classRooms as $classRoom)
                    <option value="{{ $classRoom->id }}">{{ $classRoom->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="text-sm font-bold text-slate-600 mb-1 block">Tahun Ajaran</label>
                <select name="academic_year" onchange="this.form.submit()" class="w-full bg-slate-50 border-none rounded-xl px-4 py-2 text-sm font-bold focus:ring-2 focus:ring-primary-500">
                    @foreach($academicYears as $year)
                    <option value="{{ $year->id }}">{{ $year->name }} - {{ $year->semester }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Student List -->
    <div class="bg-white rounded-3xl border border-slate-100 premium-shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">No</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">NIS</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Nama Siswa</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Kelas</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($students as $index => $student)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 text-sm font-bold text-slate-500">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $student->nis ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-900">{{ $student->name }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $student->classRoom?->name ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('tenant.school.grades.student', ['student' => $student, 'school' => $schoolSlug]) }}" class="px-4 py-2 bg-primary-50 text-primary-600 font-bold text-xs rounded-xl hover:bg-primary-100">
                            Lihat Nilai
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                        Tidak ada data siswa
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
