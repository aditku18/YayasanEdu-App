<x-platform-layout>
    <x-slot name="header">Kelola Broadcast</x-slot>
    <x-slot name="subtitle">Kirim notifikasi dan pengumuman ke pengguna platform</x-slot>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Statistics Dashboard --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Broadcast</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['total_broadcasts'] ?? 0 }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Terkirim</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['sent_broadcasts'] ?? 0 }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Dijadwalkan</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['scheduled_broadcasts'] ?? 0 }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Draft</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['draft_broadcasts'] ?? 0 }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Total Penerima</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['total_recipients'] ?? 0 }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters and Search --}}
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mb-6">
        <form method="GET" action="{{ route('platform.broadcasts.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Broadcast</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul atau pesan..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Dijadwalkan</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Terkirim</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                    <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Tipe</option>
                        <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Info</option>
                        <option value="success" {{ request('type') == 'success' ? 'selected' : '' }}>Success</option>
                        <option value="warning" {{ request('type') == 'warning' ? 'selected' : '' }}>Warning</option>
                        <option value="error" {{ request('type') == 'error' ? 'selected' : '' }}>Error</option>
                        <option value="maintenance" {{ request('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target</label>
                    <select name="target" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Target</option>
                        <option value="all_users" {{ request('target') == 'all_users' ? 'selected' : '' }}>Semua User</option>
                        <option value="platform_admins" {{ request('target') == 'platform_admins' ? 'selected' : '' }}>Admin Platform</option>
                        <option value="foundation_admins" {{ request('target') == 'foundation_admins' ? 'selected' : '' }}>Admin Yayasan</option>
                        <option value="school_admins" {{ request('target') == 'school_admins' ? 'selected' : '' }}>Admin Sekolah</option>
                        <option value="specific_foundations" {{ request('target') == 'specific_foundations' ? 'selected' : '' }}>Yayasan Tertentu</option>
                    </select>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari
                </button>
                <a href="{{ route('platform.broadcasts.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Reset
                </a>
                <a href="{{ route('platform.broadcasts.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Buat Broadcast Baru
                </a>
            </div>
        </form>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Broadcasts Table -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-900">Daftar Broadcast</h2>
                        <span class="text-sm text-gray-500">{{ $broadcasts->total() ?? 0 }} broadcast</span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dijadwalkan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($broadcasts ?? [] as $broadcast)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $broadcast->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($broadcast->message, 50) }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($broadcast->type == 'info')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Info
                                            </span>
                                        @elseif($broadcast->type == 'success')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Success
                                            </span>
                                        @elseif($broadcast->type == 'warning')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Warning
                                            </span>
                                        @elseif($broadcast->type == 'error')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Error
                                            </span>
                                        @elseif($broadcast->type == 'maintenance')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                Maintenance
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($broadcast->type) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($broadcast->target == 'all_users')
                                            <span class="text-sm text-gray-900">Semua User</span>
                                        @elseif($broadcast->target == 'platform_admins')
                                            <span class="text-sm text-gray-900">Admin Platform</span>
                                        @elseif($broadcast->target == 'foundation_admins')
                                            <span class="text-sm text-gray-900">Admin Yayasan</span>
                                        @elseif($broadcast->target == 'school_admins')
                                            <span class="text-sm text-gray-900">Admin Sekolah</span>
                                        @elseif($broadcast->target == 'specific_foundations')
                                            <span class="text-sm text-gray-900">Yayasan Tertentu</span>
                                        @else
                                            <span class="text-sm text-gray-900">{{ ucfirst($broadcast->target) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($broadcast->is_draft)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Draft
                                            </span>
                                        @elseif($broadcast->is_sent)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Terkirim
                                            </span>
                                        @elseif($broadcast->scheduled_at)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Dijadwalkan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Siap Kirim
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $broadcast->scheduled_at ? $broadcast->scheduled_at->format('d M Y H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex items-center gap-2">
                                            @if($broadcast->is_draft)
                                                <a href="{{ route('platform.broadcasts.edit', $broadcast->id) }}" 
                                                   class="text-blue-600 hover:text-blue-900">Edit</a>
                                                <span class="text-gray-300">|</span>
                                                <button onclick="showSendModal({{ $broadcast->id }})" 
                                                        class="text-green-600 hover:text-green-900">Kirim</button>
                                                <span class="text-gray-300">|</span>
                                                <button onclick="confirmDelete({{ $broadcast->id }}, '{{ $broadcast->title }}')" 
                                                        class="text-red-600 hover:text-red-900">Hapus</button>
                                            @elseif($broadcast->scheduled_at && !$broadcast->is_sent)
                                                <button onclick="showSendModal({{ $broadcast->id }})" 
                                                        class="text-green-600 hover:text-green-900">Kirim Sekarang</button>
                                                <span class="text-gray-300">|</span>
                                                <button onclick="confirmDelete({{ $broadcast->id }}, '{{ $broadcast->title }}')" 
                                                        class="text-red-600 hover:text-red-900">Hapus</button>
                                            @else
                                                <a href="{{ route('platform.broadcasts.show', $broadcast->id) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                        </svg>
                                        <p>Belum ada broadcast yang ditemukan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(isset($broadcasts) && $broadcasts->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $broadcasts->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Type Distribution -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Distribusi Tipe</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Info</span>
                        <span class="text-sm font-medium text-blue-600">{{ $stats['info_broadcasts'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Success</span>
                        <span class="text-sm font-medium text-green-600">{{ $stats['success_broadcasts'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Warning</span>
                        <span class="text-sm font-medium text-yellow-600">{{ $stats['warning_broadcasts'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Error</span>
                        <span class="text-sm font-medium text-red-600">{{ $stats['error_broadcasts'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Maintenance</span>
                        <span class="text-sm font-medium text-orange-600">{{ $stats['maintenance_broadcasts'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Target Distribution -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Distribusi Target</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Semua User</span>
                        <span class="text-sm font-medium text-gray-900">{{ $stats['all_users_broadcasts'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Admin Platform</span>
                        <span class="text-sm font-medium text-gray-900">{{ $stats['platform_admins_broadcasts'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Admin Yayasan</span>
                        <span class="text-sm font-medium text-gray-900">{{ $stats['foundation_admins_broadcasts'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Admin Sekolah</span>
                        <span class="text-sm font-medium text-gray-900">{{ $stats['school_admins_broadcasts'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Yayasan Tertentu</span>
                        <span class="text-sm font-medium text-gray-900">{{ $stats['specific_foundations_broadcasts'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Recent Broadcasts -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Broadcast Terbaru</h3>
                <div class="space-y-3">
                    @php
                        $recentBroadcasts = isset($broadcasts) ? $broadcasts->take(5) : [];
                    @endphp
                    @forelse($recentBroadcasts as $broadcast)
                        <div class="flex items-center gap-3 pb-3 border-b border-gray-100 last:border-0">
                            <div class="w-2 h-2 {{ $broadcast->is_sent ? 'bg-green-500' : ($broadcast->is_draft ? 'bg-gray-500' : 'bg-blue-500') }} rounded-full"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ Str::limit($broadcast->title, 25) }}</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-500">{{ ucfirst($broadcast->type) }}</span>
                                    <span class="text-xs text-gray-400">{{ $broadcast->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada broadcast</p>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route('platform.broadcasts.create') }}" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm block text-center">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Buat Broadcast Baru
                    </a>
                    <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        Broadcast Masal
                    </a>
                    <button class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Template Broadcast
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Modal -->
    <div id="sendModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Kirim Broadcast</h3>
                <button onclick="closeSendModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="sendForm" method="POST" class="space-y-4">
                @csrf
                <div class="p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800">Broadcast akan dikirim ke semua penerima yang ditargetkan.</p>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="confirm_send" id="confirm_send" required class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="confirm_send" class="ml-2 block text-sm text-gray-900">
                        Saya yakin ingin mengirim broadcast ini
                    </label>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeSendModal()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Kirim Broadcast
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Hapus Broadcast</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="deleteForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                <div class="p-4 bg-red-50 rounded-lg">
                    <p class="text-sm text-red-800">Apakah Anda yakin ingin menghapus broadcast "<span id="broadcastTitle"></span>"?</p>
                    <p class="text-sm text-red-500 mt-1">Broadcast yang sudah dikirim tidak dapat dihapus.</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDeleteModal()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showSendModal(broadcastId) {
            document.getElementById('sendModal').classList.remove('hidden');
            document.getElementById('sendForm').action = `/platform/broadcasts/${broadcastId}/send`;
        }

        function closeSendModal() {
            document.getElementById('sendModal').classList.add('hidden');
            document.getElementById('sendForm').reset();
        }

        function confirmDelete(broadcastId, broadcastTitle) {
            document.getElementById('broadcastTitle').textContent = broadcastTitle;
            document.getElementById('deleteForm').action = `/platform/broadcasts/${broadcastId}`;
            document.getElementById('deleteModal').classList.add('hidden');
            // Note: Delete functionality should check if broadcast is sent
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('broadcastTitle').textContent = '';
        }
    </script>
</x-platform-layout>
