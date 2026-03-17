<x-platform-layout>
    <x-slot name="header">Buat Broadcast Baru</x-slot>
    <x-slot name="subtitle">Kirim pesan broadcast ke pengguna platform</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('platform.broadcasts.store') }}">
                @csrf

                <div class="space-y-6">
                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Judul pesan broadcast" />
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Message --}}
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Pesan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="message" id="message" rows="5" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Tulis pesan broadcast Anda...">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Type --}}
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Broadcast <span class="text-red-500">*</span>
                            </label>
                            <select name="type" id="type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Pilih Tipe</option>
                                <option value="info" {{ old('type') === 'info' ? 'selected' : '' }}>Informasi</option>
                                <option value="success" {{ old('type') === 'success' ? 'selected' : '' }}>Sukses</option>
                                <option value="warning" {{ old('type') === 'warning' ? 'selected' : '' }}>Peringatan</option>
                                <option value="error" {{ old('type') === 'error' ? 'selected' : '' }}>Error</option>
                                <option value="maintenance" {{ old('type') === 'maintenance' ? 'selected' : '' }}>Pemeliharaan</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Target --}}
                        <div>
                            <label for="target" class="block text-sm font-medium text-gray-700 mb-2">
                                Target Penerima <span class="text-red-500">*</span>
                            </label>
                            <select name="target" id="target" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Pilih Target</option>
                                <option value="all_users" {{ old('target') === 'all_users' ? 'selected' : '' }}>Semua Pengguna</option>
                                <option value="platform_admins" {{ old('target') === 'platform_admins' ? 'selected' : '' }}>Admin Platform</option>
                                <option value="foundation_admins" {{ old('target') === 'foundation_admins' ? 'selected' : '' }}>Admin Yayasan</option>
                                <option value="school_admins" {{ old('target') === 'school_admins' ? 'selected' : '' }}>Admin Sekolah</option>
                                <option value="specific_foundations" {{ old('target') === 'specific_foundations' ? 'selected' : '' }}>Yayasan Tertentu</option>
                            </select>
                            @error('target')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Target Foundations (conditional) --}}
                    <div id="foundations_field" class="{{ old('target') === 'specific_foundations' ? '' : 'hidden' }}">
                        <label for="target_foundations" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Yayasan
                        </label>
                        <select name="target_foundations[]" id="target_foundations" multiple
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($foundations as $id => $name)
                                <option value="{{ $id }}" {{ in_array($id, old('target_foundations', [])) ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('target_foundations')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Tahan Ctrl/Cmd untuk memilih lebih dari satu</p>
                    </div>

                    {{-- Schedule --}}
                    <div>
                        <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-2">
                            Jadwal Pengiriman (Opsional)
                        </label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at" value="{{ old('scheduled_at') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        @error('scheduled_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Kosongkan untuk mengirim sekarang juga</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                    <a href="{{ route('platform.broadcasts.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Kirim Broadcast
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('target').addEventListener('change', function() {
            const foundationsField = document.getElementById('foundations_field');
            if (this.value === 'specific_foundations') {
                foundationsField.classList.remove('hidden');
            } else {
                foundationsField.classList.add('hidden');
            }
        });
    </script>
    @endpush
</x-platform-layout>
