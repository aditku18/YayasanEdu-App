@extends('layouts.dashboard')

@section('title', 'Penilaian - Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Penilaian</h1>
            <p class="text-slate-500 mt-1">Kelola nilai siswa: UH, UTS, UAS, dan Raport.</p>
        </div>
        <a href="{{ route('tenant.school.grades.create-component', ['school' => $schoolSlug]) }}" class="px-6 py-3 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Komponen Nilai
        </a>
    </div>

    <!-- Academic Year Filter -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 premium-shadow">
        <form method="GET" class="flex items-center gap-4">
            <label class="text-sm font-bold text-slate-600">Tahun Ajaran:</label>
            <select name="academic_year" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl px-4 py-2 text-sm font-bold focus:ring-2 focus:ring-primary-500">
                @foreach($academicYears as $year)
                <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                    {{ $year->name }}
                </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-3xl flex items-center gap-4 text-emerald-600">
        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="font-bold text-sm">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Grade Components by Type -->
    @if($gradeComponents->isNotEmpty())
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Ulangan Harian -->
        @if(isset($gradeComponents['daily']) && $gradeComponents['daily']->isNotEmpty())
        <div class="bg-white p-8 rounded-3xl border border-slate-100 premium-shadow">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Ulangan Harian (UH)</h3>
                    <p class="text-sm text-slate-500">Nilai harian/tes materi</p>
                </div>
            </div>
            <div class="space-y-3">
                @foreach($gradeComponents['daily'] as $component)
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                    <div>
                        <p class="font-bold text-slate-900">{{ $component->name }}</p>
                        <p class="text-xs text-slate-500">{{ $component->subject?->name }} - {{ $component->classRoom?->name }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('tenant.school.grades.input', ['gradeComponent' => $component, 'school' => $schoolSlug]) }}" class="px-4 py-2 bg-primary-50 text-primary-600 font-bold text-xs rounded-xl hover:bg-primary-100">
                            Input Nilai
                        </a>
                        <form method="POST" action="{{ route('tenant.school.grades.destroy-component', ['gradeComponent' => $component, 'school' => $schoolSlug]) }}" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-2 text-rose-500 hover:bg-rose-50 rounded-xl">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Tugas -->
        @if(isset($gradeComponents['assignment']) && $gradeComponents['assignment']->isNotEmpty())
        <div class="bg-white p-8 rounded-3xl border border-slate-100 premium-shadow">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Tugas</h3>
                    <p class="text-sm text-slate-500">Tugas rumah/project</p>
                </div>
            </div>
            <div class="space-y-3">
                @foreach($gradeComponents['assignment'] as $component)
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                    <div>
                        <p class="font-bold text-slate-900">{{ $component->name }}</p>
                        <p class="text-xs text-slate-500">{{ $component->subject?->name }} - {{ $component->classRoom?->name }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('tenant.school.grades.input', ['gradeComponent' => $component, 'school' => $schoolSlug]) }}" class="px-4 py-2 bg-primary-50 text-primary-600 font-bold text-xs rounded-xl hover:bg-primary-100">
                            Input Nilai
                        </a>
                        <form method="POST" action="{{ route('tenant.school.grades.destroy-component', ['gradeComponent' => $component, 'school' => $schoolSlug]) }}" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-2 text-rose-500 hover:bg-rose-50 rounded-xl">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- UTS -->
        @if(isset($gradeComponents['midterm']) && $gradeComponents['midterm']->isNotEmpty())
        <div class="bg-white p-8 rounded-3xl border border-slate-100 premium-shadow">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">UTS</h3>
                    <p class="text-sm text-slate-500">Ujian Tengah Semester</p>
                </div>
            </div>
            <div class="space-y-3">
                @foreach($gradeComponents['midterm'] as $component)
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                    <div>
                        <p class="font-bold text-slate-900">{{ $component->name }}</p>
                        <p class="text-xs text-slate-500">{{ $component->subject?->name }} - {{ $component->classRoom?->name }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('tenant.school.grades.input', ['gradeComponent' => $component, 'school' => $schoolSlug]) }}" class="px-4 py-2 bg-primary-50 text-primary-600 font-bold text-xs rounded-xl hover:bg-primary-100">
                            Input Nilai
                        </a>
                        <form method="POST" action="{{ route('tenant.school.grades.destroy-component', ['gradeComponent' => $component, 'school' => $schoolSlug]) }}" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-2 text-rose-500 hover:bg-rose-50 rounded-xl">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- UAS -->
        @if(isset($gradeComponents['final']) && $gradeComponents['final']->isNotEmpty())
        <div class="bg-white p-8 rounded-3xl border border-slate-100 premium-shadow">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">UAS</h3>
                    <p class="text-sm text-slate-500">Ujian Akhir Semester</p>
                </div>
            </div>
            <div class="space-y-3">
                @foreach($gradeComponents['final'] as $component)
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                    <div>
                        <p class="font-bold text-slate-900">{{ $component->name }}</p>
                        <p class="text-xs text-slate-500">{{ $component->subject?->name }} - {{ $component->classRoom?->name }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('tenant.school.grades.input', ['gradeComponent' => $component, 'school' => $schoolSlug]) }}" class="px-4 py-2 bg-primary-50 text-primary-600 font-bold text-xs rounded-xl hover:bg-primary-100">
                            Input Nilai
                        </a>
                        <form method="POST" action="{{ route('tenant.school.grades.destroy-component', ['gradeComponent' => $component, 'school' => $schoolSlug]) }}" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-2 text-rose-500 hover:bg-rose-50 rounded-xl">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white p-12 rounded-3xl border border-slate-100 premium-shadow text-center">
        <div class="w-20 h-20 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-2">Belum Ada Komponen Nilai</h3>
        <p class="text-slate-500 mb-6">Mulai tambahkan komponen nilai seperti UH, UTS, UAS, atau Tugas.</p>
        <a href="{{ route('tenant.school.grades.create-component', ['school' => $schoolSlug]) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Komponen Nilai Pertama
        </a>
    </div>
    @endif

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('tenant.school.grades.raport', ['school' => $schoolSlug]) }}" class="bg-white p-8 rounded-3xl border border-slate-100 premium-shadow hover:border-primary-200 transition-all group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-violet-50 text-violet-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Raport Siswa</h3>
                    <p class="text-sm text-slate-500">Lihat & cetak raport siswa</p>
                </div>
            </div>
        </a>
        
        <a href="#" class="bg-white p-8 rounded-3xl border border-slate-100 premium-shadow hover:border-primary-200 transition-all group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-cyan-50 text-cyan-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Rekap Nilai</h3>
                    <p class="text-sm text-slate-500">Rekap nilai per kelas/mapel</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
