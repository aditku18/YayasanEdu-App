<x-platform-layout>
    <x-slot name="header">Detail Pengguna</x-slot>
    <x-slot name="subtitle">Informasi lengkap pengguna {{ $user->name }}</x-slot>

    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Informasi Dasar -->
            <div class="md:col-span-2 space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Informasi Pengguna</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nama Lengkap</label>
                        <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                        <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Role</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($user->getRoleNames() as $role)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    @if($role === 'platform_admin') bg-purple-100 text-purple-800
                                    @elseif($role === 'foundation_admin') bg-blue-100 text-blue-800
                                    @elseif($role === 'school_admin') bg-green-100 text-green-800
                                    @elseif($role === 'teacher') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($role) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        <div>
                            @if($user->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Terdaftar Sejak</label>
                        <p class="text-gray-900 font-medium">{{ $user->created_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Foto Profile -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Foto Profile</h3>
                
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 bg-gradient-to-br from-primary-400 to-primary-600 rounded-2xl flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    
                    <div class="text-center mt-4">
                        <p class="text-sm text-gray-500">Avatar Default</p>
                        <p class="text-xs text-gray-400 mt-1">Belum ada foto profile</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Tambahan -->
        @if($user->schoolUnit)
        <div class="mt-8 pt-8 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Informasi Sekolah</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nama Sekolah</label>
                    <p class="text-gray-900 font-medium">{{ $user->schoolUnit->name ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Email Sekolah</label>
                    <p class="text-gray-900 font-medium">{{ $user->schoolUnit->email ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Telepon Sekolah</label>
                    <p class="text-gray-900 font-medium">{{ $user->schoolUnit->phone ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Alamat Sekolah</label>
                    <p class="text-gray-900 font-medium">{{ $user->schoolUnit->address ?? '-' }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('platform.users.index') }}" 
               class="inline-flex items-center justify-center px-5 py-2.5 text-gray-700 bg-white border-2 border-gray-300 hover:border-gray-400 hover:bg-gray-50 rounded-lg transition-all duration-200 font-medium shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('platform.users.edit', $user->id) }}" 
                   class="inline-flex items-center justify-center px-5 py-2.5 text-blue-700 bg-blue-50 border-2 border-blue-200 hover:border-blue-300 hover:bg-blue-100 rounded-lg transition-all duration-200 font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5h-3V7a2 2 0 00-2-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 3.5a2 2 0 013 0V7a2 2 0 01-3 0V3.5z"/>
                    </svg>
                    Edit Pengguna
                </a>
                
                <form action="{{ route('platform.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="event.preventDefault(); if(confirm('Hapus pengguna ini? Data tidak dapat dikembalikan.')) { this.submit(); }">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 text-white bg-red-600 hover:bg-red-700 border-2 border-red-600 hover:border-red-700 rounded-lg transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Pengguna
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-platform-layout>
