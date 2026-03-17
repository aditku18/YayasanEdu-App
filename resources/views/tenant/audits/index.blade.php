@extends('layouts.tenant-platform')

@section('title', 'Audit Log')

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
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
    }
    .audit-item {
        transition: all 0.3s ease;
    }
    .audit-item:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto mb-12">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-500 p-8 md:p-12 text-white animate-fade-in-up shadow-2xl shadow-indigo-500/20">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2 animate-pulse-slow" style="animation-delay: 1s;"></div>

            <div class="relative z-10 text-center">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">Audit Log</h1>
                </div>
                <p class="text-indigo-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Pantau dan lacak semua aktivitas sistem untuk keamanan dan compliance
                </p>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $audits->total() }}</p>
                        <p class="text-indigo-100 text-sm">Total Aktivitas</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">24/7</p>
                        <p class="text-indigo-100 text-sm">Real-time Tracking</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">100%</p>
                        <p class="text-indigo-100 text-sm">Complete Logs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="glass-effect rounded-2xl p-6 animate-fade-in-up">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="flex flex-col md:flex-row gap-4 flex-1">
                    <input type="text" placeholder="Cari aktivitas..." 
                           class="px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all flex-1">
                    <select class="px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        <option>Semua Tipe</option>
                        <option>Login</option>
                        <option>Create</option>
                        <option>Update</option>
                        <option>Delete</option>
                    </select>
                    <input type="date" 
                           class="px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>
                <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="max-w-7xl mx-auto">
        <div class="glass-effect rounded-2xl overflow-hidden animate-fade-in-up animate-delay-1">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Pengguna</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Modul</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">IP Address</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($audits as $audit)
                            <tr class="hover:bg-slate-50 transition-colors audit-item">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">{{ $audit->created_at->format('d M Y, H:i') }}</div>
                                    <div class="text-xs text-slate-500">{{ $audit->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-medium text-indigo-600">{{ substr($audit->user_name ?? 'System', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-slate-900">{{ $audit->user_name ?? 'System' }}</div>
                                            <div class="text-xs text-slate-500">{{ $audit->user_email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                        @if($audit->action == 'create') bg-green-100 text-green-700
                                        @elseif($audit->action == 'update') bg-blue-100 text-blue-700
                                        @elseif($audit->action == 'delete') bg-red-100 text-red-700
                                        @elseif($audit->action == 'login') bg-purple-100 text-purple-700
                                        @else bg-slate-100 text-slate-700
                                        @endif">
                                        {{ ucfirst($audit->action ?? '-') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">{{ $audit->module ?? '-' }}</div>
                                    <div class="text-xs text-slate-500">{{ $audit->table_name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">{{ $audit->ip_address ?? '-' }}</div>
                                    <div class="text-xs text-slate-500">{{ $audit->user_agent ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                        @if($audit->status == 'success') bg-emerald-100 text-emerald-700
                                        @elseif($audit->status == 'failed') bg-red-100 text-red-700
                                        @else bg-slate-100 text-slate-700
                                        @endif">
                                        {{ ucfirst($audit->status ?? '-') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('tenant.audit.show', $audit->id) }}" 
                                       class="text-indigo-600 hover:text-indigo-800 font-medium text-sm transition-colors">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div class="text-slate-500 font-medium">Belum ada aktivitas</div>
                                        <div class="text-slate-400 text-sm">Aktivitas sistem akan muncul di sini</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($audits->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-slate-600">
                            Menampilkan {{ $audits->firstItem() }} hingga {{ $audits->lastItem() }} dari {{ $audits->total() }} aktivitas
                        </div>
                        {{ $audits->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Audit Tips Card -->
    <div class="max-w-7xl mx-auto mt-8">
        <div class="glass-effect rounded-2xl p-6 border border-indigo-200 bg-indigo-50 animate-fade-in-up">
            <div class="flex gap-4">
                <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-indigo-900 mb-2">📋 Tips Audit Log</h4>
                    <p class="text-indigo-700 mb-2">
                        Manfaatkan audit log untuk monitoring keamanan sistem:
                    </p>
                    <ul class="text-indigo-700 space-y-1 text-sm">
                        <li>• Monitor aktivitas mencurigakan secara real-time</li>
                        <li>• Lacak perubahan data penting</li>
                        <li>• Identifikasi pola akses tidak normal</li>
                        <li>• Export log untuk keperluan compliance</li>
                        <li>• Set alert untuk aktivitas kritis</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
