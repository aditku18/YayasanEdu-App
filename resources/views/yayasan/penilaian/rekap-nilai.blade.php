@extends('layouts.dashboard')

@section('title', 'Rekap Nilai')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Rekap Nilai</h1>
            <p class="text-slate-500 mt-1">Rekap nilai siswa per kelas dan mata pelajaran.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('tenant.school.grades.export', ['academic_year' => $selectedYearId, 'class_room' => $selectedClassRoom, 'school' => $schoolSlug]) }}" class="px-4 py-2 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700">
                Export Excel
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 premium-shadow">
        <form method="GET" class="flex items-center gap-4 flex-wrap">
            <div>
                <label class="text-sm font-bold text-slate-600">Tahun Ajaran:</label>
                <select name="academic_year" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl px-4 py-2 text-sm font-bold focus:ring-2 focus:ring-primary-500 ml-2">
                    @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                        {{ $year->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-bold text-slate-600">Kelas:</label>
                <select name="class_room" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl px-4 py-2 text-sm font-bold focus:ring-2 focus:ring-primary-500 ml-2">
                    <option value="">Semua Kelas</option>
                    @foreach($classRooms as $classRoom)
                    <option value="{{ $classRoom->id }}" {{ $selectedClassRoom == $classRoom->id ? 'selected' : '' }}>
                        {{ $classRoom->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Table -->
    @if($students->isNotEmpty())
    <div class="bg-white rounded-3xl border border-slate-100 premium-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase">NIS</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase">Nama Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase">Kelas</th>
                        @foreach($subjects as $subject)
                        <th class="px-4 py-4 text-center text-xs font-bold text-slate-600 uppercase">{{ $subject->code ?? $subject->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($students as $student)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $student->nis }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $student->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $student->classRoom?->name }}</td>
                        @foreach($subjects as $subject)
                        <td class="px-4 py-4 text-center">
                            @if(isset($studentGrades[$student->id][$subject->id]))
                            <span class="inline-flex items-center justify-center w-12 h-8 rounded-lg font-bold text-sm 
                                @if($studentGrades[$student->id][$subject->id]['avg'] >= 85) bg-emerald-100 text-emerald-700
                                @elseif($studentGrades[$student->id][$subject->id]['avg'] >= 70) bg-blue-100 text-blue-700
                                @elseif($studentGrades[$student->id][$subject->id]['avg'] >= 60) bg-amber-100 text-amber-700
                                @else bg-rose-100 text-rose-700 @endif">
                                {{ $studentGrades[$student->id][$subject->id]['avg'] }}
                            </span>
                            @else
                            <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white p-12 rounded-3xl border border-slate-100 premium-shadow text-center">
        <p class="text-slate-500">Tidak ada data siswa.</p>
    </div>
    @endif
</div>
@endsection
