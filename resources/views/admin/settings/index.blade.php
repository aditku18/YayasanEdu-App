<x-platform-layout>
    <x-slot name="header">Pengaturan Global</x-slot>
    <x-slot name="subtitle">Kelola konfigurasi sistem dan pengaturan platform</x-slot>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Settings Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900">Pengaturan Umum</h2>
                    <p class="text-sm text-gray-600 mt-1">Konfigurasi dasar platform Anda</p>
                </div>
                
                <form action="{{ route('platform.settings.update') }}" method="POST" class="p-6">
                    @csrf
                    
                    {{-- Site Name --}}
                    <div class="mb-6">
                        <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Platform
                        </label>
                        <input type="text" 
                               id="site_name" 
                               name="site_name" 
                               value="{{ $settings['site_name'] ?? config('app.name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                               placeholder="Masukkan nama platform">
                        <p class="text-xs text-gray-500 mt-1">Nama yang akan ditampilkan di header dan judul halaman</p>
                    </div>

                    {{-- Support Email --}}
                    <div class="mb-6">
                        <label for="support_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Support
                        </label>
                        <input type="email" 
                               id="support_email" 
                               name="support_email" 
                               value="{{ $settings['support_email'] ?? '' }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                               placeholder="support@example.com">
                        <p class="text-xs text-gray-500 mt-1">Email untuk kontak bantuan dan dukungan pengguna</p>
                    </div>

                    {{-- Payment Gateway --}}
                    <div class="mb-6">
                        <label for="payment_gateway" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Gateway Default
                        </label>
                        <select id="payment_gateway" 
                                name="payment_gateway" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <option value="manual" {{ ($settings['payment_gateway'] ?? 'manual') == 'manual' ? 'selected' : '' }}>
                                Manual (Transfer Bank)
                            </option>
                            <option value="midtrans" {{ ($settings['payment_gateway'] ?? '') == 'midtrans' ? 'selected' : '' }}>
                                Midtrans
                            </option>
                            <option value="xendit" {{ ($settings['payment_gateway'] ?? '') == 'xendit' ? 'selected' : '' }}>
                                Xendit
                            </option>
                            <option value="stripe" {{ ($settings['payment_gateway'] ?? '') == 'stripe' ? 'selected' : '' }}>
                                Stripe
                            </option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Gateway pembayaran yang digunakan untuk transaksi</p>
                    </div>

                    {{-- Maintenance Mode --}}
                    <div class="mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <label for="maintenance_mode" class="block text-sm font-medium text-gray-700 mb-2">
                                    Maintenance Mode
                                </label>
                                <p class="text-xs text-gray-500">Matikan sementara akses pengguna ke platform</p>
                            </div>
                            <div class="flex items-center">
                                <input type="hidden" name="maintenance_mode" value="0">
                                <input type="checkbox" 
                                       id="maintenance_mode" 
                                       name="maintenance_mode" 
                                       value="1"
                                       {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }}
                                       class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('platform.dashboard') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-lg hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sidebar Info --}}
        <div class="space-y-6">
            {{-- System Info --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Sistem</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Versi</span>
                        <span class="text-sm font-medium text-gray-900">v1.0.0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Laravel</span>
                        <span class="text-sm font-medium text-gray-900">{{ app()->version() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">PHP</span>
                        <span class="text-sm font-medium text-gray-900">{{ constant('PHP_VERSION') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Environment</span>
                        <span class="text-sm font-medium {{ app()->environment('production') ? 'text-amber-600' : 'text-emerald-600' }}">
                            {{ app()->environment() }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-2">
                    <button class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium text-left">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Clear Cache
                    </button>
                    <button class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium text-left">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                        </svg>
                        Backup Database
                    </button>
                    <button class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium text-left">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        </svg>
                        System Logs
                    </button>
                </div>
            </div>

            {{-- Status Card --}}
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold">System Status</h4>
                        <p class="text-indigo-100 text-sm">All systems operational</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-indigo-100">Uptime</span>
                        <span class="font-medium">99.9%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-indigo-100">Response Time</span>
                        <span class="font-medium">120ms</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-platform-layout>
