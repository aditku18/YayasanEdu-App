<x-platform-layout>
    <x-slot name="header">Detail Yayasan</x-slot>

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

    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="flex text-sm text-gray-500">
            <a href="{{ route('platform.foundations.index') }}" class="hover:text-gray-700">Data Yayasan</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ $foundation->name }}</span>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Foundation Info --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Yayasan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Nama Yayasan</label>
                        <p class="font-medium text-gray-900">{{ $foundation->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Subdomain</label>
                        <p class="font-medium text-gray-900">{{ $foundation->subdomain }}.localhost</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Email</label>
                        <p class="font-medium text-gray-900">{{ $foundation->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Status</label>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($foundation->status === 'active') bg-green-100 text-green-800
                                @elseif($foundation->status === 'trial') bg-blue-100 text-blue-800
                                @elseif($foundation->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($foundation->status === 'expired') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($foundation->status) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Paket Langganan</label>
                        <p class="font-medium text-gray-900">{{ $foundation->plan?->name ?? 'Tidak ada paket' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Berlangganan Hingga</label>
                        <p class="font-medium text-gray-900">
                            {{ $foundation->subscription_ends_at ? $foundation->subscription_ends_at->format('d M Y') : 'Tidak ada batas waktu' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Didaftarkan pada</label>
                        <p class="font-medium text-gray-900">{{ $foundation->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Terakhir diperbarui</label>
                        <p class="font-medium text-gray-900">{{ $foundation->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- Admin Info --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Administrator Utama</h3>
                @if($foundation->adminUser)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-500">Nama Admin</label>
                            <p class="font-medium text-gray-900">{{ $foundation->adminUser->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Email Admin</label>
                            <p class="font-medium text-gray-900">{{ $foundation->adminUser->email }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">Data admin utama tidak ditemukan.</p>
                @endif
            </div>

            {{-- Users --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengguna Terdaftar</h3>
                @if($foundation->users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse table-auto">
                            <thead>
                                <tr class="text-xs font-semibold text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3">Email</th>
                                    <th class="px-4 py-3">Role</th>
                                    <th class="px-4 py-3">Terdaftar</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($foundation->users as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $user->email }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $user->created_at->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>Belum ada pengguna terdaftar untuk yayasan ini.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Actions --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h3>
                <div class="space-y-3">
                    @if($foundation->status === 'pending')
                        <a href="{{ route('platform.registrations.show', $foundation->id) }}" class="block w-full px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors text-center">
                            Verifikasi Dokumen & Approve
                        </a>
                        <div class="border-t border-gray-100 my-3"></div>
                        <form action="{{ route('platform.foundations.approve', $foundation->id) }}" method="POST" class="inline-block w-full">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                                Setujui Yayasan
                            </button>
                        </form>
                        <form action="{{ route('platform.foundations.reject', $foundation->id) }}" method="POST" class="inline-block w-full" onsubmit="return confirm('Apakah Anda yakin ingin menolak yayasan ini?')">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                                Tolak Yayasan
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('platform.foundations.index') }}" class="block w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors text-center">
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Total Pengguna</span>
                        <span class="font-semibold text-gray-900">{{ $foundation->users->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Sisa Langganan</span>
                        <span class="font-semibold text-gray-900">
                            @if($foundation->subscription_ends_at)
                                {{ $foundation->subscription_ends_at->diffInDays(now()) }} hari
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-platform-layout>
