@extends('layouts.tenant-platform')

@section('title', 'Detail Audit Log')

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
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
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
    <!-- Header -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="flex items-center gap-4 animate-fade-in-up">
            <a href="{{ route('tenant.audit.index') }}" 
               class="px-4 py-2 text-slate-600 hover:text-slate-900 font-medium transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-slate-900">Detail Audit Log</h1>
                <p class="text-slate-600 mt-1">Informasi lengkap aktivitas sistem</p>
            </div>
        </div>
    </div>

    <!-- Audit Details -->
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Activity Info -->
                <div class="glass-effect rounded-2xl p-6 animate-fade-in-up hover-lift">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-slate-900 mb-1">Informasi Aktivitas</h2>
                            <p class="text-slate-600">Detail lengkap aktivitas yang tercatat</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">ID Aktivitas</label>
                            <div class="px-4 py-3 bg-slate-50 rounded-xl border border-slate-200">
                                <span class="text-slate-900 font-mono">#{{ $audit->id }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Waktu Aktivitas</label>
                            <div class="px-4 py-3 bg-slate-50 rounded-xl border border-slate-200">
                                <div class="text-slate-900">{{ $audit->created_at->format('d M Y, H:i:s') }}</div>
                                <div class="text-sm text-slate-500">{{ $audit->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis Aksi</label>
                            <div class="px-4 py-3 bg-slate-50 rounded-xl border border-slate-200">
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                    @if($audit->action == 'create') bg-green-100 text-green-700
                                    @elseif($audit->action == 'update') bg-blue-100 text-blue-700
                                    @elseif($audit->action == 'delete') bg-red-100 text-red-700
                                    @elseif($audit->action == 'login') bg-purple-100 text-purple-700
                                    @else bg-slate-100 text-slate-700
                                    @endif">
                                    {{ ucfirst($audit->action ?? '-') }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                            <div class="px-4 py-3 bg-slate-50 rounded-xl border border-slate-200">
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                    @if($audit->status == 'success') bg-emerald-100 text-emerald-700
                                    @elseif($audit->status == 'failed') bg-red-100 text-red-700
                                    @else bg-slate-100 text-slate-700
                                    @endif">
                                    {{ ucfirst($audit->status ?? '-') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Module Information -->
                <div class="glass-effect rounded-2xl p-6 animate-fade-in-up hover-lift animate-delay-1">
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Informasi Modul</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Modul</label>
                            <div class="px-4 py-3 bg-slate-50 rounded-xl border border-slate-200">
                                <span class="text-slate-900">{{ $audit->module ?? '-' }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tabel/Model</label>
                            <div class="px-4 py-3 bg-slate-50 rounded-xl border border-slate-200">
                                <span class="text-slate-900">{{ $audit->table_name ?? '-' }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Record ID</label>
                            <div class="px-4 py-3 bg-slate-50 rounded-xl border border-slate-200">
                                <span class="text-slate-900 font-mono">{{ $audit->record_id ?? '-' }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">URL</label>
                            <div class="px-4 py-3 bg-slate-50 rounded-xl border border-slate-200">
                                <span class="text-slate-900 text-sm">{{ $audit->url ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Changes -->
                @if($audit->old_values || $audit->new_values)
                    <div class="glass-effect rounded-2xl p-6 animate-fade-in-up hover-lift animate-delay-2">
                        <h3 class="text-xl font-bold text-slate-900 mb-4">Perubahan Data</h3>
                        
                        @if($audit->old_values)
                            <div class="mb-6">
                                <h4 class="font-semibold text-slate-700 mb-3">Data Sebelumnya</h4>
                                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                                    <pre class="text-sm text-red-800 whitespace-pre-wrap">{{ json_encode($audit->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                        @endif
                        
                        @if($audit->new_values)
                            <div>
                                <h4 class="font-semibold text-slate-700 mb-3">Data Baru</h4>
                                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                                    <pre class="text-sm text-green-800 whitespace-pre-wrap">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- User Information -->
                <div class="glass-effect rounded-2xl p-6 animate-fade-in-up hover-lift animate-delay-1">
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Informasi Pengguna</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                <span class="text-lg font-medium text-indigo-600">{{ substr($audit->user_name ?? 'System', 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="font-semibold text-slate-900">{{ $audit->user_name ?? 'System' }}</div>
                                <div class="text-sm text-slate-500">{{ $audit->user_email ?? '-' }}</div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">User ID</label>
                                <div class="text-slate-900">{{ $audit->user_id ?? '-' }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Role</label>
                                <div class="text-slate-900">{{ $audit->user_role ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technical Information -->
                <div class="glass-effect rounded-2xl p-6 animate-fade-in-up hover-lift animate-delay-2">
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Informasi Teknis</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">IP Address</label>
                            <div class="text-slate-900 font-mono">{{ $audit->ip_address ?? '-' }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">User Agent</label>
                            <div class="text-slate-900 text-sm break-all">{{ $audit->user_agent ?? '-' }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Session ID</label>
                            <div class="text-slate-900 font-mono text-sm">{{ $audit->session_id ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="glass-effect rounded-2xl p-6 animate-fade-in-up hover-lift animate-delay-3">
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Aksi</h3>
                    <div class="space-y-3">
                        <a href="{{ route('tenant.audit.export') }}" 
                           class="w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Export Log
                        </a>
                        
                        <a href="{{ route('tenant.audit.index') }}" 
                           class="block w-full px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors text-center">
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
