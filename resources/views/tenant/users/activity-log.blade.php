@extends('layouts.tenant-platform')

@section('title', 'Log Aktivitas')

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
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary-600 via-primary-500 to-indigo-500 p-8 md:p-12 text-white animate-fade-in-up shadow-2xl shadow-primary-500/20">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2 animate-pulse-slow" style="animation-delay: 1s;"></div>
            
            <div class="relative z-10 text-center">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">Log Aktivitas</h1>
                </div>
                <p class="text-primary-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Pantau dan lacak semua aktivitas yang terjadi dalam sistem
                </p>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $activities->count() }}</p>
                        <p class="text-primary-100 text-sm">Total Aktivitas</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $activities->where('created_at', '>=', now()->subDay())->count() }}</p>
                        <p class="text-primary-100 text-sm">Hari Ini</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $activities->where('created_at', '>=', now()->subWeek())->count() }}</p>
                        <p class="text-primary-100 text-sm">Minggu Ini</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Action Buttons Section -->
    <div class="max-w-7xl mx-auto mb-6">
        <div class="glass-effect rounded-2xl p-6 animate-fade-in-up">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <!-- Filters -->
                <div class="flex flex-wrap gap-3">
                    <select id="moduleFilter" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Module</option>
                        <option value="user">Pengguna</option>
                        <option value="student">Siswa</option>
                        <option value="teacher">Guru</option>
                        <option value="invoice">Tagihan</option>
                        <option value="payment">Pembayaran</option>
                    </select>
                    
                    <select id="actionFilter" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Aksi</option>
                        <option value="create">Membuat</option>
                        <option value="update">Mengubah</option>
                        <option value="delete">Menghapus</option>
                    </select>
                    
                    <input type="date" id="startDate" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <input type="date" id="endDate" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    
                    <button onclick="applyFilters()" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                    </button>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button onclick="showExportModal()" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export
                    </button>
                    <button onclick="showClearModal()" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Bersihkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Activities Table Section -->
    <div class="max-w-7xl mx-auto">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">Riwayat Aktivitas</h2>
                            <p class="text-slate-600">Log aktivitas pengguna dan sistem</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-black text-primary-600">{{ $activities->count() }}</p>
                        <p class="text-sm text-slate-500 font-medium">Total Aktivitas</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                @if($activities->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="text-left p-4 font-bold text-slate-900">Waktu</th>
                                    <th class="text-left p-4 font-bold text-slate-900">Pengguna</th>
                                    <th class="text-left p-4 font-bold text-slate-900">Module</th>
                                    <th class="text-left p-4 font-bold text-slate-900">Aksi</th>
                                    <th class="text-left p-4 font-bold text-slate-900">Deskripsi</th>
                                    <th class="text-left p-4 font-bold text-slate-900">IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activities as $index => $activity)
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors animate-slide-in-left {{ $index % 3 === 0 ? 'animate-delay-1' : ($index % 3 === 1 ? 'animate-delay-2' : 'animate-delay-3') }}">
                                        <td class="p-4">
                                            <div class="flex flex-col">
                                                <span class="text-slate-900 font-medium">{{ $activity->created_at->format('d M Y') }}</span>
                                                <span class="text-slate-600 text-sm">{{ $activity->created_at->format('H:i:s') }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center">
                                                    <span class="text-white font-bold text-xs">{{ $activity->user ? strtoupper(substr($activity->user->name, 0, 1)) : 'S' }}</span>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-900">{{ $activity->user ? $activity->user->name : 'System' }}</p>
                                                    <p class="text-sm text-slate-600">{{ $activity->user ? $activity->user->email : 'system@app.com' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">{{ $activity->getModuleDisplayName() }}</span>
                                        </td>
                                        <td class="p-4">
                                            <span class="px-3 py-1 bg-primary-100 text-primary-700 text-xs font-bold rounded-full">{{ $activity->getActionDescription() }}</span>
                                        </td>
                                        <td class="p-4">
                                            <div class="text-slate-700">
                                                @if($activity->reference_type)
                                                    <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $activity->reference_type)) }}</span>
                                                    @if($activity->reference_id)
                                                        <span class="text-slate-500"> #{{ $activity->reference_id }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-slate-500">-</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span class="text-slate-600 font-mono text-sm">{{ $activity->ip_address ?? '-' }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-8 flex items-center justify-center">
                        {{ $activities->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-8">
                            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Belum Ada Aktivitas</h3>
                        <p class="text-slate-600 max-w-md mx-auto mb-8">Belum ada aktivitas yang tercatat dalam sistem.</p>
                        <div class="flex items-center justify-center gap-4">
                            <button onclick="refreshActivityLog()" class="group px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Refresh Log
                            </span>
                        </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-t-2xl">
            <h3 class="text-xl font-bold text-white">Export Log Aktivitas</h3>
        </div>
        <form id="exportForm" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal Mulai</label>
                <input type="date" id="exportStartDate" name="start_date" required
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal Selesai</label>
                <input type="date" id="exportEndDate" name="end_date" required
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Format</label>
                <select id="exportFormat" name="format" required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="csv">CSV</option>
                    <option value="xlsx">Excel</option>
                </select>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300">
                    Export
                </button>
                <button type="button" onclick="closeExportModal()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2 px-4 rounded-lg transition-all duration-300">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Clear Log Modal -->
<div id="clearModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <div class="bg-gradient-to-r from-red-500 to-red-600 p-6 rounded-t-2xl">
            <h3 class="text-xl font-bold text-white">Bersihkan Log Aktivitas</h3>
        </div>
        <form id="clearForm" class="p-6">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Hapus log aktivitas yang lebih lama dari</label>
                <select id="clearDays" name="days" required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="7">7 hari</option>
                    <option value="30" selected>30 hari</option>
                    <option value="90">90 hari</option>
                    <option value="180">180 hari</option>
                    <option value="365">1 tahun</option>
                </select>
                <p class="text-slate-600 text-sm mt-2">Log yang lebih lama dari periode yang dipilih akan dihapus secara permanen.</p>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300">
                    Bersihkan
                </button>
                <button type="button" onclick="closeClearModal()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2 px-4 rounded-lg transition-all duration-300">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showExportModal() {
    // Set default dates (last 30 days)
    const endDate = new Date();
    const startDate = new Date();
    startDate.setDate(endDate.getDate() - 30);
    
    document.getElementById('exportStartDate').value = startDate.toISOString().split('T')[0];
    document.getElementById('exportEndDate').value = endDate.toISOString().split('T')[0];
    document.getElementById('exportModal').classList.remove('hidden');
}

function closeExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
}

function showClearModal() {
    document.getElementById('clearModal').classList.remove('hidden');
}

function closeClearModal() {
    document.getElementById('clearModal').classList.add('hidden');
}

function applyFilters() {
    const module = document.getElementById('moduleFilter').value;
    const action = document.getElementById('actionFilter').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    const params = new URLSearchParams();
    if (module) params.append('module', module);
    if (action) params.append('action', action);
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    
    window.location.href = '/activity?' + params.toString();
}

function refreshActivityLog() {
    window.location.reload();
}

// Handle export form submission
document.getElementById('exportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/activity/export', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            start_date: formData.get('start_date'),
            end_date: formData.get('end_date'),
            format: formData.get('format')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // For now, just show success. In real implementation, 
            // you would trigger file download here
            alert('Export berhasil! Data akan segera diunduh.');
            closeExportModal();
        } else {
            alert('Gagal mengekspor: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error exporting:', error);
        alert('Gagal mengekspor. Silakan coba lagi.');
    });
});

// Handle clear form submission
document.getElementById('clearForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    if (!confirm('Apakah Anda yakin ingin menghapus log aktivitas? Tindakan ini tidak dapat dibatalkan.')) {
        return;
    }
    
    fetch('/activity/clear', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            days: formData.get('days')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeClearModal();
            location.reload();
        } else {
            alert('Gagal membersihkan log: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error clearing logs:', error);
        alert('Gagal membersihkan log. Silakan coba lagi.');
    });
});
</script>
@endsection
