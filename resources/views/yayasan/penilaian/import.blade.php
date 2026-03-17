@extends('layouts.dashboard')

@section('title', 'Import Nilai')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Import Nilai</h1>
        <p class="text-slate-500 mt-1">Import nilai siswa dari file Excel.</p>
    </div>

    <!-- Form -->
    <div class="bg-white p-8 rounded-3xl border border-slate-100 premium-shadow">
        <form method="POST" action="{{ route('tenant.school.grades.import', ['school' => $schoolSlug]) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">File Excel</label>
                <input type="file" name="file" accept=".xlsx,.xls" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                <p class="text-xs text-slate-500 mt-2">Format: .xlsx atau .xls</p>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl">
                <h3 class="font-bold text-slate-900 mb-2">Format File</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th class="text-left py-1">Kolom</th>
                            <th class="text-left py-1">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-1">A</td>
                            <td class="py-1">NIS siswa</td>
                        </tr>
                        <tr>
                            <td class="py-1">B</td>
                            <td class="py-1">ID Mata Pelajaran</td>
                        </tr>
                        <tr>
                            <td class="py-1">C</td>
                            <td class="py-1">Nilai</td>
                        </tr>
                        <tr>
                            <td class="py-1">D</td>
                            <td class="py-1">Jenis nilai (daily/assignment/midterm/final)</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('tenant.school.grades.index', ['school' => $schoolSlug]) }}" class="px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700">
                    Import Nilai
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
