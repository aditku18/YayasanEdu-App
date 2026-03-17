<x-platform-layout>
    <x-slot name="header">Buat Paket Baru</x-slot>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <form method="POST" action="{{ route('platform.plans.store') }}">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Left Column --}}
                <div class="space-y-6">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block font-medium text-sm text-gray-700 mb-2">Nama Paket</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500" />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block font-medium text-sm text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ old('description') }}</textarea>
                    </div>

                    {{-- Prices --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="price_per_month" class="block font-medium text-sm text-gray-700 mb-2">Harga/Bulan (Rp)</label>
                            <input type="number" name="price_per_month" id="price_per_month" value="{{ old('price_per_month') }}" required min="0"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500" />
                        </div>
                        <div>
                            <label for="price_per_year" class="block font-medium text-sm text-gray-700 mb-2">Harga/Tahun (Rp)</label>
                            <input type="number" name="price_per_year" id="price_per_year" value="{{ old('price_per_year') }}" min="0"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500" />
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="space-y-6">
                    {{-- Limits --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="max_schools" class="block font-medium text-sm text-gray-700 mb-2">Max Sekolah</label>
                            <input type="number" name="max_schools" id="max_schools" value="{{ old('max_schools', 1) }}" required min="1"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500" />
                        </div>
                        <div>
                            <label for="max_users" class="block font-medium text-sm text-gray-700 mb-2">Max User</label>
                            <input type="number" name="max_users" id="max_users" value="{{ old('max_users', 100) }}" required min="1"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500" />
                        </div>
                        <div>
                            <label for="max_students" class="block font-medium text-sm text-gray-700 mb-2">Max Siswa</label>
                            <input type="number" name="max_students" id="max_students" value="{{ old('max_students', 100) }}" required min="1"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500" />
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500" />
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Aktif</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500" />
                            <label for="is_featured" class="ml-2 text-sm text-gray-700">Unggulan</label>
                        </div>
                        <div>
                            <label for="sort_order" class="block font-medium text-sm text-gray-700 mb-2">Urutan</label>
                            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500" />
                        </div>
                    </div>

                    {{-- Features --}}
                    <div>
                        <label for="features_text" class="block font-medium text-sm text-gray-700 mb-2">Fitur (satu per baris)</label>
                        <textarea name="features_text" id="features_text" rows="5" placeholder="Fitur 1&#10;Fitur 2&#10;Fitur 3"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ old('features_text') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Masukkan satu fitur per baris</p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('platform.plans.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-xl font-medium hover:bg-green-700 transition">
                    Buat Paket
                </button>
            </div>
        </form>
    </div>
</x-platform-layout>
