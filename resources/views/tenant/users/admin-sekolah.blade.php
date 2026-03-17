@extends('layouts.tenant-platform')

@section('title', 'Admin Sekolah')

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
    .animate-slide-in-left {
        animation: slideInLeft 0.8s ease-out forwards;
        opacity: 0;
    }
    .animate-pulse-slow {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    .animate-delay-1 { animation-delay: 0.1s; }
    .animate-delay-2 { animation-delay: 0.2s; }
    .animate-delay-3 { animation-delay: 0.3s; }
    .glass-effect {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto mb-12">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-600 via-emerald-500 to-teal-500 p-8 md:p-12 text-white animate-fade-in-up shadow-2xl shadow-emerald-500/20">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2 animate-pulse-slow" style="animation-delay: 1s;"></div>
            
            <div class="relative z-10 text-center">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">Admin Sekolah</h1>
                </div>
                <p class="text-emerald-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Kelola administrator sekolah dan pengelolaan unit pendidikan
                </p>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $users->count() }}</p>
                        <p class="text-emerald-100 text-sm">Total Admin</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $users->where('email_verified_at', '!=', null)->count() }}</p>
                        <p class="text-emerald-100 text-sm">Terverifikasi</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $users->where('email_verified_at', null)->count() }}</p>
                        <p class="text-emerald-100 text-sm">Belum Terverifikasi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table Section -->
    <div class="max-w-7xl mx-auto">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">Daftar Administrator Sekolah</h2>
                            <p class="text-slate-600">Kelola hak akses administrator sekolah</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-black text-emerald-600">{{ $users->count() }}</p>
                        <p class="text-sm text-slate-500 font-medium">Total Admin</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                @if($users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="text-left p-4 font-bold text-slate-900">Nama</th>
                                    <th class="text-left p-4 font-bold text-slate-900">Email</th>
                                    <th class="text-left p-4 font-bold text-slate-900">Status Verifikasi</th>
                                    <th class="text-left p-4 font-bold text-slate-900">Terakhir Login</th>
                                    <th class="text-left p-4 font-bold text-slate-900">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $index => $user)
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors animate-slide-in-left {{ $index % 3 === 0 ? 'animate-delay-1' : ($index % 3 === 1 ? 'animate-delay-2' : 'animate-delay-3') }}">
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center">
                                                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-900">{{ $user->name }}</p>
                                                    <p class="text-sm text-slate-600">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span class="text-slate-700">{{ $user->email }}</span>
                                        </td>
                                        <td class="p-4">
                                            @if($user->email_verified_at)
                                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Terverifikasi</span>
                                            @else
                                                <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">Belum Terverifikasi</span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            <span class="text-slate-600">{{ $user->last_login_at ? $user->last_login_at->format('d M Y') : 'Belum pernah login' }}</span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex items-center gap-2">
                                                <button class="p-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg transition-colors" title="Edit User">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-4h-4v4m0 0h4m0 0v-4"/>
                                                    </svg>
                                                </button>
                                                <button class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors" title="Delete User">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116 2.828H5.07a2 2 0 01-2.828 1.414L12 4.586A7.001 7.001 0 0010 10V17a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-8">
                            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Belum Ada Administrator Sekolah</h3>
                        <p class="text-slate-600 max-w-md mx-auto mb-8">Belum ada administrator sekolah yang terdaftar dalam sistem.</p>
                        <button class="group px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0 0v6m0 0v1m0-1c0 1.11.89 2 2 2h2a2 2 0 002-2v-1"/>
                                </svg>
                                Tambah Administrator Sekolah
                            </span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
