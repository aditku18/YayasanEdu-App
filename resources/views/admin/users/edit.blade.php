<x-platform-layout>
    <x-slot name="header">Edit Pengguna</x-slot>
    <x-slot name="subtitle">Perbarui data pengguna {{ $user->name }}</x-slot>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
        <form action="{{ route('platform.users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informasi Dasar -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Informasi Pengguna</h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role Pengguna</label>
                        <select id="role" 
                                name="role" 
                                value="{{ old('role', $user->roles->first()?->name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                                required>
                            <option value="">Pilih Role</option>
                            <option value="platform_admin" {{ old('role', $user->roles->first()?->name) === 'platform_admin' ? 'selected' : '' }}>Platform Admin</option>
                            <option value="foundation_admin" {{ old('role', $user->roles->first()?->name) === 'foundation_admin' ? 'selected' : '' }}>Foundation Admin</option>
                            <option value="school_admin" {{ old('role', $user->roles->first()?->name) === 'school_admin' ? 'selected' : '' }}>School Admin</option>
                            <option value="teacher" {{ old('role', $user->roles->first()?->name) === 'teacher' ? 'selected' : '' }}>Guru</option>
                            <option value="student" {{ old('role', $user->roles->first()?->name) === 'student' ? 'selected' : '' }}>Siswa</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Pilih role yang sesuai dengan hak akses pengguna</p>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status & Keamanan -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Status & Keamanan</h3>
                    
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status Akun</label>
                        <select id="is_active" 
                                name="is_active" 
                                value="{{ old('is_active', $user->is_active ? '1' : '0') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                            <option value="1" {{ old('is_active', $user->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $user->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                               placeholder="Kosongkan jika tidak ingin mengubah password">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                               placeholder="Ulangi password baru">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('platform.users.show', $user->id) }}" 
                   class="inline-flex items-center justify-center px-5 py-2.5 text-gray-700 bg-white border-2 border-gray-300 hover:border-gray-400 hover:bg-gray-50 rounded-lg transition-all duration-200 font-medium shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Batal
                </a>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('platform.users.show', $user->id) }}" 
                       class="inline-flex items-center justify-center px-5 py-2.5 text-blue-700 bg-blue-50 border-2 border-blue-200 hover:border-blue-300 hover:bg-blue-100 rounded-lg transition-all duration-200 font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.514 1.514-3.182 3-5.254 3H5.414C3.182 15 1.514 13.486 0 12c1.514-1.486 3.182-3 5.254-3h12.092c2.072 0 3.74 1.514 5.254 3z"/>
                        </svg>
                        Lihat Detail
                    </a>
                    
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-5 py-2.5 text-white bg-blue-600 hover:bg-blue-700 border-2 border-blue-600 hover:border-blue-700 rounded-lg transition-all duration-200 font-medium shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-platform-layout>
