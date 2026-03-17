@extends('layouts.dashboard')

@section('title', 'Penilaian Sikap')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Penilaian Sikap</h1>
            <p class="text-slate-500 mt-1">Penilaian sikap spiritual dan sosial siswa.</p>
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
                <label class="text-sm font-bold text-slate-600">Semester:</label>
                <select name="semester" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl px-4 py-2 text-sm font-bold focus:ring-2 focus:ring-primary-500 ml-2">
                    <option value="ganjil" {{ $semester == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="genap" {{ $semester == 'genap' ? 'selected' : '' }}>Genap</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-3xl flex items-center gap-4 text-emerald-600">
        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="font-bold text-sm">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-100 premium-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase">NIS</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase">Nama Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase">Kelas</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-600 uppercase">Sikap Spiritual</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-600 uppercase">Sikap Sosial</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($students as $student)
                    @php
                        $spiritual = $behaviorGrades->get($student->id)?->where('aspect', 'spiritual')->first();
                        $social = $behaviorGrades->get($student->id)?->where('aspect', 'social')->first();
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $student->nis }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $student->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $student->classRoom?->name }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($spiritual)
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold
                                @if($spiritual->grade == 'sangat_baik') bg-emerald-100 text-emerald-700
                                @elseif($spiritual->grade == 'baik') bg-blue-100 text-blue-700
                                @elseif($spiritual->grade == 'cukup') bg-amber-100 text-amber-700
                                @else bg-rose-100 text-rose-700 @endif">
                                {{ str_replace('_', ' ', ucfirst($spiritual->grade)) }}
                            </span>
                            @else
                            <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($social)
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold
                                @if($social->grade == 'sangat_baik') bg-emerald-100 text-emerald-700
                                @elseif($social->grade == 'baik') bg-blue-100 text-blue-700
                                @elseif($social->grade == 'cukup') bg-amber-100 text-amber-700
                                @else bg-rose-100 text-rose-700 @endif">
                                {{ str_replace('_', ' ', ucfirst($social->grade)) }}
                            </span>
                            @else
                            <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button onclick="openModal('{{ $student->id }}', '{{ $student->name }}')" class="px-4 py-2 bg-primary-50 text-primary-600 font-bold text-xs rounded-xl hover:bg-primary-100">
                                Nilai
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="gradeModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-8 w-full max-w-md mx-4">
        <h3 class="text-xl font-bold text-slate-900 mb-4">Nilai Sikap: <span id="modalStudentName"></span></h3>
        <form method="POST" action="{{ route('tenant.school.grades.sikap.store', ['school' => $schoolSlug]) }}">
            @csrf
            <input type="hidden" name="student_id" id="modalStudentId">
            <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">
            <input type="hidden" name="semester" value="{{ $semester }}">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Aspek</label>
                    <select name="aspect" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary-500">
                        <option value="spiritual">Spiritual</option>
                        <option value="social">Sosial</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Predikat</label>
                    <select name="grade" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary-500">
                        <option value="sangat_baik">Sangat Baik</option>
                        <option value="baik">Baik</option>
                        <option value="cukup">Cukup</option>
                        <option value="kurang">Kurang</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary-500"></textarea>
                </div>
            </div>
            
            <div class="flex gap-4 mt-6">
                <button type="button" onclick="closeModal()" class="flex-1 px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-6 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(studentId, studentName) {
    document.getElementById('modalStudentId').value = studentId;
    document.getElementById('modalStudentName').textContent = studentName;
    document.getElementById('gradeModal').classList.remove('hidden');
    document.getElementById('gradeModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('gradeModal').classList.add('hidden');
    document.getElementById('gradeModal').classList.remove('flex');
}
</script>
@endsection
