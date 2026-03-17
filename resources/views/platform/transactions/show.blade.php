<x-platform-layout>
    <x-slot name="header">Detail Transaksi</x-slot>
    <x-slot name="subtitle">Informasi lengkap transaksi yayasan</x-slot>

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
        <a href="{{ route('platform.transactions.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Transaksi
        </a>
    </div>

    <!-- Transaction Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Transaction Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Transaksi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">ID Transaksi</p>
                        <p class="mt-1 font-medium text-gray-900">#{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Transaction ID</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $transaction->transaction_id ?? 'Auto-generated' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tipe</p>
                        <div class="mt-1">
                            @if($transaction->type == 'subscription')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    Langganan
                                </span>
                            @elseif($transaction->type == 'addon')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    Add-on
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    Lainnya
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <div class="mt-1">
                            @if($transaction->status == 'success')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Berhasil
                                </span>
                            @elseif($transaction->status == 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    Menunggu
                                </span>
                            @elseif($transaction->status == 'failed')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    Gagal
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Amount</p>
                        <p class="mt-1 font-bold text-lg text-gray-900">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                @if($transaction->description)
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm font-medium text-gray-700 mb-2">Deskripsi</p>
                        <p class="text-sm text-gray-600">{{ $transaction->description }}</p>
                    </div>
                @endif

                @if($transaction->status == 'pending')
                    <div class="mt-4 p-4 bg-yellow-50 rounded-lg">
                        <p class="text-sm text-yellow-600 font-medium">Transaksi menunggu konfirmasi</p>
                        <p class="text-sm text-yellow-500 mt-1">Silakan konfirmasi atau batalkan transaksi ini</p>
                    </div>
                @elseif($transaction->status == 'success')
                    <div class="mt-4 p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-green-600 font-medium">Transaksi berhasil</p>
                        <p class="text-sm text-green-500 mt-1">Transaksi telah dikonfirmasi dan diproses</p>
                    </div>
                @elseif($transaction->status == 'failed')
                    <div class="mt-4 p-4 bg-red-50 rounded-lg">
                        <p class="text-sm text-red-600 font-medium">Transaksi gagal</p>
                        <p class="text-sm text-red-500 mt-1">Transaksi telah dibatalkan atau gagal diproses</p>
                    </div>
                @endif
            </div>

            <!-- Foundation Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Yayasan</h3>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $transaction->foundation->name }}</p>
                        <p class="text-sm text-gray-500">{{ $transaction->foundation->email }}</p>
                        <p class="text-sm text-gray-400">{{ $transaction->foundation->subdomain }}.edusaaS.com</p>
                    </div>
                </div>
            </div>

            <!-- Plan Info -->
            @if($transaction->plan)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Paket</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $transaction->plan->name }}</p>
                            <p class="text-sm text-gray-500">Rp {{ number_format($transaction->plan->price_per_month, 0, ',', '.') }}/bulan</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Payment Info -->
            @if($transaction->payment)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Pembayaran</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Payment ID</p>
                            <p class="mt-1 font-medium text-gray-900">{{ $transaction->payment->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Gateway</p>
                            <p class="mt-1 font-medium text-gray-900">{{ $transaction->payment->gateway ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status Pembayaran</p>
                            <p class="mt-1 font-medium text-gray-900">{{ ucfirst($transaction->payment->status) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Pembayaran</p>
                            <p class="mt-1 font-medium text-gray-900">{{ $transaction->payment->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            @if($transaction->status == 'pending')
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Transaksi</h3>
                    <div class="flex flex-wrap gap-3">
                        <button onclick="showConfirmModal({{ $transaction->id }})" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Konfirmasi Transaksi
                        </button>
                        <button onclick="showCancelModal({{ $transaction->id }})" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l-2-2m2 2l2-2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Batalkan Transaksi
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Transaction Summary -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Transaksi</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">ID Transaksi</span>
                        <span class="text-sm font-medium text-gray-900">#{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Tipe</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($transaction->type) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Amount</span>
                        <span class="text-sm font-bold text-gray-900">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Status</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($transaction->status) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Yayasan</span>
                        <span class="text-sm font-medium text-gray-900">{{ $transaction->foundation->name }}</span>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Transaksi Dibuat</p>
                            <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @if($transaction->status == 'success')
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Transaksi Berhasil</p>
                                <p class="text-xs text-gray-500">{{ $transaction->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @elseif($transaction->status == 'failed')
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-red-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Transaksi Gagal</p>
                                <p class="text-xs text-gray-500">{{ $transaction->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-2">
                    <button class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Kirim Email
                    </button>
                    <button class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Download PDF
                    </button>
                    <button class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Modal -->
    <div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Konfirmasi Transaksi</h3>
                <button onclick="closeConfirmModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="confirmForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <div class="p-4 bg-green-50 rounded-lg">
                    <p class="text-sm text-green-800">Transaksi akan dikonfirmasi sebagai berhasil.</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeConfirmModal()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Batalkan Transaksi</h3>
                <button onclick="closeCancelModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="cancelForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <div class="p-4 bg-red-50 rounded-lg">
                    <p class="text-sm text-red-800">Transaksi akan dibatalkan dan ditandai sebagai gagal.</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCancelModal()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showConfirmModal(transactionId) {
            document.getElementById('confirmModal').classList.remove('hidden');
            document.getElementById('confirmForm').action = `/platform/transactions/${transactionId}/confirm`;
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        function showCancelModal(transactionId) {
            document.getElementById('cancelModal').classList.remove('hidden');
            document.getElementById('cancelForm').action = `/platform/transactions/${transactionId}/cancel`;
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }
    </script>
</x-platform-layout>
