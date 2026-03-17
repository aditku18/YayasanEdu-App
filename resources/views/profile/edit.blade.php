<x-platform-layout>
    <x-slot name="header">Profil Pengguna</x-slot>
    <x-slot name="subtitle">Kelola informasi akun dan pengaturan profil</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Profile Information --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Informasi Profil</h3>
                    @include('profile.partials.update-profile-information-form')
                </div>

                {{-- Password Update --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Ubah Password</h3>
                    @include('profile.partials.update-password-form')
                </div>

                {{-- Delete Account --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Hapus Akun</h3>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- User Avatar --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Avatar</h3>
                    <div class="flex flex-col items-center">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xl mb-4">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="text-center">
                            <p class="font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                Bergabung sejak {{ Auth::user()->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Account Stats --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Statistik Akun</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="text-sm font-medium text-green-600">Aktif</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Email Terverifikasi</span>
                            <span class="text-sm font-medium {{ Auth::user()->email_verified_at ? 'text-green-600' : 'text-orange-600' }}">
                                {{ Auth::user()->email_verified_at ? 'Ya' : 'Tidak' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Terakhir Login</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'Tidak ada data' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="space-y-2">
                        <a href="{{ route('profile.edit') }}" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium text-center block">
                            Edit Profil
                        </a>
                        <button onclick="window.history.back()" class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                            Kembali
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-platform-layout>
