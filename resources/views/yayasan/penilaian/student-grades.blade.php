@extends('layouts.dashboard')

@section('title', 'Nilai Siswa - ' . $student->name)

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Nilai {{ $student->name }}</h1>
            <p class="text-slate-500 mt-1">Kelas: {{ $student->classRoom?->name ?? '-' }}</p>
        </div>
        <a href="{{ route('tenant.school.grades.raport', ['school' => $schoolSlug]) }}" class="text-sm font-bold text-slate-400 hover:text-slate-600">Kembali</a>
    </div>

    <!-- Grades by Subject -->
    @if($grades->isNotEmpty())
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($grades as $subjectId => $subjectGrades)
        <div class="bg-white p-8 rounded-3xl border border-slate-100 premium-shadow">
            <h3 class="text-lg font-bold text-slate-900 mb-4">{{ $subjectGrades->first()?->subject?->name ?? 'Mata Pelajaran' }}</h3>
            
            <div class="space-y-3">
                @foreach($subjectGrades as $grade)
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                    <div>
                        <p class="font-bold text-slate-900">{{ $grade->gradeComponent?->name }}</p>
                        <p class="text-xs text-slate-500">{{ $grade->gradeComponent?->typeLabel }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-primary-600">{{ number_format($grade->score, 0) }}</p>
                        <p class="text-xs text-slate-500">/ {{ $grade->gradeComponent?->max_score }}</p>
                    </div>
                </div>
                @endforeach
                
                @if(isset($averages[$subjectId]))
                <div class="flex items-center justify-between p-4 bg-primary-50 rounded-2xl mt-4">
                    <div>
                        <p class="font-bold text-primary-900">Rata-rata</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-primary-600">{{ number_format(array_sum($averages[$subjectId]) / count($averages[$subjectId]), 2) }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white p-12 rounded-3xl border border-slate-100 premium-shadow text-center">
        <div class="text-slate-400">
            <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <p class="font-bold">Belum ada nilai untuk siswa ini</p>
        </div>
    </div>
    @endif
</div>
@endsection
