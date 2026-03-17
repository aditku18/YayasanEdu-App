@extends('layouts.dashboard')

@section('title', 'Edit Kelas')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ $schoolSlug ? route('tenant.school.classrooms.show', ['school' => $schoolSlug, 'classroom' => $classroom->id]) : route('tenant.classrooms.show', $classroom->id) }}" class="p-2 bg-white rounded-xl border border-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Edit Kelas</h1>
            <p class="text-slate-500 text-sm">Perbarui informasi kelas {{ $classroom->name }}</p>
        </div>
    </div>

    <form action="{{ $schoolSlug ? route('tenant.school.classrooms.update', ['school' => $schoolSlug, 'classroom' => $classroom->id]) : route('tenant.classrooms.update', $classroom->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 space-y-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kelas</label>
                <input type="text" name="name" value="{{ old('name', $classroom->name) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Tingkat / Level</label>
                <select name="level" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition">
                    <option value="">Pilih Tingkat</option>
                    <option value="X" {{ old('level', $classroom->level) == 'X' ? 'selected' : '' }}>X (Sepuluh)</option>
                    <option value="XI" {{ old('level', $classroom->level) == 'XI' ? 'selected' : '' }}>XI (Sebelas)</option>
                    <option value="XII" {{ old('level', $classroom->level) == 'XII' ? 'selected' : '' }}>XII (Dua Belas)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Wali Kelas</label>
                <select name="teacher_id"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition">
                    <option value="">Pilih Wali Kelas</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $classroom->teacher_id) == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ $schoolSlug ? route('tenant.school.classrooms.show', ['school' => $schoolSlug, 'classroom' => $classroom->id]) : route('tenant.classrooms.show', $classroom->id) }}" class="px-6 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">Batalkan</a>
            <button type="submit" class="px-10 py-3 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-slate-200">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
