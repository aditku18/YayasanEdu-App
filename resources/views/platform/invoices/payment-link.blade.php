<x-platform-layout>
    <x-slot name="header">Link Pembayaran</x-slot>
    <x-slot name="subtitle">Bagikan link ini kepada yayasan untuk melakukan pembayaran</x-slot>

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('platform.invoices.show', $invoice->foundation_id) }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Detail Invoice
        </a>
    </div>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Link Pembayaran Invoice</h3>
                        <p class="text-gray-500">Invoice #{{ $invoice->invoice_number }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-sm text-gray-400 uppercase tracking-wider font-semibold mb-1">Yayasan</p>
                        <p class="text-lg font-bold text-gray-900">{{ $invoice->foundation->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-400 uppercase tracking-wider font-semibold mb-1">Total Tagihan</p>
                        <p class="text-2xl font-black text-indigo-600">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-8 space-y-8">
                <!-- Payment URL -->
                <div>
                    <label for="paymentUrl" class="block text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide">URL Pembayaran</label>
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <input type="text" id="paymentUrl" readonly value="{{ $paymentUrl }}" 
                                   class="w-full pl-4 pr-12 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-600 focus:ring-0 focus:border-gray-200 cursor-default">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2v12a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                        </div>
                        <button onclick="copyToClipboard()" class="px-6 py-3.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 whitespace-nowrap">
                            Copy Link
                        </button>
                    </div>
                    <p class="mt-3 text-sm text-gray-500 italic flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Bagikan link ini secara manual atau kirim otomatis via email di bawah.
                    </p>
                </div>

                <!-- Action Section -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-gray-50">
                    <form action="{{ route('platform.invoices.send-payment-link', $invoice->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-3 px-8 py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 group">
                            <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Kirim via Email
                        </button>
                    </form>
                    
                    <a href="{{ route('platform.invoices.show', $invoice->foundation_id) }}" class="flex items-center justify-center gap-2 px-8 py-4 bg-gray-100 text-gray-700 font-bold rounded-2xl hover:bg-gray-200 transition-all">
                        Tutup
                    </a>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="px-8 py-6 bg-yellow-50/50 border-t border-yellow-100">
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-yellow-900 mb-1">Cara Penggunaan</h4>
                        <ol class="text-sm text-yellow-800 space-y-1 list-decimal list-inside">
                            <li>Copy URL atau klik tombol kirim via email</li>
                            <li>Yayasan akan menerima link dan dapat melakukan pembayaran</li>
                            <li>Status invoice akan otomatis berubah setelah konfirmasi pembayaran</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function copyToClipboard() {
        const copyText = document.getElementById("paymentUrl");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value).then(() => {
            alert("Link pembayaran berhasil disalin!");
        });
    }
    </script>
    @endpush
</x-platform-layout>
