<x-platform-layout>
    <x-slot name="header">Detail Invoice</x-slot>
    <x-slot name="subtitle">Informasi lengkap invoice yayasan</x-slot>

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
        <a href="{{ route('platform.invoices.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Invoice
        </a>
    </div>

    <!-- Invoice Details -->
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
                            <p class="font-medium text-gray-900">{{ $foundation->name }}</p>
                            <p class="text-sm text-gray-500">{{ $foundation->email }}</p>
                            <p class="text-sm text-gray-400">{{ $foundation->subdomain }}.edusaaS.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Langganan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Status Langganan</p>
                        <div class="mt-1">
                            @if($foundation->status == 'active')
                                @if($foundation->subscription_ends_at && $foundation->subscription_ends_at->isFuture())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        Kadaluarsa
                                    </span>
                                @endif
                            @elseif($foundation->status == 'trial')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    Trial
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($foundation->status) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Paket</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $foundation->plan->name ?? 'Free' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Mulai</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $foundation->created_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Berakhir</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $foundation->subscription_ends_at ? $foundation->subscription_ends_at->format('d M Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Sisa Waktu</p>
                        <p class="mt-1 font-medium text-gray-900">
                            @if($foundation->subscription_ends_at && $foundation->subscription_ends_at->isFuture())
                                {{ $foundation->subscription_ends_at->diffInDays(now()) }} hari
                            @elseif($foundation->subscription_ends_at && $foundation->subscription_ends_at->isPast())
                                Kadaluarsa
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Harga Paket</p>
                        <p class="mt-1 font-medium text-gray-900">
                            @if($foundation->plan && $foundation->plan->price_per_month > 0)
                                Rp {{ number_format($foundation->plan->price_per_month, 0, ',', '.') }}/bulan
                            @else
                                Gratis
                            @endif
                        </p>
                    </div>
                </div>

                @if($foundation->subscription_ends_at && $foundation->subscription_ends_at->isFuture())
                    <div class="mt-4 p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-green-600 font-medium">Langganan aktif hingga {{ $foundation->subscription_ends_at->format('d M Y H:i') }}</p>
                        <p class="text-sm text-green-500 mt-1">Sisa waktu: {{ $foundation->subscription_ends_at->diffForHumans(now(), true) }}</p>
                    </div>
                @elseif($foundation->subscription_ends_at && $foundation->subscription_ends_at->isPast())
                    <div class="mt-4 p-4 bg-red-50 rounded-lg">
                        <p class="text-sm text-red-600 font-medium">Langganan telah kadaluarsa pada {{ $foundation->subscription_ends_at->format('d M Y H:i') }}</p>
                        <p class="text-sm text-red-500 mt-1">Kadaluarsa {{ $foundation->subscription_ends_at->diffForHumans() }}</p>
                    </div>
                @endif
            </div>

            <!-- Invoice History -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Riwayat Invoice</h3>
                <div class="space-y-3">
                    @forelse($invoices as $invoice)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="font-bold text-lg text-gray-900">Invoice #{{ $invoice->invoice_number }}</p>
                                    <p class="text-sm text-gray-500">Dibuat: {{ $invoice->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-xl text-gray-900">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                        @if($invoice->status == 'paid') bg-green-100 text-green-800
                                        @elseif($invoice->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($invoice->status == 'verifying') bg-blue-100 text-blue-800
                                        @elseif($invoice->status == 'rejected') bg-red-100 text-red-800
                                        @elseif($invoice->isOverdue()) bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        @if($invoice->status == 'paid') Lunas
                                        @elseif($invoice->status == 'pending') Menunggu Pembayaran
                                        @elseif($invoice->status == 'verifying') Menunggu Verifikasi
                                        @elseif($invoice->status == 'rejected') Ditolak
                                        @elseif($invoice->isOverdue()) Jatuh Tempo
                                        @else {{ ucfirst($invoice->status) }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Rincian Pembayaran -->
                            <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                <p class="font-semibold text-gray-700 mb-2">Rincian Pembayaran:</p>
                                <div class="space-y-1">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Paket Langganan:</span>
                                        <span class="font-medium text-gray-900">{{ $invoice->items['plan_name'] ?? '-' }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Jenis Pembayaran:</span>
                                        <span class="font-medium text-gray-900">{{ $invoice->billing_cycle == 'yearly' ? 'Tahunan' : 'Bulanan' }}</span>
                                    </div>
                                    @if(isset($invoice->items['price_per_month']) && $invoice->items['billing_cycle'] == 'monthly')
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Biaya per Bulan:</span>
                                        <span class="font-medium text-gray-900">Rp {{ number_format($invoice->items['price_per_month'], 0, ',', '.') }}</span>
                                    </div>
                                    @endif
                                    @if(isset($invoice->items['price_per_year']) && $invoice->items['billing_cycle'] == 'yearly')
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Biaya per Tahun:</span>
                                        <span class="font-medium text-gray-900">Rp {{ number_format($invoice->items['price_per_year'], 0, ',', '.') }}</span>
                                    </div>
                                    @endif
                                    <div class="flex justify-between text-sm border-t pt-1 mt-1">
                                        <span class="font-medium text-gray-900">Total:</span>
                                        <span class="font-bold text-gray-900">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Periode & Jatuh Tempo -->
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="text-gray-500">Periode Layanan</p>
                                    <p class="font-medium text-gray-900">
                                        {{ $invoice->period_start ? $invoice->period_start->format('d M Y') : '-' }} 
                                        s/d 
                                        {{ $invoice->period_end ? $invoice->period_end->format('d M Y') : '-' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Jatuh Tempo</p>
                                    <p class="font-medium {{ $invoice->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                                        {{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '-' }}
                                    </p>
                                </div>
                            </div>
                            
                             @if($invoice->status !== 'paid')
                            <div class="mt-4 pt-3 border-t flex flex-wrap gap-2">
                                @if($invoice->status == 'verifying')
                                <button onclick="showVerifyModal({{ $invoice->id }}, '{{ asset('storage/' . $invoice->payment_receipt) }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors text-sm font-bold animate-pulse">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Verifikasi Bukti
                                </button>
                                @endif
                                
                                <a href="{{ route('platform.invoices.payment-link', $invoice->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                    Link Bayar
                                </a>
                                @if($invoice->status == 'pending' || $invoice->status == 'rejected')
                                <form action="{{ route('platform.invoices.send-payment-link', $invoice->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        Kirim Link
                                    </button>
                                </form>
                                @endif
                                <a href="#" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    PDF
                                </a>
                            </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p>Belum ada riwayat invoice</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Invoice</h3>
                <div class="flex flex-wrap gap-4">
                    <button onclick="showGenerateModal({{ $foundation->id }})" class="px-6 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition-all shadow-lg shadow-green-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Buat Invoice Baru
                    </button>
                    <button onclick="showSendModal({{ $foundation->id }})" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Kirim Invoice Terakhir
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Invoice Statistics -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Statistik Invoice</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Invoice</span>
                        <span class="text-sm font-bold text-gray-900">{{ $invoiceStats['total_invoices'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Sudah Dibayar</span>
                        <span class="text-sm font-bold text-green-600">{{ $invoiceStats['paid_invoices'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Menunggu Pembayaran</span>
                        <span class="text-sm font-bold text-yellow-600">{{ $invoiceStats['pending_invoices'] }}</span>
                    </div>
                    @if(isset($invoiceStats['overdue_invoices']) && $invoiceStats['overdue_invoices'] > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Jatuh Tempo</span>
                        <span class="text-sm font-bold text-red-600">{{ $invoiceStats['overdue_invoices'] }}</span>
                    </div>
                    @endif
                    <div class="border-t pt-3 mt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Amount</span>
                            <span class="text-sm font-bold text-gray-900">Rp {{ number_format($invoiceStats['total_amount'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-sm text-gray-600">Sudah Dibayar</span>
                            <span class="text-sm font-bold text-green-600">Rp {{ number_format($invoiceStats['paid_amount'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-sm text-gray-600">Menunggu</span>
                            <span class="text-sm font-bold text-yellow-600">Rp {{ number_format($invoiceStats['pending_amount'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Invoice -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Invoice Berikutnya</h3>
                @if($foundation->subscription_ends_at && $foundation->subscription_ends_at->isFuture())
                    <div class="space-y-3">
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-600 font-medium">Akan jatuh tempo</p>
                            <p class="text-lg font-bold text-blue-900">{{ $foundation->subscription_ends_at->format('d M Y') }}</p>
                            <p class="text-sm text-blue-500">{{ $foundation->subscription_ends_at->diffInDays(now()) }}, hari lagi</p>
                        </div>
                        <div class="text-sm text-gray-600">
                            <p class="font-medium">Estimasi Amount:</p>
                            <p class="text-lg font-bold text-gray-900">
                                @if($foundation->plan && $foundation->plan->price_per_month > 0)
                                    Rp {{ number_format($foundation->plan->price_per_month, 0, ',', '.') }}
                                @else
                                    Gratis
                                @endif
                            </p>
                        </div>
                    </div>
                @else
                    <div class="p-4 bg-red-50 rounded-lg">
                        <p class="text-sm text-red-600 font-medium">Langganan telah kadaluarsa</p>
                        <p class="text-sm text-red-500">Silakan perbarui langganan untuk membuat invoice baru</p>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <button onclick="showGenerateModal({{ $foundation->id }})" class="w-full px-5 py-3.5 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition-all shadow-lg shadow-green-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Generate Invoice
                    </button>
                    <button onclick="showSendModal({{ $foundation->id }})" class="w-full px-5 py-3.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Kirim Email
                    </button>
                    <button class="w-full px-5 py-3.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download All
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Generate Modal -->
    <div id="generateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Buat Invoice</h3>
                <button onclick="closeGenerateModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="generateForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="POST">
                
                <!-- Plan Info -->
                <div class="p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-600 font-medium">Paket: {{ $foundation->plan->name ?? 'Tidak ada' }}</p>
                    <p class="text-lg font-bold text-blue-900">
                        @if($foundation->plan)
                            Bulanan: Rp {{ number_format($foundation->plan->price_per_month, 0, ',', '.') }} | 
                            Tahunan: Rp {{ number_format($foundation->plan->price_per_year ?? 0, 0, ',', '.') }}
                        @else
                            Gratis
                        @endif
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Siklus Pembayaran</label>
                    <select name="billing_cycle" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="monthly">Bulanan</option>
                        <option value="yearly">Tahunan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jatuh Tempo (hari)</label>
                    <select name="due_days" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="7">7 Hari</option>
                        <option value="14">14 Hari</option>
                        <option value="30" selected>30 Hari</option>
                        <option value="60">60 Hari</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (opsional)</label>
                    <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Catatan invoice..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeGenerateModal()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Buat Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Send Modal -->
    <div id="sendModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Kirim Invoice</h3>
                <button onclick="closeSendModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="sendForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="POST">
                
                @if($invoices->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Invoice</label>
                    <select name="invoice_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Invoice terbaru (pending)</option>
                        @foreach($invoices->where('status', 'pending') as $inv)
                            <option value="{{ $inv->id }}">#{{ $inv->invoice_number }} - Rp {{ number_format($inv->amount, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Tujuan</label>
                    <input type="email" name="email" value="{{ $foundation->email }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                    <textarea name="message" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Pesan email..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeSendModal()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Kirim Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showGenerateModal(foundationId) {
            document.getElementById('generateModal').classList.remove('hidden');
            document.getElementById('generateForm').action = `/platform/invoices/${foundationId}/generate`;
        }

        function closeGenerateModal() {
            document.getElementById('generateModal').classList.add('hidden');
            document.getElementById('generateForm').reset();
        }

        function showSendModal(foundationId) {
            document.getElementById('sendModal').classList.remove('hidden');
            document.getElementById('sendForm').action = `/platform/invoices/${foundationId}/send`;
        }

        function closeSendModal() {
            document.getElementById('sendModal').classList.add('hidden');
            document.getElementById('sendForm').reset();
        }

        function showVerifyModal(invoiceId, receiptUrl) {
            document.getElementById('verifyInvoiceId').value = invoiceId;
            document.getElementById('receiptPreview').src = receiptUrl;
            document.getElementById('downloadReceipt').href = receiptUrl;
            document.getElementById('verifyForm').action = `/platform/invoices/${invoiceId}/verify-payment`;
            document.getElementById('verifyModal').classList.remove('hidden');
        }

        function closeVerifyModal() {
            document.getElementById('verifyModal').classList.add('hidden');
        }
    </script>

    <!-- Verify Modal -->
    <div id="verifyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4 border-b pb-4">
                <h3 class="text-xl font-bold text-gray-900">Verifikasi Pembayaran</h3>
                <button onclick="closeVerifyModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-bold text-gray-700 mb-2">Preview Bukti Transfer</p>
                    <div class="border rounded-xl overflow-hidden bg-gray-100 flex items-center justify-center min-h-[300px]">
                        <img id="receiptPreview" src="" alt="Receipt Preview" class="max-w-full h-auto">
                    </div>
                    <div class="mt-4">
                        <a id="downloadReceipt" href="#" target="_blank" class="text-blue-600 hover:underline text-sm font-medium">Buka Gambar Penuh</a>
                    </div>
                </div>
                <div>
                    <form id="verifyForm" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" id="verifyInvoiceId" name="invoice_id">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Aksi Verifikasi</label>
                            <select name="action" required onchange="this.value === 'reject' ? document.getElementById('rejectNotes').classList.remove('hidden') : document.getElementById('rejectNotes').classList.add('hidden')" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-bold text-lg">
                                <option value="approve" class="text-green-600">Terima Pembayaran (Lunas)</option>
                                <option value="reject" class="text-red-600">Tolak Pembayaran (Bukti Tidak Valid)</option>
                            </select>
                        </div>

                        <div id="rejectNotes" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan / Catatan</label>
                            <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Bukti buram, Nominal tidak sesuai, dll..."></textarea>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl text-sm text-gray-600">
                            <p class="font-bold mb-1">Penting:</p>
                            <p>Memilih "Terima" akan mengubah status invoice menjadi **Lunas** dan memperpanjang masa aktif langganan yayasan secara otomatis.</p>
                        </div>

                        <div class="flex flex-col gap-3 pt-4">
                            <button type="submit" 
                                    class="w-full px-6 py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                                Proses Verifikasi
                            </button>
                            <button type="button" onclick="closeVerifyModal()" 
                                    class="w-full px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-platform-layout>
