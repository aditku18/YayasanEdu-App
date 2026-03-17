<x-platform-layout>
    <x-slot name="header">Detail Plugin</x-slot>
    <x-slot name="subtitle">Informasi lengkap plugin yayasan</x-slot>

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

    <!-- Back Button -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('platform.plugins.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Plugin
        </a>
        <a href="{{ route('platform.plugins.edit', $plugin) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Plugin
        </a>
    </div>

    <!-- Plugin Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Plugin Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $plugin->name }}</h2>
                        <p class="text-gray-500 mt-1">versi {{ $plugin->version }} oleh {{ $plugin->developer }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($plugin->status == 'active')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                Aktif
                            </span>
                        @elseif($plugin->status == 'inactive')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                Tidak Aktif
                            </span>
                        @elseif($plugin->status == 'deprecated')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                Kadaluarsa
                            </span>
                        @endif
                        @if($plugin->is_available_in_marketplace)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                Marketplace
                            </span>
                        @endif
                    </div>
                </div>

                <div class="prose max-w-none">
                    <p class="text-gray-600">{{ $plugin->description ?? 'Tidak ada deskripsi tersedia.' }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <div>
                        <p class="text-sm text-gray-500">Kategori</p>
                        <p class="mt-1 font-medium text-gray-900">{{ ucfirst($plugin->category ?? 'Other') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Harga</p>
                        <p class="mt-1 font-medium text-gray-900">
                            @if($plugin->price > 0)
                                Rp {{ number_format($plugin->price, 0, ',', '.') }}
                            @else
                                Gratis
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Instalasi</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $plugin->installations->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Dibuat</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $plugin->created_at ? $plugin->created_at->format('d M Y') : '-' }}</p>
                    </div>
                </div>

                @if($plugin->documentation_url)
                    <div class="mt-4">
                        <a href="{{ $plugin->documentation_url }}" target="_blank" 
                           class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Dokumentasi
                        </a>
                    </div>
                @endif
            </div>

            <!-- Features -->
            @if($plugin->features && is_array($plugin->features) && count($plugin->features) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Fitur</h3>
                    <ul class="space-y-2">
                        @foreach($plugin->features as $feature)
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-gray-700">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Requirements -->
            @if($plugin->requirements && is_array($plugin->requirements) && count($plugin->requirements) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Persyaratan</h3>
                    <ul class="space-y-2">
                        @foreach($plugin->requirements as $requirement)
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-orange-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-gray-700">{{ $requirement }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi</h3>
                <div class="flex flex-wrap gap-3">
                    @if($plugin->is_available_in_marketplace)
                        <button onclick="showInstallModal({{ $plugin->id }})" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Instal Plugin
                        </button>
                    @endif
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Kirim Info
                    </button>
                    <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Edit
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Statistik Cepat</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Instalasi</span>
                        <span class="text-sm font-bold text-gray-900">{{ $plugin->installations->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Instalasi Aktif</span>
                        <span class="text-sm font-bold text-gray-900">{{ $plugin->installations->where('is_active', true)->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Status</span>
                        <span class="text-sm font-bold text-gray-900">{{ ucfirst($plugin->status) }}</span>
                    </div>
                </div>
            </div>

            <!-- Recent Installations -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Instalasi Terbaru</h3>
                <div class="space-y-3">
                    @forelse($plugin->installations->sortByDesc('installed_at')->take(5) as $installation)
                        <div class="flex items-center gap-3 pb-3 border-b border-gray-100 last:border-0">
                            <div class="w-2 h-2 {{ $installation->is_active ? 'bg-green-500' : 'bg-gray-500' }} rounded-full"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $installation->foundation->name }}</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs {{ $installation->is_active ? 'text-green-600' : 'text-gray-500' }} font-medium">
                                        {{ $installation->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                    <span class="text-xs text-gray-400">{{ $installation->installed_at ? $installation->installed_at->diffForHumans() : '-' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada instalasi</p>
                    @endforelse
                </div>
            </div>

            <!-- Installation Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Instalasi</h3>
                <div class="space-y-2">
                    <button onclick="showInstallModal({{ $plugin->id }})" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Instal Baru
                    </button>
                    <a href="{{ route('platform.plugins.active') }}" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm block text-center">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Lihat Instalasi Aktif
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Install Modal -->
    <div id="installModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Instal Plugin</h3>
                <button onclick="closeInstallModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="installForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Yayasan</label>
                    <select name="foundation_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih yayasan...</option>
                        @foreach(\App\Models\Foundation::all() as $foundation)
                            <option value="{{ $foundation->id }}">{{ $foundation->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeInstallModal()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Instal Plugin
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showInstallModal(pluginId) {
            document.getElementById('installModal').classList.remove('hidden');
            document.getElementById('installForm').action = `/platform/plugins/${pluginId}/install`;
        }

        function closeInstallModal() {
            document.getElementById('installModal').classList.add('hidden');
            document.getElementById('installForm').reset();
        }
    </script>
</x-platform-layout>
