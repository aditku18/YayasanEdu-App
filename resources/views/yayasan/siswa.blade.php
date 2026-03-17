@extends('layouts.dashboard')

@section('title', 'Manajemen Siswa' . ($schoolSlug ? ' - ' . ucfirst($schoolSlug) : ' Global'))

@section('content')
<div class="space-y-6">

    {{-- Alert --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3.5 rounded-2xl text-sm font-medium">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-2xl text-sm font-medium">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->has('file') || $errors->has('import'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-2xl text-sm">
            @foreach($errors->all() as $e)
                <p class="flex items-start gap-2"><svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $e }}</p>
            @endforeach
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Siswa</h1>
            <p class="text-slate-500 text-sm mt-0.5">Data seluruh siswa aktif di seluruh unit sekolah yayasan.</p>
        </div>
        {{-- Action Buttons --}}
        <div class="flex flex-wrap items-center gap-2">
            {{-- Tambah Manual --}}
            <a href="{{ $schoolSlug ? route('tenant.school.students.create', ['school' => $schoolSlug]) : route('tenant.students.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm hover:shadow-md transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Siswa
            </a>

            {{-- Import Button → buka modal --}}
            <button onclick="document.getElementById('modal-import').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl shadow-sm hover:shadow-md transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Import Data
            </button>

            {{-- Download Template --}}
            <a href="{{ $schoolSlug ? route('tenant.school.students.template', ['school' => $schoolSlug]) : route('tenant.students.template') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-600 hover:bg-slate-700 text-white text-sm font-bold rounded-xl shadow-sm hover:shadow-md transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
                Template CSV
            </a>

            {{-- Export Excel --}}
            <button class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold rounded-xl shadow-sm hover:shadow-md transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Excel
            </button>

            {{-- Export PDF --}}
            <button class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-sm font-bold rounded-xl shadow-sm hover:shadow-md transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Export PDF
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-600 p-6 rounded-2xl text-white shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-xs font-bold uppercase tracking-widest">Total Siswa</p>
                    <h3 class="text-4xl font-extrabold mt-1">{{ number_format($students->total()) }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Laki-laki</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 mt-1">{{ number_format($maleCount ?? 0) }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Perempuan</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 mt-1">{{ number_format($femaleCount ?? 0) }}</h3>
                </div>
                <div class="w-12 h-12 bg-pink-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
        <form action="{{ $schoolSlug ? route('tenant.school.students.index', ['school' => $schoolSlug]) : route('tenant.students.index') }}" method="GET" class="flex items-center gap-3">
            <div class="relative flex-1 max-w-sm">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIS, atau NIK..." class="bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full transition">
            </div>
            <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white text-sm font-bold rounded-xl hover:bg-slate-700 transition">Cari</button>
            @if(request('search'))
                <a href="{{ $schoolSlug ? route('tenant.school.students.index', ['school' => $schoolSlug]) : route('tenant.students.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-200 transition">Reset</a>
            @endif
        </form>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-[0.15em]">
                        <th class="px-8 py-5">Nama Siswa</th>
                        <th class="px-6 py-5">NIK</th>
                        <th class="px-6 py-5">NIS</th>
                        <th class="px-6 py-5">Unit Sekolah</th>
                        <th class="px-6 py-5">Kelas</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($students as $student)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center font-bold">
                                    {{ substr($student->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 leading-none">{{ $student->name }}</p>
                                    <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-widest">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm font-bold text-slate-600 tracking-wider">{{ $student->nik ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm font-bold text-slate-600 tracking-wider">{{ $student->nis ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 bg-primary-50 text-primary-600 text-xs font-bold rounded-lg">{{ $student->school->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm font-bold text-slate-700">{{ $student->classRoom->name ?? '-' }}</span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-1">
                                {{-- Show --}}
                                <a href="{{ $schoolSlug ? route('tenant.school.students.show', ['school' => $schoolSlug, 'student' => $student->id]) : route('tenant.students.show', $student->id) }}"
                                    title="Lihat Detail"
                                    class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                {{-- Edit --}}
                                <a href="{{ $schoolSlug ? route('tenant.school.students.edit', ['school' => $schoolSlug, 'student' => $student->id]) : route('tenant.students.edit', $student->id) }}"
                                    title="Edit"
                                    class="p-2 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                {{-- Delete --}}
                                <form action="{{ $schoolSlug ? route('tenant.school.students.destroy', ['school' => $schoolSlug, 'student' => $student->id]) : route('tenant.students.destroy', $student->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus siswa {{ addslashes($student->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus"
                                        class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-10 text-center text-slate-400 font-medium italic">Belum ada data siswa.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-8 py-6 bg-slate-50">
            {{ $students->links() }}
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     MODAL IMPORT DATA SISWA
══════════════════════════════════════════════════════════ --}}
<div id="modal-import" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('modal-import').classList.add('hidden')"></div>

    {{-- Modal Card --}}
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between px-7 py-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Import Data Siswa</h3>
                    <p class="text-xs text-slate-500">Upload file CSV atau Excel</p>
                </div>
            </div>
            <button onclick="document.getElementById('modal-import').classList.add('hidden')"
                class="p-2 rounded-xl hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-7 py-6 space-y-5">
            {{-- Step 1: Download Template --}}
            <div class="flex items-start gap-4 p-4 bg-blue-50 rounded-2xl border border-blue-100">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                    <span class="text-blue-600 font-extrabold text-xs">1</span>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-slate-800">Download Template</p>
                    <p class="text-xs text-slate-500 mt-0.5">Gunakan template resmi agar format data sesuai dengan sistem.</p>
                    <a href="{{ $schoolSlug ? route('tenant.school.students.template', ['school' => $schoolSlug]) : route('tenant.students.template') }}"
                        class="inline-flex items-center gap-1.5 mt-2.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        </svg>
                        Download template_import_siswa.csv
                    </a>
                </div>
            </div>

            {{-- Step 2: Upload File --}}
            <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                <div class="w-8 h-8 bg-slate-200 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                    <span class="text-slate-600 font-extrabold text-xs">2</span>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-slate-800">Upload File yang Sudah Diisi</p>
                    <p class="text-xs text-slate-500 mt-0.5">Format yang didukung: <strong>.csv</strong></p>

                    <form action="{{ $schoolSlug ? route('tenant.school.students.import', ['school' => $schoolSlug]) : route('tenant.students.import') }}" method="POST" enctype="multipart/form-data" id="form-import" class="mt-3">
                        @csrf
                        {{-- Drop Zone --}}
                        <label for="import-file-modal"
                            class="flex flex-col items-center justify-center gap-2 w-full h-28 border-2 border-dashed border-slate-300 rounded-xl cursor-pointer hover:border-amber-400 hover:bg-amber-50 transition group"
                            id="drop-zone">
                            <svg class="w-8 h-8 text-slate-300 group-hover:text-amber-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <span class="text-xs text-slate-400 group-hover:text-amber-500 font-medium" id="drop-label">Klik atau seret file ke sini</span>
                            <input id="import-file-modal" type="file" name="file" accept=".csv" class="hidden"
                                onchange="document.getElementById('drop-label').textContent = this.files[0]?.name ?? 'Klik atau seret file ke sini'">
                        </label>
                    </form>
                </div>
            </div>

            {{-- Catatan --}}
            <div class="flex items-start gap-2 text-xs text-slate-500">
                <svg class="w-4 h-4 text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Isi data siswa mulai dari baris kosong setelah baris contoh. Baris placeholder dan contoh akan dilewati otomatis saat import.</span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end gap-3 px-7 py-4 bg-slate-50 border-t border-slate-100">
            <button type="button" onclick="document.getElementById('modal-import').classList.add('hidden')"
                class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 text-sm font-medium hover:bg-slate-50 transition">
                Batal
            </button>
            <button type="button" onclick="document.getElementById('form-import').submit()"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Upload & Import
            </button>
        </div>
    </div>
</div>

@endsection
