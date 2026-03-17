@extends('layouts.tenant-platform')

@section('title', 'Pengaturan Backup')

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
    .backup-item {
        transition: all 0.3s ease;
    }
    .backup-item:hover {
        transform: translateY(-2px);
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">Pengaturan Backup</h1>
                </div>
                <p class="text-emerald-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Amankan data penting Anda dengan sistem backup otomatis dan terjadwal
                </p>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">99.9%</p>
                        <p class="text-emerald-100 text-sm">Data Recovery</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">24/7</p>
                        <p class="text-emerald-100 text-sm">Auto Backup</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">30</p>
                        <p class="text-emerald-100 text-sm">Hari Retensi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto mb-6">
            <div class="glass-effect rounded-2xl p-6 border border-emerald-200 bg-emerald-50 animate-fade-in-up">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-emerald-800">Berhasil Disimpan!</p>
                        <p class="text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto mb-6">
            <div class="glass-effect rounded-2xl p-6 border border-red-200 bg-red-50 animate-fade-in-up">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-red-800">Terjadi Kesalahan!</p>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Backup Settings -->
    <div class="max-w-7xl mx-auto">
        <form method="POST" action="{{ route('tenant.setting.backup.update') }}" class="space-y-8">
            @csrf
            
            <!-- Backup Schedule -->
            <div class="backup-item glass-effect rounded-2xl p-8 hover-lift animate-fade-in-up animate-delay-1">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Jadwal Backup Otomatis</h3>
                        <p class="text-slate-600">Atur frekuensi dan waktu backup otomatis</p>
                    </div>
                    <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">Scheduled</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Frekuensi Backup</label>
                        <select name="backup_frequency" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="daily" {{ old('backup_frequency', 'daily') == 'daily' ? 'selected' : '' }}>Harian</option>
                            <option value="weekly" {{ old('backup_frequency') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                            <option value="monthly" {{ old('backup_frequency') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Waktu Backup</label>
                        <input type="time" name="backup_time" value="{{ old('backup_time', '02:00') }}" 
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <p class="text-xs text-slate-500 mt-1">Backup akan berjalan pada waktu yang ditentukan</p>
                    </div>
                </div>
                
                <div class="mt-6 space-y-3">
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                        <input type="checkbox" name="backup_database" value="1" {{ old('backup_database', 1) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <div>
                            <div class="font-semibold text-slate-900">Backup Database</div>
                            <div class="text-sm text-slate-500">Semua data dan tabel database</div>
                        </div>
                    </label>
                    
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                        <input type="checkbox" name="backup_files" value="1" {{ old('backup_files', 1) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <div>
                            <div class="font-semibold text-slate-900">Backup File Upload</div>
                            <div class="text-sm text-slate-500">Dokumen, gambar, dan file lainnya</div>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Backup Retention -->
            <div class="backup-item glass-effect rounded-2xl p-8 hover-lift animate-fade-in-up animate-delay-2">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Penyimpanan Backup</h3>
                        <p class="text-slate-600">Kelola lokasi dan retensi backup</p>
                    </div>
                    <span class="px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">Storage</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Simpan backup selama (hari)</label>
                        <input type="number" name="retention_days" value="{{ old('retention_days', 30) }}" min="7" max="365" 
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <p class="text-xs text-slate-500 mt-1">Backup lama akan otomatis dihapus</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Lokasi penyimpanan</label>
                        <select name="backup_location" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            <option value="local" {{ old('backup_location', 'local') == 'local' ? 'selected' : '' }}>Lokal</option>
                            <option value="cloud" {{ old('backup_location') == 'cloud' ? 'selected' : '' }}>Cloud</option>
                        </select>
                        <p class="text-xs text-slate-500 mt-1">Pilih lokasi penyimpanan backup</p>
                    </div>
                </div>
            </div>
            
            <!-- Manual Backup -->
            <div class="backup-item glass-effect rounded-2xl p-8 hover-lift animate-fade-in-up animate-delay-3">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Backup Manual</h3>
                        <p class="text-slate-600">Buat backup instan kapan saja</p>
                    </div>
                    <span class="px-4 py-2 bg-orange-100 text-orange-700 rounded-full text-sm font-medium">Instant</span>
                </div>
                
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-semibold text-slate-900 mb-1">Status Backup Terakhir</h4>
                            <p class="text-sm text-slate-600">
                                <span id="lastBackupStatus">{{ $lastBackup ?? 'Belum pernah' }}</span>
                            </p>
                        </div>
                        <button type="button" onclick="createBackup()" class="px-6 py-3 bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 text-white font-semibold rounded-xl shadow-lg transition-all duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Backup Sekarang
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="glass-effect rounded-2xl p-6 animate-fade-in-up">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-center md:text-left">
                        <h4 class="font-semibold text-slate-900 mb-1">Simpan Pengaturan Backup</h4>
                        <p class="text-sm text-slate-500">Perubahan akan diterapkan pada backup berikutnya</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="resetForm()" class="px-6 py-3 text-slate-600 hover:text-slate-900 font-medium transition-colors">
                            Reset
                        </button>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-semibold rounded-xl shadow-lg transition-all duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Backup Tips Card -->
    <div class="max-w-7xl mx-auto mt-8">
        <div class="glass-effect rounded-2xl p-6 border border-emerald-200 bg-emerald-50 animate-fade-in-up">
            <div class="flex gap-4">
                <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-emerald-900 mb-2">💾 Tips Backup</h4>
                    <p class="text-emerald-700 mb-2">
                        Pastikan strategi backup Anda optimal untuk keamanan data:
                    </p>
                    <ul class="text-emerald-700 space-y-1 text-sm">
                        <li>• Lakukan backup hararian untuk data kritis</li>
                        <li>• Simpan backup di lokasi terpisah (offsite)</li>
                        <li>• Test restore backup secara berkala</li>
                        <li>• Monitor kapasitas penyimpanan backup</li>
                        <li>• Gunakan enkripsi untuk data sensitif</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset semua pengaturan backup?')) {
        document.querySelector('form').reset();
    }
}

function createBackup() {
    if (confirm('Apakah Anda yakin ingin membuat backup sekarang? Proses ini mungkin memakan waktu beberapa menit.')) {
        // Update status
        document.getElementById('lastBackupStatus').innerHTML = '<span class="text-orange-600">Sedang membuat backup...</span>';
        
        fetch('{{ route("tenant.setting.backup.create") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('lastBackupStatus').innerHTML = '<span class="text-green-600">Backup berhasil dimulai!</span>';
                setTimeout(() => {
                    document.getElementById('lastBackupStatus').innerHTML = '<span class="text-green-600">' + new Date().toLocaleString('id-ID') + '</span>';
                }, 2000);
            } else {
                document.getElementById('lastBackupStatus').innerHTML = '<span class="text-red-600">Gagal: ' + data.message + '</span>';
            }
        })
        .catch(error => {
            document.getElementById('lastBackupStatus').innerHTML = '<span class="text-red-600">Terjadi kesalahan: ' + error.message + '</span>';
        });
    }
}
</script>
@endsection
