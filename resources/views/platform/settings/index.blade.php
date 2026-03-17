<x-platform-layout>
    <x-slot name="header">Pengaturan Platform</x-slot>
    <x-slot name="subtitle">Kelola konfigurasi dan pengaturan sistem platform</x-slot>

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

    {{-- Statistics Dashboard --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Pengaturan</p>
                    <p class="text-3xl font-bold mt-1">{{ count($settings) }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Sistem Aktif</p>
                    <p class="text-3xl font-bold mt-1">{{ $systemInfo['cache_status'] == 'Connected' ? 'Aktif' : 'Error' }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Maintenance</p>
                    <p class="text-3xl font-bold mt-1">{{ $settings['maintenance_mode'] ? 'Aktif' : 'Non-aktif' }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Debug Mode</p>
                    <p class="text-3xl font-bold mt-1">{{ $settings['debug_mode'] ? 'Aktif' : 'Non-aktif' }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l-2-2m2 2l2-2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">PHP Version</p>
                    <p class="text-3xl font-bold mt-1">{{ Str::before($systemInfo['php_version'], '.') }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Settings Configuration -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-900">Konfigurasi Sistem</h2>
                        <span class="text-sm text-gray-500">{{ count($settings) }} pengaturan</span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengaturan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">Nama Aplikasi</div>
                                    <div class="text-sm text-gray-500">APP_NAME</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $settings['app_name'] }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <button onclick="showEditModal('app_name', '{{ $settings['app_name'] }}')" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">URL Aplikasi</div>
                                    <div class="text-sm text-gray-500">APP_URL</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $settings['app_url'] }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <button onclick="showEditModal('app_url', '{{ $settings['app_url'] }}')" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">Timezone</div>
                                    <div class="text-sm text-gray-500">APP_TIMEZONE</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $settings['timezone'] }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <select onchange="quickUpdate('timezone', this.value)" class="text-indigo-600 hover:text-indigo-900 bg-transparent border-0 p-0">
                                        <option value="Asia/Jakarta" {{ $settings['timezone'] == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta</option>
                                        <option value="Asia/Makassar" {{ $settings['timezone'] == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar</option>
                                        <option value="Asia/Jayapura" {{ $settings['timezone'] == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura</option>
                                        <option value="UTC" {{ $settings['timezone'] == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">Bahasa</div>
                                    <div class="text-sm text-gray-500">APP_LOCALE</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $settings['locale'] == 'id' ? 'Indonesia' : 'English' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <select onchange="quickUpdate('locale', this.value)" class="text-indigo-600 hover:text-indigo-900 bg-transparent border-0 p-0">
                                        <option value="id" {{ $settings['locale'] == 'id' ? 'selected' : '' }}>Indonesia</option>
                                        <option value="en" {{ $settings['locale'] == 'en' ? 'selected' : '' }}>English</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">Debug Mode</div>
                                    <div class="text-sm text-gray-500">APP_DEBUG</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $settings['debug_mode'] ? 'Aktif' : 'Non-aktif' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($settings['debug_mode'])
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Warning
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Aman
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <button onclick="toggleSetting('debug_mode', {{ $settings['debug_mode'] ? 'false' : 'true' }})" 
                                            class="text-indigo-600 hover:text-indigo-900">
                                        {{ $settings['debug_mode'] ? 'Non-aktifkan' : 'Aktifkan' }}
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">Maintenance Mode</div>
                                    <div class="text-sm text-gray-500">APP_MAINTENANCE</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $settings['maintenance_mode'] ? 'Aktif' : 'Non-aktif' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($settings['maintenance_mode'])
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Maintenance
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Normal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <button onclick="toggleSetting('maintenance_mode', {{ $settings['maintenance_mode'] ? 'false' : 'true' }})" 
                                            class="text-indigo-600 hover:text-indigo-900">
                                        {{ $settings['maintenance_mode'] ? 'Non-aktifkan' : 'Aktifkan' }}
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- System Information -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Sistem</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">PHP Version</span>
                        <span class="text-sm font-medium text-gray-900">{{ $systemInfo['php_version'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Laravel Version</span>
                        <span class="text-sm font-medium text-gray-900">{{ $systemInfo['laravel_version'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Server</span>
                        <span class="text-sm font-medium text-gray-900">{{ Str::limit($systemInfo['server_software'], 15) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Database</span>
                        <span class="text-sm font-medium text-gray-900">{{ Str::limit($systemInfo['database_version'], 10) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Cache Status</span>
                        <span class="text-sm font-medium {{ $systemInfo['cache_status'] == 'Connected' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $systemInfo['cache_status'] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- System Health -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Kesehatan Sistem</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Storage Writable</span>
                        <div class="flex items-center">
                            @if($systemInfo['storage_writable'])
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-sm font-medium text-green-600">OK</span>
                            @else
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                <span class="text-sm font-medium text-red-600">Error</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Logs Writable</span>
                        <div class="flex items-center">
                            @if($systemInfo['logs_writable'])
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-sm font-medium text-green-600">OK</span>
                            @else
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                <span class="text-sm font-medium text-red-600">Error</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Memory Usage</span>
                        <span class="text-sm font-medium text-gray-900">{{ round(memory_get_peak_usage(true) / 1024 / 1024, 1) }} MB</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Uptime</span>
                        <span class="text-sm font-medium text-gray-900">{{ round((time() - LARAVEL_START) / 60, 1) }} min</span>
                    </div>
                </div>
            </div>

            <!-- Driver Configuration -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Driver Konfigurasi</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">CACHE_DRIVER</span>
                        <span class="font-mono text-gray-900">{{ $settings['cache_driver'] }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">SESSION_DRIVER</span>
                        <span class="font-mono text-gray-900">{{ $settings['session_driver'] }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">MAIL_MAILER</span>
                        <span class="font-mono text-gray-900">{{ $settings['mail_driver'] }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">DB_CONNECTION</span>
                        <span class="font-mono text-gray-900">{{ $settings['database_connection'] }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">BROADCAST_DRIVER</span>
                        <span class="font-mono text-gray-900">{{ $settings['broadcast_driver'] }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">QUEUE_CONNECTION</span>
                        <span class="font-mono text-gray-900">{{ $settings['queue_driver'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-2">
                    <form method="POST" action="{{ route('platform.settings.cache') }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Bersihkan Cache
                        </button>
                    </form>
                    <form method="POST" action="{{ route('platform.settings.optimize') }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Optimalkan
                        </button>
                    </form>
                    <form method="POST" action="{{ route('platform.settings.backup') }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                            Backup Data
                        </button>
                    </form>
                    <button onclick="exportSettings()" class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export Settings
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Edit Pengaturan</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label id="editLabel" class="block text-sm font-medium text-gray-700 mb-2">Label</label>
                    <input type="text" id="editInput" name="value" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    <input type="hidden" id="editKey" name="key">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showEditModal(key, currentValue) {
            const labels = {
                'app_name': 'Nama Aplikasi',
                'app_url': 'URL Aplikasi'
            };
            
            document.getElementById('editLabel').textContent = labels[key] || key;
            document.getElementById('editInput').value = currentValue;
            document.getElementById('editKey').value = key;
            document.getElementById('editForm').action = '/platform/settings/update-single';
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function quickUpdate(key, value) {
            fetch('/platform/settings/quick-update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ key: key, value: value })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        function toggleSetting(key, value) {
            fetch('/platform/settings/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ key: key, value: value })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        function exportSettings() {
            const settings = @json($settings);
            const dataStr = JSON.stringify(settings, null, 2);
            const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);

            const exportFileDefaultName = 'platform-settings-' + new Date().toISOString().split('T')[0] + '.json';

            const linkElement = document.createElement('a');
            linkElement.setAttribute('href', dataUri);
            linkElement.setAttribute('download', exportFileDefaultName);
            linkElement.click();
        }
    </script>
</x-platform-layout>
