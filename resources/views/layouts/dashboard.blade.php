<x-platform-layout>
    <x-slot name="header">Dashboard</x-slot>
    <x-slot name="subtitle">Overview sistem secara real-time</x-slot>
<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-navy-700 via-navy-600 to-army-700 rounded-2xl p-6 mb-6 text-white shadow-navy relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-gold-500/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
            <h3 class="text-2xl font-display font-bold mb-1">Selamat Datang, {{ explode(' ', Auth::user()->name ?? 'Admin')[0] }}! 👋</h3>
            <p class="text-navy-200 text-sm">Berikut ringkasan aktivitas platform hari ini</p>
        </div>
        <div class="mt-4 md:mt-0 flex items-center space-x-3">
            <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-white/10 backdrop-blur-sm border border-white/20">
                <span class="w-2.5 h-2.5 bg-success rounded-full mr-2.5 animate-pulse"></span>
                Sistem Aktif
            </span>
            <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-gold-500/20 backdrop-blur-sm border border-gold-500/30 text-gold-400">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ now()->format('H:i') }} WITA
            </span>
        </div>
    </div>
</div>

<!-- Stats Grid - 5 Cards Overview (Platform Level) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
    <!-- Total Yayasan -->
    <div class="bg-white rounded-2xl shadow-card hover:shadow-card-hover transition-all duration-300 p-6 group cursor-pointer transform hover:-translate-y-1">
        <div class="flex items-start justify-between">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-navy-600 to-navy-700 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div class="flex items-center space-x-1 text-sm font-medium">
                <span class="text-success flex items-center">
                    <svg class="w-4 h-4 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                    12%
                </span>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm font-medium text-gray-500">Total Yayasan</p>
            <p class="text-3xl font-display font-bold text-gray-900 mt-1">{{ $stats['total_foundations'] ?? 0 }}</p>
        </div>
        <div class="mt-3 flex items-center text-xs text-gray-400">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Diperbarui {{ now()->diffForHumans() }}
        </div>
    </div>

    <!-- Total Unit Sekolah -->
    <div class="bg-white rounded-2xl shadow-card hover:shadow-card-hover transition-all duration-300 p-6 group cursor-pointer transform hover:-translate-y-1">
        <div class="flex items-start justify-between">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-500 to-sky-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                </svg>
            </div>
            <div class="flex items-center space-x-1 text-sm font-medium">
                <span class="text-success flex items-center">
                    <svg class="w-4 h-4 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                    8%
                </span>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm font-medium text-gray-500">Total Unit Sekolah</p>
            <p class="text-3xl font-display font-bold text-gray-900 mt-1">{{ $stats['active_foundations'] ?? 0 }}</p>
        </div>
        <div class="mt-3 flex items-center text-xs text-gray-400">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Diperbarui {{ now()->diffForHumans() }}
        </div>
    </div>

    <!-- Admin Platform -->
    <div class="bg-white rounded-2xl shadow-card hover:shadow-card-hover transition-all duration-300 p-6 group cursor-pointer transform hover:-translate-y-1">
        <div class="flex items-start justify-between">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-gold-500 to-gold-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div class="flex items-center space-x-1 text-sm font-medium">
                <span class="text-success flex items-center">
                    <svg class="w-4 h-4 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                    23%
                </span>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm font-medium text-gray-500">Total Siswa</p>
            <p class="text-3xl font-display font-bold text-gray-900 mt-1">12,450</p>
        </div>
        <div class="mt-3 flex items-center text-xs text-gray-400">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0            </svg>
z"></path>
            Diperbarui {{ now()->diffForHumans() }}
        </div>
    </div>

    <!-- Unit Belum Setup -->
    <div class="bg-white rounded-2xl shadow-card hover:shadow-card-hover transition-all duration-300 p-6 group cursor-pointer transform hover:-translate-y-1">
        <div class="flex items-start justify-between">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-secondary-500 to-secondary-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div class="flex items-center space-x-1 text-sm font-medium">
                <span class="text-danger flex items-center">
                    <svg class="w-4 h-4 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    2%
                </span>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm font-medium text-gray-500">Total Guru & Karyawan</p>
            <p class="text-3xl font-display font-bold text-gray-900 mt-1">3,280</p>
        </div>
        <div class="mt-3 flex items-center text-xs text-gray-400">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Diperbarui {{ now()->diffForHumans() }}
        </div>
    </div>

    <!-- Status Sistem -->
    <div class="bg-white rounded-2xl shadow-card hover:shadow-card-hover transition-all duration-300 p-6 group cursor-pointer transform hover:-translate-y-1 border-l-4 border-success">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-success to-emerald-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-success/10 text-success">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Healthy
            </span>
        </div>
        <div class="mt-4">
            <p class="text-sm font-medium text-gray-500">Status Sistem</p>
            <p class="text-3xl font-display font-bold text-gray-900 mt-1">99.9%</p>
        </div>
        <div class="mt-3 flex items-center text-xs text-gray-400">
            <span class="text-success">Uptime Server</span>
        </div>
    </div>
</div>

<!-- Charts Row - Visual Statistics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Bar Chart - Unit per Yayasan -->
    <div class="bg-white rounded-2xl shadow-card p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h4 class="text-lg font-display font-semibold text-gray-900">Unit per Yayasan</h4>
                <p class="text-sm text-gray-500">Distribusi unit sekolah per yayasan</p>
            </div>
            <button class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                </svg>
            </button>
        </div>
        <div class="h-64">
            <canvas id="studentDistributionChart"></canvas>
        </div>
    </div>

    <!-- Pie Chart - Unit per Yayasan -->
    <div class="bg-white rounded-2xl shadow-card p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h4 class="text-lg font-display font-semibold text-gray-900">Yayasan Aktif vs Non-Aktif</h4>
                <p class="text-sm text-gray-500">Status login terakhir yayasan</p>
            </div>
            <button class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                </svg>
            </button>
        </div>
        <div class="h-64 flex items-center justify-center">
            <canvas id="unitPerFoundationChart"></canvas>
        </div>
    </div>
</div>

<!-- Charts Row 2 -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Line Chart - Aktivitas Login -->
    <div class="bg-white rounded-2xl shadow-card p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h4 class="text-lg font-display font-semibold text-gray-900">Pertumbuhan Unit Baru</h4>
                <p class="text-sm text-gray-500">Monitoring pertumbuhan platform bulanan</p>
            </div>
            <div class="flex items-center space-x-2">
                <select class="text-sm border-gray-200 rounded-lg px-3 py-1.5 focus:ring-sky-500 focus:border-sky-500">
                    <option>7 Hari</option>
                    <option>30 Hari</option>
                    <option>90 Hari</option>
                </select>
            </div>
        </div>
        <div class="h-64">
            <canvas id="loginActivityChart"></canvas>
        </div>
    </div>

    <!-- Stacked Bar Chart - Pembayaran SPP -->
    <div class="bg-white rounded-2xl shadow-card p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h4 class="text-lg font-display font-semibold text-gray-900">Kesehatan Sistem</h4>
                <p class="text-sm text-gray-500">Error log & API usage monitoring</p>
            </div>
            <div class="flex items-center space-x-2">
                <select class="text-sm border-gray-200 rounded-lg px-3 py-1.5 focus:ring-sky-500 focus:border-sky-500">
                    <option>Bulan Ini</option>
                    <option>Bulan Lalu</option>
                    <option>3 Bulan</option>
                </select>
            </div>
        </div>
        <div class="h-64">
            <canvas id="sppPaymentChart"></canvas>
        </div>
    </div>
</div>

<!-- Quick Actions & Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-card p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h4 class="text-lg font-display font-semibold text-gray-900">Aksi Cepat</h4>
                <p class="text-sm text-gray-500">Akses cepat ke fitur utama</p>
            </div>
        </div>
        <div class="space-y-3">
            <a href="{{ route('platform.foundations.create') }}" class="flex items-center p-4 rounded-xl border border-gray-200 hover:border-sky-500 hover:bg-sky-50 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-xl bg-navy-100 flex items-center justify-center group-hover:bg-navy-600 transition-colors">
                    <svg class="w-5 h-5 text-navy-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-900">Tambah Yayasan</p>
                    <p class="text-xs text-gray-500">Daftarkan yayasan baru</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-sky-500 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            
            <a href="#" class="flex items-center p-4 rounded-xl border border-gray-200 hover:border-sky-500 hover:bg-sky-50 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center group-hover:bg-sky-500 transition-colors">
                    <svg class="w-5 h-5 text-sky-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-900">Tambah Unit Sekolah</p>
                    <p class="text-xs text-gray-500">Tambah unit ke yayasan</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-sky-500 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            
            <a href="#" class="flex items-center p-4 rounded-xl border border-gray-200 hover:border-sky-500 hover:bg-sky-50 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-xl bg-gold-100 flex items-center justify-center group-hover:bg-gold-500 transition-colors">
                    <svg class="w-5 h-5 text-gold-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-900">Tambah Admin Platform</p>
                    <p class="text-xs text-gray-500">Buat akun admin baru</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-sky-500 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            
            <a href="#" class="flex items-center p-4 rounded-xl border border-gray-200 hover:border-sky-500 hover:bg-sky-50 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-xl bg-secondary-100 flex items-center justify-center group-hover:bg-secondary-500 transition-colors">
                    <svg class="w-5 h-5 text-secondary-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-900">Lihat Laporan Global</p>
                    <p class="text-xs text-gray-500">Akses laporan seluruh sistem</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-sky-500 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            
            <a href="#" class="flex items-center p-4 rounded-xl border border-gray-200 hover:border-sky-500 hover:bg-sky-50 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-xl bg-success/10 flex items-center justify-center group-hover:bg-success transition-colors">
                    <svg class="w-5 h-5 text-success group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-900">Kirim Pengumuman</p>
                    <p class="text-xs text-gray-500">Broadcast ke semua unit</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-sky-500 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Recent Activity - Table Format -->
    <div class="bg-white rounded-2xl shadow-card p-6 lg:col-span-2">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h4 class="text-lg font-display font-semibold text-gray-900">Log Aktivitas Terbaru</h4>
                <p class="text-sm text-gray-500">Aktivitas sistem terkini</p>
            </div>
            <a href="#" class="text-sm text-sky-600 hover:text-sky-700 font-medium">Lihat semua</a>
        </div>
        
        <!-- Filter -->
        <div class="flex items-center space-x-4 mb-4">
            <select class="text-sm border-gray-200 rounded-lg px-3 py-2 focus:ring-sky-500 focus:border-sky-500">
                <option>Semua Aktivitas</option>
                <option>Registrasi Yayasan</option>
                <option>Penambahan Admin</option>
                <option>Update Modul</option>
                <option>Error Sistem</option>
            </select>
            <input type="date" class="text-sm border-gray-200 rounded-lg px-3 py-2 focus:ring-sky-500 focus:border-sky-500">
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aktivitas</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-success/10 flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Yayasan Al-Hidayah menyelesaikan setup wizard</p>
                                    <p class="text-xs text-gray-500">Unit SD Islam Al-Hidayah telah aktif</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">Admin Utama</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">Success</span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-400">5 menit</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Admin baru ditambahkan di Yayasan Tunas Bangsa</p>
                                    <p class="text-xs text-gray-500">3 admin unit baru telah dibuat</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">Super Admin</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-700">Info</span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-400">15 menit</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-gold-100 flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">45 siswa baru terdaftar di SMA Cendekia</p>
                                    <p class="text-xs text-gray-500">Tahun ajaran 2024/2025</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">Admin SMA Cendekia</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gold-100 text-gold-700">Baru</span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-400">1 jam</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-secondary-100 flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Pembayaran SPP berhasil diproses</p>
                                    <p class="text-xs text-gray-500">128 transaksi dari seluruh unit</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">System</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-100 text-secondary-700">Success</span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-400">2 jam</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-warning/10 flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">3 unit belum menyelesaikan setup wizard</p>
                                    <p class="text-xs text-gray-500">Akses terbatas hingga setup selesai</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">System</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">Warning</span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-400">3 jam</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
            <p class="text-sm text-gray-500">Menampilkan 1-5 dari 128 aktivitas</p>
            <div class="flex items-center space-x-2">
                <button class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors disabled:opacity-50" disabled>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button class="px-3 py-1 text-sm font-medium text-white bg-navy-600 rounded-lg">1</button>
                <button class="px-3 py-1 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg">2</button>
                <button class="px-3 py-1 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg">3</button>
                <span class="text-gray-400">...</span>
                <button class="px-3 py-1 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg">26</button>
                <button class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Alerts Section -->
<div class="bg-white rounded-2xl shadow-card p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-warning/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-lg font-display font-semibold text-gray-900">Pemberitahuan Penting</h4>
                <p class="text-sm text-gray-500">Items yang memerlukan perhatian</p>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Alert 1 -->
        <div class="p-4 rounded-xl bg-warning/5 border border-warning/20">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-lg bg-warning/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Setup Wizard Belum Selesai</p>
                    <p class="text-xs text-gray-500 mt-1">3 unit sekolah belum menyelesaikan setup</p>
                </div>
            </div>
        </div>
        
        <!-- Alert 2 -->
        <div class="p-4 rounded-xl bg-danger/5 border border-danger/20">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-lg bg-danger/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Unit Tidak Aktif</p>
                    <p class="text-xs text-gray-500 mt-1">2 yayasan dengan unit tidak aktif</p>
                </div>
            </div>
        </div>
        
        <!-- Alert 3 -->
        <div class="p-4 rounded-xl bg-info/10 border border-info/20">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-lg bg-info/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Update Sistem Tersedia</p>
                    <p class="text-xs text-gray-500 mt-1">Patch v1.0.1 sudah dapat diunduh</p>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Student Distribution Bar Chart
    const studentDistributionCtx = document.getElementById('studentDistributionChart').getContext('2d');
    new Chart(studentDistributionCtx, {
        type: 'bar',
        data: {
            labels: ['TK', 'SD', 'SMP', 'SMA', 'SMK'],
            datasets: [{
                label: 'Siswa',
                data: [1850, 4200, 3150, 2100, 1150],
                backgroundColor: [
                    '#fbbf24',
                    '#0ea5e9',
                    '#8b5cf6',
                    '#10b981',
                    '#f97316'
                ],
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e3a5f',
                    titleFont: { family: 'Poppins', size: 13 },
                    bodyFont: { family: 'Inter', size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Inter', size: 12 } }
                },
                y: {
                    grid: { color: '#f3f4f6' },
                    ticks: { font: { family: 'Inter', size: 12 } }
                }
            }
        }
    });

    // Unit per Foundation Pie Chart
    const unitPerFoundationCtx = document.getElementById('unitPerFoundationChart').getContext('2d');
    new Chart(unitPerFoundationCtx, {
        type: 'doughnut',
        data: {
            labels: ['Yayasan Al-Hidayah', 'Yayasan Tunas Bangsa', 'Yayasan Cendekia', 'Yayasan Islamiyah', 'Lainnya'],
            datasets: [{
                data: [5, 4, 3, 2, 2],
                backgroundColor: [
                    '#1e3a5f',
                    '#0ea5e9',
                    '#fbbf24',
                    '#10b981',
                    '#8b5cf6'
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        font: { family: 'Inter', size: 11 },
                        padding: 12,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: '#1e3a5f',
                    titleFont: { family: 'Poppins', size: 13 },
                    bodyFont: { family: 'Inter', size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                }
            }
        }
    });

    // Login Activity Line Chart
    const loginActivityCtx = document.getElementById('loginActivityChart').getContext('2d');
    new Chart(loginActivityCtx, {
        type: 'line',
        data: {
            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
            datasets: [{
                label: 'Login Admin',
                data: [145, 189, 210, 195, 230, 85, 45],
                borderColor: '#0ea5e9',
                backgroundColor: 'rgba(14, 165, 233, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#0ea5e9',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }, {
                label: 'Login Guru',
                data: [320, 450, 480, 520, 490, 120, 60],
                borderColor: '#fbbf24',
                backgroundColor: 'rgba(251, 191, 36, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fbbf24',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        font: { family: 'Inter', size: 11 },
                        padding: 16,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: '#1e3a5f',
                    titleFont: { family: 'Poppins', size: 13 },
                    bodyFont: { family: 'Inter', size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Inter', size: 12 } }
                },
                y: {
                    grid: { color: '#f3f4f6' },
                    ticks: { font: { family: 'Inter', size: 12 } }
                }
            }
        }
    });

    // SPP Payment Stacked Bar Chart
    const sppPaymentCtx = document.getElementById('sppPaymentChart').getContext('2d');
    new Chart(sppPaymentCtx, {
        type: 'bar',
        data: {
            labels: ['Al-Hidayah', 'Tunas Bangsa', 'Cendekia', 'Islamiyah'],
            datasets: [{
                label: 'Lunas',
                data: [45000000, 38000000, 28000000, 15000000],
                backgroundColor: '#10b981',
                borderRadius: 4,
            }, {
                label: 'Belum Lunas',
                data: [5000000, 8000000, 4000000, 2000000],
                backgroundColor: '#fbbf24',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        font: { family: 'Inter', size: 11 },
                        padding: 16,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: '#1e3a5f',
                    titleFont: { family: 'Poppins', size: 13 },
                    bodyFont: { family: 'Inter', size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + (context.raw / 1000000).toFixed(1) + ' JT';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Inter', size: 11 } }
                },
                y: {
                    grid: { color: '#f3f4f6' },
                    ticks: {
                        font: { family: 'Inter', size: 11 },
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000) + ' JT';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush

</x-platform-layout>
