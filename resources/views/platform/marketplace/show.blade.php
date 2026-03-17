<x-platform-layout>
    <x-slot name="header">Detail Plugin</x-slot>
    <x-slot name="subtitle">Informasi lengkap dan pembelian plugin</x-slot>

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
    <div class="mb-6">
        <a href="{{ route('platform.marketplace.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Marketplace
        </a>
    </div>

    <!-- Plugin Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Plugin Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $plugin->name }}</h1>
                        <div class="flex items-center gap-4 text-gray-500">
                            <span>versi {{ $plugin->version }}</span>
                            <span>•</span>
                            <span>oleh {{ $plugin->developer }}</span>
                            <span>•</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ ucfirst($plugin->category ?? 'Other') }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        @if($plugin->price > 0)
                            <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($plugin->price, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-500">sekali bayar</p>
                        @else
                            <p class="text-3xl font-bold text-green-600">Gratis</p>
                            <p class="text-sm text-gray-500">selamanya</p>
                        @endif
                    </div>
                </div>

                <!-- Rating and Stats -->
                <div class="flex items-center gap-6 pb-6 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= 4) <!-- Assuming 4 stars for demo -->
                                    <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span class="text-sm font-medium text-gray-900">N/A</span>
                        <span class="text-sm text-gray-500">(Belum ada ulasan)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <span class="text-sm text-gray-600">{{ $plugin->installations->count() }} instalasi</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm text-gray-600">Diperbarui {{ $plugin->updated_at->diffForHumans() }}</span>
                    </div>
                </div>

                <!-- Description -->
                <div class="py-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Deskripsi</h3>
                    <div class="prose max-w-none">
                        <p class="text-gray-600">{{ $plugin->description ?? 'Tidak ada deskripsi tersedia.' }}</p>
                    </div>
                </div>

                <!-- Features -->
                @if($plugin->features && is_array($plugin->features) && count($plugin->features) > 0)
                    <div class="py-6 border-t border-gray-100">
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
                    <div class="py-6 border-t border-gray-100">
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

                <!-- Documentation Link -->
                @if($plugin->documentation_url)
                    <div class="py-6 border-t border-gray-100">
                        <a href="{{ $plugin->documentation_url }}" target="_blank" 
                           class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Lihat Dokumentasi
                        </a>
                    </div>
                @endif
            </div>

            <!-- Reviews Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Ulasan Pengguna</h3>
                <div class="space-y-4 text-center py-6">
                    <p class="text-gray-500 italic">Belum ada ulasan untuk plugin ini.</p>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Purchase/Install Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="text-center mb-6">
                    @if($plugin->price > 0)
                        <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($plugin->price, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500">sekali bayar</p>
                    @else
                        <p class="text-3xl font-bold text-green-600">Gratis</p>
                        <p class="text-sm text-gray-500">selamanya</p>
                    @endif
                </div>

                <div class="space-y-3">
                    @if($plugin->price > 0)
                        <button onclick="showPurchaseModal({{ $plugin->id }})" 
                                class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            Beli Sekarang
                        </button>
                    @else
                        <button onclick="showInstallModal({{ $plugin->id }})" 
                                class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            Install Gratis
                        </button>
                    @endif

                    <button class="w-full px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Hubungi Developer
                    </button>
                </div>

                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">
                        <strong>Garansi:</strong> 30 hari uang kembali<br>
                        <strong>Support:</strong> 24/7 via email<br>
                        <strong>Update:</strong> Gratis 1 tahun
                    </p>
                </div>
            </div>

            <!-- Plugin Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Plugin</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Versi</span>
                        <span class="text-sm font-medium text-gray-900">{{ $plugin->version }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Kategori</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($plugin->category ?? 'Other') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Developer</span>
                        <span class="text-sm font-medium text-gray-900">{{ $plugin->developer }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Instalasi</span>
                        <span class="text-sm font-medium text-gray-900">{{ $plugin->installations->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Rating</span>
                        <span class="text-sm font-medium text-gray-900">- / 5.0</span>
                    </div>
                </div>
            </div>

            <!-- Related Plugins -->
            @if(isset($relatedPlugins) && $relatedPlugins->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Plugin Terkait</h3>
                    <div class="space-y-3">
                        @foreach($relatedPlugins as $relatedPlugin)
                            <div class="flex items-center gap-3 pb-3 border-b border-gray-100 last:border-0">
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $relatedPlugin->name }}</p>
                                    <div class="flex items-center gap-2">
                                        @if($relatedPlugin->price > 0)
                                            <span class="text-xs text-green-600 font-medium">Rp {{ number_format($relatedPlugin->price, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-xs text-green-600 font-medium">Gratis</span>
                                        @endif
                                        <span class="text-xs text-gray-500">{{ $relatedPlugin->installations->count() }} instalasi</span>
                                    </div>
                                </div>
                                <a href="{{ route('platform.marketplace.show', $relatedPlugin->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 text-sm">
                                    Lihat
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Purchase Modal -->
    <div id="purchaseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Beli Plugin</h3>
                <button onclick="closePurchaseModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="purchaseForm" method="POST" class="space-y-4">
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
                <div class="p-4 bg-green-50 rounded-lg">
                    <p class="text-sm text-green-800">Setelah pembelian, plugin dapat diinstal pada yayasan yang dipilih.</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closePurchaseModal()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Beli Plugin
                    </button>
                </div>
            </form>
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
        function showPurchaseModal(pluginId) {
            document.getElementById('purchaseModal').classList.remove('hidden');
            document.getElementById('purchaseForm').action = `/platform/marketplace/${pluginId}/purchase`;
        }

        function closePurchaseModal() {
            document.getElementById('purchaseModal').classList.add('hidden');
            document.getElementById('purchaseForm').reset();
        }

        function showInstallModal(pluginId) {
            document.getElementById('installModal').classList.remove('hidden');
            document.getElementById('installForm').action = `/platform/marketplace/${pluginId}/install`;
        }

        function closeInstallModal() {
            document.getElementById('installModal').classList.add('hidden');
            document.getElementById('installForm').reset();
        }
    </script>
</x-platform-layout>
