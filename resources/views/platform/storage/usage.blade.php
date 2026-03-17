<x-platform-layout>
    <x-slot name="header">Detail Penggunaan Storage</x-slot>
    <x-slot name="subtitle">Analisis storage untuk {{ $foundation->name }}</x-slot>

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('platform.storage.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Monitoring Storage
        </a>
    </div>

    <!-- Foundation Info -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Yayasan</h3>
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <p class="font-medium text-gray-900">{{ $foundation->name }}</p>
                <p class="text-sm text-gray-500">{{ $foundation->email }}</p>
                <p class="text-sm text-gray-400">{{ $foundation->subdomain }}.edusaaS.com</p>
            </div>
        </div>
    </div>

    <!-- Storage Usage Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Usage</p>
                    <p class="text-3xl font-bold mt-1">{{ $usage['total'] }} MB</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Documents</p>
                    <p class="text-3xl font-bold mt-1">{{ $usage['documents'] }} MB</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Images</p>
                    <p class="text-3xl font-bold mt-1">{{ $usage['images'] }} MB</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Videos</p>
                    <p class="text-3xl font-bold mt-1">{{ $usage['videos'] }} MB</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Storage Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- File Type Distribution -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Distribusi Tipe File</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Documents</span>
                    <div class="flex items-center gap-2">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $usage['total'] > 0 ? ($usage['documents'] / $usage['total']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $usage['documents'] }} MB</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Images</span>
                    <div class="flex items-center gap-2">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $usage['total'] > 0 ? ($usage['images'] / $usage['total']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $usage['images'] }} MB</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Videos</span>
                    <div class="flex items-center gap-2">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $usage['total'] > 0 ? ($usage['videos'] / $usage['total']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $usage['videos'] }} MB</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Temp Files</span>
                    <div class="flex items-center gap-2">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full" style="width: {{ $usage['total'] > 0 ? ($usage['temp_files'] / $usage['total']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $usage['temp_files'] }} MB</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Storage Recommendations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Rekomendasi</h3>
            <div class="space-y-3">
                @if($usage['temp_files'] > 50)
                    <div class="p-4 bg-red-50 rounded-lg">
                        <p class="text-sm text-red-600 font-medium">Bersihkan Temp Files</p>
                        <p class="text-sm text-red-500 mt-1">Temp files terlalu besar ({{ $usage['temp_files'] }} MB)</p>
                    </div>
                @endif
                @if($usage['videos'] > 200)
                    <div class="p-4 bg-yellow-50 rounded-lg">
                        <p class="text-sm text-yellow-600 font-medium">Optimasi Video</p>
                        <p class="text-sm text-yellow-500 mt-1">Pertimbangkan kompresi video</p>
                    </div>
                @endif
                @if($usage['total'] > 500)
                    <div class="p-4 bg-orange-50 rounded-lg">
                        <p class="text-sm text-orange-600 font-medium">Storage Tinggi</p>
                        <p class="text-sm text-orange-500 mt-1">Pertimbangkan upgrade storage</p>
                    </div>
                @endif
                @if($usage['total'] < 100)
                    <div class="p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-green-600 font-medium">Storage Optimal</p>
                        <p class="text-sm text-green-500 mt-1">Penggunaan storage efisien</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Storage Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Storage</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Generate Report
            </button>
            <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Download Backup
            </button>
            <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Cleanup Storage
            </button>
        </div>
    </div>
</x-platform-layout>
