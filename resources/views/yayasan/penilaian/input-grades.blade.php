@extends('layouts.dashboard')

@section('title', 'Input Nilai - ' . $gradeComponent->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Input Nilai</h1>
            <p class="text-slate-500 mt-1">{{ $gradeComponent->name }} - {{ $gradeComponent->subject?->name }} - {{ $gradeComponent->classRoom?->name }}</p>
        </div>
        <a href="{{ route('tenant.school.grades.index', ['school' => $schoolSlug]) }}" class="text-sm font-bold text-slate-400 hover:text-slate-600">Kembali</a>
    </div>

    <!-- Component Info -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="font-bold text-slate-900">{{ $gradeComponent->name }}</p>
                <p class="text-sm text-slate-500">{{ $gradeComponent->typeLabel }} | Bobot: {{ $gradeComponent->weight }}% | Max: {{ $gradeComponent->max_score }}</p>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-3xl flex items-center gap-4 text-emerald-600">
        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="font-bold text-sm">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Grades Form -->
    <form action="{{ route('tenant.school.grades.store', ['gradeComponent' => $gradeComponent, 'school' => $schoolSlug]) }}" method="POST" class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
        @csrf
        
        <div class="p-8 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Daftar Siswa</h3>
            <p class="text-sm text-slate-500">Kelas {{ $gradeComponent->classRoom?->name }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Nama Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">NIS</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Nilai ({{ $gradeComponent->max_score }})</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $index => $student)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-bold text-slate-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">{{ $student->name }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $student->nis ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <input 
                                type="number" 
                                name="grades[{{ $student->id }}]" 
                                value="{{ $existingGrades[$student->id] ?? '' }}"
                                min="0" 
                                max="{{ $gradeComponent->max_score }}"
                                step="0.01"
                                class="w-24 bg-slate-50 border-none rounded-xl px-4 py-2 text-slate-900 font-bold focus:ring-2 focus:ring-primary-500 transition-all"
                                placeholder="0"
                            >
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="text-slate-400">
                                <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <p class="font-bold">Tidak ada siswa di kelas ini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->isNotEmpty())
        <div class="p-8 border-t border-slate-100 flex items-center justify-between">
            <p class="text-sm text-slate-500">Total siswa: {{ $students->count() }}</p>
            <button type="submit" class="px-10 py-4 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-100 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Nilai
            </button>
        </div>
        @endif
    </form>
</div>
@endsection
