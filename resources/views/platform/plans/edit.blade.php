<x-platform-layout>
    <x-slot name="header">Edit Paket</x-slot>
    <x-slot name="subtitle">Perbarui informasi paket langganan</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('platform.plans.update', $plan->id) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Left Column --}}
                    <div class="space-y-6">
                        {{-- Basic Information --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Paket <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $plan->name) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                    <textarea name="description" id="description" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $plan->description) }}</textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="price_per_month" class="block text-sm font-medium text-gray-700 mb-2">Harga/Bulan (Rp) <span class="text-red-500">*</span></label>
                                        <input type="number" name="price_per_month" id="price_per_month" value="{{ old('price_per_month', $plan->price_per_month) }}" required min="0"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                        @error('price_per_month')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="price_per_year" class="block text-sm font-medium text-gray-700 mb-2">Harga/Tahun (Rp)</label>
                                        <input type="number" name="price_per_year" id="price_per_year" value="{{ old('price_per_year', $plan->price_per_year) }}" min="0"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                        @error('price_per_year')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Features --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Fitur Paket</h3>
                            
                            <div>
                                <label for="features_text" class="block text-sm font-medium text-gray-700 mb-2">Daftar Fitur</label>
                                <textarea name="features_text" id="features_text" rows="6" placeholder="Setiap fitur pada baris baru"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('features_text', implode("\n", $plan->getFeaturesArray())) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Masukkan setiap fitur pada baris baru</p>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-6">
                        {{-- Limits --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Batasan Kuota</h3>
                            
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label for="max_schools" class="block text-sm font-medium text-gray-700 mb-2">Max Sekolah <span class="text-red-500">*</span></label>
                                    <input type="number" name="max_schools" id="max_schools" value="{{ old('max_schools', $plan->max_schools) }}" required min="1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                    @error('max_schools')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="max_users" class="block text-sm font-medium text-gray-700 mb-2">Max User <span class="text-red-500">*</span></label>
                                    <input type="number" name="max_users" id="max_users" value="{{ old('max_users', $plan->max_users) }}" required min="1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                    @error('max_users')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="max_students" class="block text-sm font-medium text-gray-700 mb-2">Max Siswa <span class="text-red-500">*</span></label>
                                    <input type="number" name="max_students" id="max_students" value="{{ old('max_students', $plan->max_students) }}" required min="1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                    @error('max_students')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Settings --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Urutan Tampil</label>
                                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $plan->sort_order) }}" min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                    @error('sort_order')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }}
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                            Aktifkan paket ini
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $plan->is_featured) ? 'checked' : '' }}
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="is_featured" class="ml-2 block text-sm text-gray-700">
                                            Tandai sebagai paket unggulan
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Current Usage --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Penggunaan Saat Ini</h3>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Jumlah Yayasan</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $plan->foundations()->count() }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Total Siswa</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $plan->foundations()->withCount('students')->get()->sum('students_count') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Slug: <span class="font-mono">{{ $plan->slug }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('platform.plans.index') }}" 
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                                Batal
                            </a>
                            <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                                Perbarui Paket
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-platform-layout>
