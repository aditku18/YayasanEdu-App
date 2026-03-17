<x-platform-layout>
    <x-slot name="header">Detail Langganan</x-slot>
    <x-slot name="subtitle">Informasi lengkap langganan yayasan</x-slot>

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
        <a href="{{ route('platform.subscriptions.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Langganan
        </a>
    </div>

    <!-- Subscription Details -->
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
                            <p class="font-medium text-gray-900">{{ $subscription->foundation->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">{{ $subscription->foundation->email ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-400">{{ $subscription->foundation->subdomain ?? 'N/A' }}.edusaaS.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Langganan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <div class="mt-1">
                            @if($subscription->status == 'active')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @elseif($subscription->status == 'trial')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    Trial
                                </span>
                            @elseif($subscription->status == 'cancelled')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    Dibatalkan
                                </span>
                            @elseif($subscription->status == 'expired')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                    Kadaluarsa
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Paket</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $subscription->plan->name ?? 'Free' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Harga</p>
                        <p class="mt-1 font-medium text-gray-900">Rp {{ number_format($subscription->price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Siklus Penagihan</p>
                        <p class="mt-1 font-medium text-gray-900">{{ ucfirst($subscription->billing_cycle ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Mulai</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $subscription->starts_at ? $subscription->starts_at->format('d M Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Berakhir</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $subscription->ends_at ? $subscription->ends_at->format('d M Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Auto Renew</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $subscription->auto_renew ? 'Ya' : 'Tidak' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Dibuat</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $subscription->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                @if($subscription->cancelled_at)
                    <div class="mt-4 p-4 bg-red-50 rounded-lg">
                        <p class="text-sm text-red-600 font-medium">Dibatalkan pada: {{ $subscription->cancelled_at->format('d M Y H:i') }}</p>
                        @if($subscription->cancellation_reason)
                            <p class="text-sm text-red-500 mt-1">Alasan: {{ $subscription->cancellation_reason }}</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi</h3>
                <div class="flex flex-wrap gap-3">
                    @if($subscription->status == 'cancelled')
                        <form action="{{ route('platform.subscriptions.reactivate', $subscription->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Aktifkan Kembali
                            </button>
                        </form>
                    @elseif($subscription->status == 'active')
                        <button onclick="showCancelModal({{ $subscription->id }})" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Batalkan Langganan
                        </button>
                    @endif
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Kirim Invoice
                    </button>
                    <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
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
                        <span class="text-sm text-gray-600">Total Pembayaran</span>
                        <span class="text-sm font-bold text-gray-900">{{ $subscription->payments->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Dibayar</span>
                        <span class="text-sm font-bold text-gray-900">Rp {{ number_format($subscription->payments->sum('amount'), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Sisa Hari</span>
                        <span class="text-sm font-bold text-gray-900">
                            @if($subscription->ends_at)
                                {{ max(0, $subscription->ends_at->diffInDays(now())) }} hari
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Riwayat Pembayaran</h3>
                <div class="space-y-3">
                    @forelse($subscription->payments as $payment)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500">{{ $payment->created_at->format('d M Y') }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-600">
                                {{ ucfirst($payment->status ?? 'completed') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada pembayaran</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Batalkan Langganan</h3>
                <button onclick="closeCancelModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="cancelForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Pembatalan</label>
                    <textarea name="reason" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Masukkan alasan pembatalan..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCancelModal()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Batalkan Langganan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showCancelModal(subscriptionId) {
            document.getElementById('cancelModal').classList.remove('hidden');
            document.getElementById('cancelForm').action = `/platform/subscriptions/${subscriptionId}/cancel`;
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
            document.getElementById('cancelForm').reset();
        }
    </script>
</x-platform-layout>
