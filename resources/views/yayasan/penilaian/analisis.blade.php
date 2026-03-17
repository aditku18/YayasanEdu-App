@extends('layouts.dashboard')

@section('title', 'Analisis Hasil Belajar')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Analisis Hasil Belajar</h1>
            <p class="text-slate-500 mt-1">Statistik dan analisis nilai siswa.</p>
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
                <label class="text-sm font-bold text-slate-600">Mata Pelajaran:</label>
                <select name="subject" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl px-4 py-2 text-sm font-bold focus:ring-2 focus:ring-primary-500 ml-2">
                    <option value="">Semua Mapel</option>
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ $selectedSubject == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Statistics by Subject -->
    @if($selectedSubject && isset($statistics[$selectedSubject]))
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 premium-shadow">
            <p class="text-sm text-slate-500">Rata-rata</p>
            <p class="text-3xl font-bold text-primary-600">{{ $statistics[$selectedSubject]['avg'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 premium-shadow">
            <p class="text-sm text-slate-500">Nilai Tertinggi</p>
            <p class="text-3xl font-bold text-emerald-600">{{ $statistics[$selectedSubject]['max'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 premium-shadow">
            <p class="text-sm text-slate-500">Nilai Terendah</p>
            <p class="text-3xl font-bold text-rose-600">{{ $statistics[$selectedSubject]['min'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 premium-shadow">
            <p class="text-sm text-slate-500">Median</p>
            <p class="text-3xl font-bold text-blue-600">{{ $statistics[$selectedSubject]['median'] }}</p>
        </div>
    </div>
    @endif

    <!-- Statistics Table by Subject -->
    <div class="bg-white rounded-3xl border border-slate-100 premium-shadow overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-900">Statistik per Mata Pelajaran</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase">Mata Pelajaran</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-slate-600 uppercase">Jml Nilai</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-slate-600 uppercase">Rata-rata</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-slate-600 uppercase">Terendah</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-slate-600 uppercase">Tertinggi</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-slate-600 uppercase">Median</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-slate-600 uppercase">Std Dev</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($subjects as $subject)
                    @if(isset($statistics[$subject->id]))
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $subject->name }}</td>
                        <td class="px-4 py-4 text-center text-sm text-slate-600">{{ $statistics[$subject->id]['count'] }}</td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-8 rounded-lg font-bold text-sm bg-primary-100 text-primary-700">
                                {{ $statistics[$subject->id]['avg'] }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center text-sm text-slate-600">{{ $statistics[$subject->id]['min'] }}</td>
                        <td class="px-4 py-4 text-center text-sm text-slate-600">{{ $statistics[$subject->id]['max'] }}</td>
                        <td class="px-4 py-4 text-center text-sm text-slate-600">{{ $statistics[$subject->id]['median'] }}</td>
                        <td class="px-4 py-4 text-center text-sm text-slate-600">{{ $statistics[$subject->id]['std_dev'] }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Statistics by Class -->
    <div class="bg-white rounded-3xl border border-slate-100 premium-shadow overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-900">Statistik per Kelas</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase">Kelas</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-slate-600 uppercase">Jml Nilai</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-slate-600 uppercase">Rata-rata</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-slate-600 uppercase">Terendah</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-slate-600 uppercase">Tertinggi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($classRooms as $classRoom)
                    @if(isset($classStats[$classRoom->id]))
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $classRoom->name }}</td>
                        <td class="px-4 py-4 text-center text-sm text-slate-600">{{ $classStats[$classRoom->id]['count'] }}</td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-8 rounded-lg font-bold text-sm bg-primary-100 text-primary-700">
                                {{ $classStats[$classRoom->id]['avg'] }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center text-sm text-slate-600">{{ $classStats[$classRoom->id]['min'] }}</td>
                        <td class="px-4 py-4 text-center text-sm text-slate-600">{{ $classStats[$classRoom->id]['max'] }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
