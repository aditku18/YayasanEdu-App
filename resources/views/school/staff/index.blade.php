@extends('layouts.dashboard')

@section('title', 'Daftar Staff Unit')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Staff Administrasi</h1>
            <p class="text-slate-500 mt-1">Kelola personil non-akademik di unit sekolah Anda.</p>
        </div>
        <button onclick="location.href='{{ $schoolSlug ? route('tenant.school.staff.create', ['school' => $schoolSlug]) : route('tenant.staff.create') }}'" class="px-6 py-3 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition-all shadow-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Tambah Staff
        </button>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Nama</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Email</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Login Terakhir</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($staffs as $staff)
                <tr class="hover:bg-slate-50/30 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold">{{ substr($staff->name, 0, 1) }}</div>
                            <span class="font-bold text-slate-900">{{ $staff->name }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-sm font-medium text-slate-500">{{ $staff->email }}</td>
                    <td class="px-8 py-5 text-sm font-medium text-slate-400 italic">Belum pernah login</td>
                    <td class="px-8 py-5 text-right">
                        <button class="p-2 text-slate-300 hover:text-slate-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-medium italic">Belum ada staff yang terdaftar untuk unit ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-6">
            {{ $staffs->links() }}
        </div>
    </div>
</div>
@endsection
