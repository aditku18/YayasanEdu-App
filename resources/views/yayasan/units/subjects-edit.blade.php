@extends('layouts.dashboard')

@section('title', 'Edit Mata Pelajaran — ' . $school->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ $schoolSlug ? route('tenant.school.subjects.index', ['school' => $schoolSlug]) : route('tenant.subjects.index', ['school' => $school->slug]) }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Mata Pelajaran
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8">
        <h1 class="text-2xl font-black text-slate-900 mb-6">Edit Mata Pelajaran</h1>
        
        <form action="{{ $schoolSlug ? route('tenant.school.subjects.update', ['school' => $schoolSlug, 'subject' => $subject->id]) : route('tenant.subjects.update', ['school' => $school->slug, 'subject' => $subject->id]) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label for="code" class="block text-sm font-bold text-slate-700 mb-2">Kode Mapel</label>
                <input type="text" name="code" id="code" value="{{ old('code', $subject->code) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all"
                    placeholder="Contoh: MTK">
                @error('code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Mapel</label>
                <input type="text" name="name" id="name" value="{{ old('name', $subject->name) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all"
                    placeholder="Contoh: Matematika">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="type" class="block text-sm font-bold text-slate-700 mb-2">Tipe Mapel</label>
                <select name="type" id="type" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all">
                    <option value="">Pilih Tipe</option>
                    <option value="theory" {{ old('type', $subject->type) == 'theory' ? 'selected' : '' }}>Teori</option>
                    <option value="practice" {{ old('type', $subject->type) == 'practice' ? 'selected' : '' }}>Praktik</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">
                    Update Mata Pelajaran
                </button>
                <a href="{{ $schoolSlug ? route('tenant.school.subjects.index', ['school' => $schoolSlug]) : route('tenant.subjects.index', ['school' => $school->slug]) }}" class="px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-2xl hover:bg-slate-200 transition-all">
                    Batal
                </a>
            </div>
        </form>
        
        <div class="mt-8 pt-6 border-t border-slate-100">
            <form action="{{ $schoolSlug ? route('tenant.school.subjects.destroy', ['school' => $schoolSlug, 'subject' => $subject->id]) : route('tenant.subjects.destroy', ['school' => $school->slug, 'subject' => $subject->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-3 bg-red-50 text-red-600 font-bold rounded-2xl hover:bg-red-100 transition-all">
                    Hapus Mata Pelajaran
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
