<x-platform-layout>
    <x-slot name="header">Dashboard Utama</x-slot>
    <x-slot name="subtitle">Selamat datang kembali, {{ explode(' ', Auth::user()->name ?? 'Admin')[0] }}</x-slot>

    {{-- Statistics Dashboard --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 border border-white/20 hover-lift group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium">+5%</span>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['total_foundations'] ?? 0) }}</p>
                <p class="text-sm text-slate-600 mt-1">Total Yayasan</p>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-white/20 hover-lift group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">+8%</span>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['total_schools'] ?? 0) }}</p>
                <p class="text-sm text-slate-600 mt-1">Total Sekolah</p>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-white/20 hover-lift group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">+12%</span>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['total_students'] ?? 0) }}</p>
                <p class="text-sm text-slate-600 mt-1">Total Siswa</p>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-white/20 hover-lift group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-medium">+15%</span>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['total_users'] ?? 0) }}</p>
                <p class="text-sm text-slate-600 mt-1">Total Users</p>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-white/20 hover-lift group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-medium">Aktif</span>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['active_plans'] ?? 0) }}</p>
                <p class="text-sm text-slate-600 mt-1">Paket Aktif</p>
            </div>
        </div>
    </div>

    {{-- Additional Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 border border-white/20 hover-lift group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">+18%</span>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-900">Rp {{ number_format($stats['monthly_revenue'] ?? 0, 0, ',', '.') }}</p>
                <p class="text-sm text-slate-600 mt-1">Revenue Bulan Ini</p>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-white/20 hover-lift group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2v2m0 0V8a2 2 0 10-2h2a2 2 0 012 2v8m0 0v2m0-6V4m6 6v10m6-2a2 2 0 10-2h2a2 2 0 012 2v2m0 0v2m0-6V4"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">Online</span>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-900">100%</p>
                <p class="text-sm text-slate-600 mt-1">System Status</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activity -->
        <div class="lg:col-span-2">
            <div class="glass-effect rounded-2xl border border-white/20">
                <div class="p-6 border-b border-white/10">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-slate-900">Aktivitas Terbaru</h2>
                        <a href="{{ route('platform.activity-logs.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Lihat Semua</a>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentActivities ?? [] as $activity)
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                            <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-900">{{ $activity['description'] ?? 'Aktivitas Sistem' }}</p>
                                <p class="text-xs text-slate-500">{{ $activity['user_name'] ?? 'Admin' }}</p>
                                <p class="text-xs text-slate-400 mt-1">{{ $activity['created_at'] ?? 'Baru saja' }}</p>
                            </div>
                        </div>
                        @empty
                        <!-- Activity Item 1 -->
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                            <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-900">Yayasan Baru Terdaftar</p>
                                <p class="text-xs text-slate-500">Yayasan Pendidikan Citra Bangsa telah mendaftar</p>
                                <p class="text-xs text-slate-400 mt-1">2 jam yang lalu</p>
                            </div>
                        </div>

                        <!-- Activity Item 2 -->
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-900">Pembayaran Berhasil</p>
                                <p class="text-xs text-slate-500">Yayasan Al-Azhar telah melakukan pembayaran paket Pro</p>
                                <p class="text-xs text-slate-400 mt-1">4 jam yang lalu</p>
                            </div>
                        </div>

                        <!-- Activity Item 3 -->
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-900">Sekolah Baru Ditambahkan</p>
                                <p class="text-xs text-slate-500">SDN Harapan Bangsa telah ditambahkan ke sistem</p>
                                <p class="text-xs text-slate-400 mt-1">6 jam yang lalu</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="glass-effect rounded-2xl p-6 border border-white/20">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route('platform.foundations.index') }}" class="w-full px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors text-sm font-medium text-center block">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Kelola Yayasan
                    </a>
                    <a href="{{ route('platform.users.index') }}" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors text-sm font-medium text-center block">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Kelola Users
                    </a>
                    <a href="{{ route('platform.plans.create') }}" class="w-full px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors text-sm font-medium text-center block">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                        Tambah Paket
                    </a>
                </div>
            </div>

            <!-- Recent Activity Summary -->
            <div class="glass-effect rounded-2xl p-6 border border-white/20">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Ringkasan Hari Ini</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Yayasan Baru</span>
                        <span class="text-sm font-medium text-slate-900">{{ $stats['new_foundations_today'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Pembayaran</span>
                        <span class="text-sm font-medium text-slate-900">{{ $stats['payments_today'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">User Baru</span>
                        <span class="text-sm font-medium text-slate-900">{{ $stats['new_users_today'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Support Tickets</span>
                        <span class="text-sm font-medium text-slate-900">{{ $stats['tickets_today'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Growth Chart -->
        <div class="glass-effect rounded-2xl border border-white/20">
            <div class="p-6 border-b border-white/10">
                <h2 class="text-lg font-bold text-slate-900">Pertumbuhan Platform</h2>
            </div>
            <div class="p-6">
                <canvas id="growthChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Distribution Chart -->
        <div class="glass-effect rounded-2xl border border-white/20">
            <div class="p-6 border-b border-white/10">
                <h2 class="text-lg font-bold text-slate-900">Distribusi Paket</h2>
            </div>
            <div class="p-6">
                <canvas id="distributionChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Growth Chart
        const growthCtx = document.getElementById('growthChart').getContext('2d');
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($monthlyData, 'month')) !!},
                datasets: [{
                    label: 'Yayasan',
                    data: {!! json_encode(array_column($monthlyData, 'foundations')) !!},
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Sekolah',
                    data: {!! json_encode(array_column($monthlyData, 'schools')) !!},
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Distribution Chart
        const distributionCtx = document.getElementById('distributionChart').getContext('2d');
        new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($planDistribution->keys()) !!},
                datasets: [{
                    data: {!! json_encode($planDistribution->values()) !!},
                    backgroundColor: [
                        'rgb(99, 102, 241)',
                        'rgb(16, 185, 129)',
                        'rgb(251, 146, 60)',
                        'rgb(244, 63, 94)',
                        'rgb(168, 85, 247)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
    @endpush
</x-platform-layout>
