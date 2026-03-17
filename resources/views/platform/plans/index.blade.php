<x-platform-layout>
    <x-slot name="header">Paket Langganan</x-slot>
    <x-slot name="subtitle">Kelola dan pantau semua paket langganan platform</x-slot>

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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Paket</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($stats['total']) }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Paket Aktif</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($stats['active']) }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Paket Unggulan</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($stats['featured']) }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Rata-rata Harga</p>
                    <p class="text-3xl font-bold mt-1">Rp {{ number_format($plans->avg('price_per_month'), 0, ',', '.') }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters and Search --}}
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mb-6">
        <form method="GET" action="{{ route('platform.plans.index') }}" class="space-y-4">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Paket</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama paket..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div class="flex items-end gap-3">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari
                    </button>
                    <a href="{{ route('platform.plans.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Paket
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Plans Grid -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-900">Daftar Paket</h2>
                        <span class="text-sm text-gray-500">{{ $plans->total() }} paket</span>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($plans as $plan)
                            <div class="bg-white border-2 rounded-xl p-6 shadow-sm hover:shadow-md transition-all {{ $plan->is_featured ? 'border-indigo-500 ring-2 ring-indigo-100' : 'border-gray-200' }}">
                                {{-- Featured Badge --}}
                                @if($plan->is_featured)
                                    <div class="mb-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                            </svg>
                                            Terpopuler
                                        </span>
                                    </div>
                                @endif

                                {{-- Plan Name & Price --}}
                                <div class="mb-4">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $plan->name }}</h3>
                                    @if($plan->price_per_month > 0)
                                        <p class="text-2xl font-bold text-indigo-600">
                                            Rp {{ number_format($plan->price_per_month, 0, ',', '.') }}
                                            <span class="text-sm font-normal text-gray-500">/bulan</span>
                                        </p>
                                        @if($plan->price_per_year > 0)
                                            <p class="text-sm text-gray-500">
                                                Rp {{ number_format($plan->price_per_year, 0, ',', '.') }} /tahun
                                            </p>
                                        @endif
                                    @else
                                        <p class="text-2xl font-bold text-gray-900">Kontak Sales</p>
                                    @endif
                                </div>

                                {{-- Limits --}}
                                <div class="grid grid-cols-1 gap-2 mb-4 p-3 bg-gray-50 rounded-xl">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">Sekolah</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ $plan->max_schools ?? 'Unlimited' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">User</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ $plan->max_users ?? 'Unlimited' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">Siswa</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ $plan->max_students ?? 'Unlimited' }}</span>
                                    </div>
                                </div>

                                {{-- Features --}}
                                @if(!empty($plan->features) && is_array($plan->features))
                                    <ul class="space-y-2 mb-4">
                                        @foreach(array_slice($plan->features, 0, 4) as $feat)
                                            <li class="flex items-center gap-2 text-sm text-gray-700">
                                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $feat }}
                                            </li>
                                        @endforeach
                                        @if(count($plan->features) > 4)
                                            <li class="text-sm text-gray-500">+{{ count($plan->features) - 4 }} fitur lainnya</li>
                                        @endif
                                    </ul>
                                @endif

                                {{-- Actions --}}
                                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                    <span class="text-xs text-gray-500">{{ $plan->slug }}</span>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('platform.plans.edit', $plan->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('platform.plans.destroy', $plan->id) }}" class="inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada paket tersedia</h3>
                                <p class="text-gray-500 mb-4">Silakan tambah paket terlebih dahulu</p>
                                <a href="{{ route('platform.plans.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Tambah Paket
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                @if($plans->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $plans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-platform-layout>
