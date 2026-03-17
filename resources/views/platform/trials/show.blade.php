<x-platform-layout>
    <x-slot name="header">Detail Trial</x-slot>
    <x-slot name="subtitle">Informasi lengkap trial yayasan</x-slot>

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

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('platform.trials.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Trial
        </a>
    </div>

    <!-- Trial Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Foundation Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Yayasan</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $trial->name }}</p>
                            <p class="text-sm text-gray-500">{{ $trial->email }}</p>
                            <p class="text-sm text-gray-400">{{ $trial->subdomain }}.edusaaS.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trial Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Trial</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Status Trial</p>
                        <div class="mt-1">
                            @if($trial->trial_ends_at && $trial->trial_ends_at->isFuture())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @elseif($trial->trial_ends_at && $trial->trial_ends_at->isPast())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    Kadaluarsa
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    Tidak Diketahui
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Sisa Waktu</p>
                        <p class="mt-1 font-medium text-gray-900">
                            @if($trial->trial_ends_at && $trial->trial_ends_at->isFuture())
                                {{ $trial->trial_ends_at->diffInDays(now()) }} hari
                            @elseif($trial->trial_ends_at && $trial->trial_ends_at->isPast())
                                Kadaluarsa
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Mulai</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $trial->created_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Berakhir</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $trial->trial_ends_at ? $trial->trial_ends_at->format('d M Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Paket Saat Ini</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $trial->plan->name ?? 'Free' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Dibuat</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $trial->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                @if($trial->trial_ends_at && $trial->trial_ends_at->isFuture())
                    <div class="mt-4 p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-green-600 font-medium">Trial aktif hingga {{ $trial->trial_ends_at->format('d M Y H:i') }}</p>
                        <p class="text-sm text-green-500 mt-1">Sisa waktu: {{ $trial->trial_ends_at->diffForHumans(now(), true) }}</p>
                    </div>
                @elseif($trial->trial_ends_at && $trial->trial_ends_at->isPast())
                    <div class="mt-4 p-4 bg-red-50 rounded-lg">
                        <p class="text-sm text-red-600 font-medium">Trial telah kadaluarsa pada {{ $trial->trial_ends_at->format('d M Y H:i') }}</p>
                        <p class="text-sm text-red-500 mt-1">Kadaluarsa {{ $trial->trial_ends_at->diffForHumans() }}</p>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi</h3>
                <div class="flex flex-wrap gap-3">
                    @if($trial->trial_ends_at && $trial->trial_ends_at->isFuture())
                        <button onclick="showExtendModal({{ $trial->id }})" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Perpanjang Trial
                        </button>
                        <button onclick="showConvertModal({{ $trial->id }})" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Konversi ke Langganan
                        </button>
                    @endif
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Kirim Email
                    </button>
                    <button class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        Reminder
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Statistik Cepat</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Sekolah</span>
                        <span class="text-sm font-bold text-gray-900">{{ $trial->schools->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Pengguna</span>
                        <span class="text-sm font-bold text-gray-900">{{ $trial->users->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Status</span>
                        <span class="text-sm font-bold text-gray-900">{{ ucfirst($trial->status) }}</span>
                    </div>
                </div>
            </div>

            <!-- Trial Progress -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Progress Trial</h3>
                @if($trial->trial_ends_at)
                    @php
                        $totalDays = $trial->created_at->diffInDays($trial->trial_ends_at);
                        $remainingDays = now()->diffInDays($trial->trial_ends_at, false);
                        $usedDays = $totalDays - max(0, $remainingDays);
                        $progress = $totalDays > 0 ? ($usedDays / $totalDays) * 100 : 0;
                    @endphp
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Progress</span>
                            <span class="font-medium text-gray-900">{{ round($progress, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, $progress) }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>{{ $usedDays }} hari digunakan</span>
                            <span>{{ max(0, $remainingDays) }} hari tersisa</span>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500">Informasi trial tidak tersedia</p>
                @endif
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aktivitas Terbaru</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100 last:border-0">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Trial Dimulai</p>
                            <p class="text-xs text-gray-500">{{ $trial->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @if($trial->updated_at != $trial->created_at)
                        <div class="flex items-center gap-3 pb-3 border-b border-gray-100 last:border-0">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Terakhir Diperbarui</p>
                                <p class="text-xs text-gray-500">{{ $trial->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Extend Modal -->
    <div id="extendModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Perpanjang Trial</h3>
                <button onclick="closeExtendModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="extendForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Hari</label>
                    <input type="number" name="days" min="1" max="30" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Masukkan jumlah hari (1-30)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Perpanjangan</label>
                    <textarea name="reason" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Masukkan alasan perpanjangan..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeExtendModal()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Perpanjang Trial
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Convert Modal -->
    <div id="convertModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Konversi ke Langganan</h3>
                <button onclick="closeConvertModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="convertForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Paket Langganan</label>
                    <select name="plan_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih paket...</option>
                        {{-- This would need to be populated with actual plans --}}
                        <option value="1">Basic</option>
                        <option value="2">Premium</option>
                        <option value="3">Enterprise</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeConvertModal()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Konversi ke Langganan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showExtendModal(trialId) {
            document.getElementById('extendModal').classList.remove('hidden');
            document.getElementById('extendForm').action = `/platform/trials/${trialId}/extend`;
        }

        function closeExtendModal() {
            document.getElementById('extendModal').classList.add('hidden');
            document.getElementById('extendForm').reset();
        }

        function showConvertModal(trialId) {
            document.getElementById('convertModal').classList.remove('hidden');
            document.getElementById('convertForm').action = `/platform/trials/${trialId}/convert`;
        }

        function closeConvertModal() {
            document.getElementById('convertModal').classList.add('hidden');
            document.getElementById('convertForm').reset();
        }
    </script>
</x-platform-layout>
