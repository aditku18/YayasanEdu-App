@extends('layouts.dashboard')

@section('title', 'Buat Jadwal — ' . $school->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ $schoolSlug ? route('tenant.school.schedule.index', ['school' => $schoolSlug]) : route('tenant.schedule.index', ['school' => $school->slug]) }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Jadwal
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8">
        <h1 class="text-2xl font-black text-slate-900 mb-6">Buat Jadwal Pelajaran Baru</h1>
        
        <form action="{{ $schoolSlug ? route('tenant.school.schedule.store', ['school' => $schoolSlug]) : route('tenant.schedule.store', ['school' => $school->slug]) }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="class_room_id" class="block text-sm font-bold text-slate-700 mb-2">Kelas</label>
                <select name="class_room_id" id="class_room_id" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all">
                    <option value="">Pilih Kelas</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                    @endforeach
                </select>
                @error('class_room_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="subject_id" class="block text-sm font-bold text-slate-700 mb-2">Mata Pelajaran</label>
                <select name="subject_id" id="subject_id" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all">
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->code }} - {{ $subject->name }}</option>
                    @endforeach
                </select>
                @error('subject_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="teacher_id" class="block text-sm font-bold text-slate-700 mb-2">Guru</label>
                <select name="teacher_id" id="teacher_id" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all">
                    <option value="">Pilih Guru</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
                @error('teacher_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="academic_year_id" class="block text-sm font-bold text-slate-700 mb-2">Tahun Ajaran</label>
                <select name="academic_year_id" id="academic_year_id" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all">
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                    @endforeach
                </select>
                @error('academic_year_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="day" class="block text-sm font-bold text-slate-700 mb-2">Hari</label>
                <select name="day" id="day" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all">
                    <option value="">Pilih Hari</option>
                    <option value="monday">Senin</option>
                    <option value="tuesday">Selasa</option>
                    <option value="wednesday">Rabu</option>
                    <option value="thursday">Kamis</option>
                    <option value="friday">Jumat</option>
                    <option value="saturday">Sabtu</option>
                    <option value="sunday">Minggu</option>
                </select>
                @error('day')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="start_time" class="block text-sm font-bold text-slate-700 mb-2">Jam Mulai</label>
                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all">
                    @error('start_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="end_time" class="block text-sm font-bold text-slate-700 mb-2">Jam Selesai</label>
                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all">
                    @error('end_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">
                    Simpan Jadwal
                </button>
                <a href="{{ $schoolSlug ? route('tenant.school.schedule.index', ['school' => $schoolSlug]) : route('tenant.schedule.index', ['school' => $school->slug]) }}" class="px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-2xl hover:bg-slate-200 transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
