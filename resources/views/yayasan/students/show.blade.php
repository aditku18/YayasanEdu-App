@extends('layouts.dashboard')

@section('title', 'Detail Siswa')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ $schoolSlug ? route('tenant.school.students.index', ['school' => $schoolSlug]) : route('tenant.students.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 transition">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $student->name }}</h1>
            <p class="text-slate-500 text-sm mt-0.5">Detail lengkap data siswa</p>
        </div>
        <a href="{{ $schoolSlug ? route('tenant.school.students.edit', ['school' => $schoolSlug, 'student' => $student]) : route('tenant.students.edit', $student) }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-2xl px-5 py-3 text-sm text-green-700 font-medium">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex items-center gap-4 px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl font-extrabold">
                {{ substr($student->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">{{ $student->name }}</h2>
                <p class="text-sm text-slate-500">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }} · {{ $student->status }}</p>
            </div>
        </div>

        <dl class="divide-y divide-slate-50">
            @php
            $fields = [
                ['NIK', $student->nik],
                ['NIS', $student->nis],
                ['NISN', $student->nisn],
                ['Tempat Lahir', $student->birth_place],
                ['Tanggal Lahir', $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d F Y') : null],
                ['Alamat', $student->address],
                ['Nama Ayah', $student->father_name],
                ['Nama Ibu', $student->mother_name],
                ['Nama Wali', $student->guardian_name],
                ['No. HP Orang Tua', $student->parent_phone],
                ['Unit Sekolah', $student->school->name ?? '-'],
                ['Status', $student->status],
            ];
            @endphp
            @foreach($fields as [$label, $value])
            <div class="px-6 py-4 flex gap-4">
                <dt class="w-40 text-xs font-semibold text-slate-500 uppercase tracking-wide flex-shrink-0 pt-0.5">{{ $label }}</dt>
                <dd class="text-sm text-slate-800 font-medium">{{ $value ?: '-' }}</dd>
            </div>
            @endforeach
        </dl>
    </div>

    {{-- Delete --}}
    <div class="flex justify-end">
        <form action="{{ $schoolSlug ? route('tenant.school.students.destroy', ['school' => $schoolSlug, 'student' => $student->id]) : route('tenant.students.destroy', $student->id) }}" method="POST"
            onsubmit="return confirm('Yakin ingin menghapus data siswa ini? Tindakan tidak dapat dibatalkan.')">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-bold rounded-xl border border-red-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus Siswa
            </button>
        </form>
    </div>
</div>
@endsection
