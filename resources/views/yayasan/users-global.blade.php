@extends('layouts.dashboard')

@section('title', 'Users Global')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Daftar Admin & Staff</h1>
            <p class="text-slate-500 mt-1">Kelola seluruh kredensial pengelola di level Yayasan dan Unit.</p>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Nama</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Email / Role</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Unit</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($users as $user)
                <tr class="hover:bg-slate-50/30 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center font-bold">{{ substr($user->name, 0, 1) }}</div>
                            <span class="font-bold text-slate-900">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <p class="text-sm font-medium text-slate-600">{{ $user->email }}</p>
                        <p class="text-[10px] font-bold text-primary-500 uppercase tracking-wider mt-0.5">{{ $user->role }}</p>
                    </td>
                    <td class="px-8 py-5 text-sm font-bold text-slate-500">
                        {{ $user->school?->name ?? 'Pusat (Yayasan)' }}
                    </td>
                    <td class="px-8 py-5 text-sm font-bold text-slate-500">
                        @if($user->email_verified_at)
                            <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] uppercase tracking-widest">Verified</span>
                        @else
                            <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] uppercase tracking-widest">Unverified</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-6">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
