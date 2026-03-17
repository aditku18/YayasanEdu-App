<x-platform-layout>
    <x-slot name="header">Buat Refund Baru</x-slot>
    <x-slot name="subtitle">Ajukan permintaan refund untuk transaksi</x-slot>

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

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('platform.refunds.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Refund
        </a>
    </div>

    {{-- Create Refund Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('platform.refunds.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Transaction Selection --}}
            <div>
                <label for="transaction_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Transaksi <span class="text-red-500">*</span>
                </label>
                <select name="transaction_id" id="transaction_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Pilih Transaksi --</option>
                    @forelse($transactions as $transaction)
                        <option value="{{ $transaction->id }}" {{ old('transaction_id') == $transaction->id ? 'selected' : '' }}>
                            #{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }} - 
                            {{ $transaction->foundation->name ?? 'N/A' }} - 
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            ({{ $transaction->created_at->format('d M Y') }})
                        </option>
                    @empty
                        <option value="" disabled>Tidak ada transaksi yang tersedia</option>
                    @endforelse
                </select>
                @error('transaction_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Hanya transaksi dengan status success yang belum memiliki refund</p>
            </div>

            {{-- Refund Type --}}
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipe Refund <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="full" {{ old('type') == 'full' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500" required>
                        <span class="ml-2 text-gray-700">Refund Penuh</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="partial" {{ old('type') == 'partial' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-gray-700">Refund Sebagian</span>
                    </label>
                </div>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Amount (for partial refund) --}}
            <div id="amount-container" class="hidden">
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                    Jumlah Refund <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        min="0" step="0.01">
                </div>
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Reason --}}
            <div>
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Alasan Refund <span class="text-red-500">*</span>
                </label>
                <textarea name="reason" id="reason" rows="4" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Jelaskan alasan pengajuan refund...">{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('platform.refunds.index') }}" 
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Ajukan Refund
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeRadios = document.querySelectorAll('input[name="type"]');
            const amountContainer = document.getElementById('amount-container');
            const transactionSelect = document.getElementById('transaction_id');

            function toggleAmountField() {
                const selectedType = document.querySelector('input[name="type"]:checked');
                if (selectedType && selectedType.value === 'partial') {
                    amountContainer.classList.remove('hidden');
                } else {
                    amountContainer.classList.add('hidden');
                }
            }

            typeRadios.forEach(radio => {
                radio.addEventListener('change', toggleAmountField);
            });

            // Initialize on page load
            toggleAmountField();
        });
    </script>
    @endpush
</x-platform-layout>
