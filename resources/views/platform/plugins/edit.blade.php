<x-platform-layout>
    <x-slot name="header">Edit Plugin</x-slot>
    <x-slot name="subtitle">Ubah informasi dan pengaturan plugin</x-slot>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('platform.plugins.show', $plugin) }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Detail Plugin
        </a>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Quick Price Update -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Harga Cepat</h3>
            <form action="{{ route('platform.plugins.update-price', $plugin) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga Baru (Rp)</label>
                    <input type="number" name="price" id="price" value="{{ $plugin->price }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        min="0" step="1000" required>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Update Harga
                </button>
            </form>
        </div>

        <!-- Quick Status Update -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h3>
            <form action="{{ route('platform.plugins.update-status', $plugin) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Plugin</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="active" {{ $plugin->status == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ $plugin->status == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="maintenance" {{ $plugin->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Update Status
                </button>
            </form>
        </div>
    </div>

    <!-- Full Edit Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Informasi Lengkap</h3>
        
        <form action="{{ route('platform.plugins.update', $plugin) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Plugin</label>
                    <input type="text" name="name" id="name" value="{{ $plugin->name }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <!-- Version -->
                <div>
                    <label for="version" class="block text-sm font-medium text-gray-700 mb-2">Versi</label>
                    <input type="text" name="version" id="version" value="{{ $plugin->version }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category" id="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="education" {{ $plugin->category == 'education' ? 'selected' : '' }}>Education</option>
                        <option value="communication" {{ $plugin->category == 'communication' ? 'selected' : '' }}>Communication</option>
                        <option value="finance" {{ $plugin->category == 'finance' ? 'selected' : '' }}>Finance</option>
                        <option value="management" {{ $plugin->category == 'management' ? 'selected' : '' }}>Management</option>
                        <option value="other" {{ $plugin->category == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Price -->
                <div>
                    <label for="price_full" class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                    <input type="number" name="price" id="price_full" value="{{ $plugin->price }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        min="0" step="1000" required>
                </div>

                <!-- Developer -->
                <div>
                    <label for="developer" class="block text-sm font-medium text-gray-700 mb-2">Developer</label>
                    <input type="text" name="developer" id="developer" value="{{ $plugin->developer }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Status -->
                <div>
                    <label for="status_full" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status_full" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="active" {{ $plugin->status == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ $plugin->status == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="maintenance" {{ $plugin->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>

                <!-- Marketplace -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_available_in_marketplace" value="1" {{ $plugin->is_available_in_marketplace ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Tampilkan di Marketplace</span>
                    </label>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" id="description" rows="4" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $plugin->description }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('platform.plugins.show', $plugin) }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</x-platform-layout>
