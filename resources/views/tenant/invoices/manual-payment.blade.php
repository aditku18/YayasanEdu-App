@extends('layouts.tenant-platform')

@section('title', 'Instruksi Pembayaran Manual')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Breadcrumb --}}
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('tenant.invoice.index') }}" class="text-gray-700 hover:text-primary-600">
                    <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Invoice
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-gray-500">{{ $invoice->invoice_number }}</span>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-gray-500">Instruksi Pembayaran</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Instruksi Pembayaran</h1>
                <p class="text-gray-600 mt-1">Selesaikan pembayaran untuk invoice #{{ $invoice->invoice_number }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('tenant.invoice.pay', $invoice->id) }}" class="px-4 py-2 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Invoice Summary --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500 mb-2">Invoice</p>
                    <h2 class="text-xl font-bold text-gray-900">#{{ $invoice->invoice_number }}</h2>
                    <p class="text-gray-600 mt-1">Reference: {{ $reference }}</p>
                </div>
                <div class="text-end">
                    <p class="text-sm text-gray-500 mb-2">Total Pembayaran</p>
                    <p class="text-2xl font-bold text-primary-600">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Bank Details --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Detail Transfer Bank</h3>
        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-2">Bank</p>
                    <p class="font-bold text-lg text-gray-900">{{ $gateway->getConfigValue('bank_name') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-2">Nomor Rekening</p>
                    <p class="font-bold text-lg text-gray-900 font-mono">{{ $gateway->getConfigValue('account_number') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-2">Atas Nama</p>
                    <p class="font-bold text-lg text-gray-900">{{ $gateway->getConfigValue('account_name') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-2">Cabang</p>
                    <p class="font-bold text-lg text-gray-900">{{ $gateway->getConfigValue('bank_branch') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Steps --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Cara Pembayaran</h3>
        <div class="space-y-4">
            <div class="flex gap-4">
                <div class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold text-sm">1</div>
                <div>
                    <p class="font-medium text-gray-900">Login ke mobile banking atau internet banking Anda</p>
                    <p class="text-gray-600">Buka aplikasi mobile banking atau akses internet banking dari bank Anda</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold text-sm">2</div>
                <div>
                    <p class="font-medium text-gray-900">Pilih menu Transfer</p>
                    <p class="text-gray-600">Pilih menu transfer antar bank atau transfer ke rekening lain</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold text-sm">3</div>
                <div>
                    <p class="font-medium text-gray-900">Masukkan detail rekening tujuan</p>
                    <p class="text-gray-600">Masukkan nomor rekening {{ $gateway->getConfigValue('account_number') }} atas nama {{ $gateway->getConfigValue('account_name') }}</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold text-sm">4</div>
                <div>
                    <p class="font-medium text-gray-900">Masukkan jumlah pembayaran</p>
                    <p class="text-gray-600">Transfer sejumlah <strong>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</strong></p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold text-sm">5</div>
                <div>
                    <p class="font-medium text-gray-900">Tambahkan Catatan Transfer</p>
                    <p class="text-gray-600">Cantumkan nomor invoice <strong>#{{ $invoice->invoice_number }}</strong> pada kolom berita/catatan transfer Anda</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold text-sm">6</div>
                <div>
                    <p class="font-medium text-gray-900">Konfirmasi dan selesaikan transfer</p>
                    <p class="text-gray-600">Periksa kembali detail transfer dan konfirmasi pembayaran</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Important Notes --}}
    <div class="bg-yellow-50 rounded-lg border border-yellow-200 p-6 mb-6">
        <div class="flex gap-4">
            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h4 class="font-medium text-yellow-800 mb-2">Penting:</h4>
                <ul class="text-yellow-700 space-y-1 text-sm">
                    <li>• Pastikan jumlah transfer sesuai dengan total tagihan</li>
                    <li>• Cantumkan nomor invoice <strong>#{{ $invoice->invoice_number }}</strong> di berita transfer</li>
                    <li>• Simpan bukti transfer untuk konfirmasi pembayaran</li>
                    <li>• Pembayaran akan diproses dalam 1x24 jam setelah konfirmasi</li>
                    <li>• Hubungi admin jika pembayaran tidak terkonfirmasi dalam 24 jam</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Receipt Upload Form --}}
    @if($invoice->status !== 'paid' && $invoice->status !== 'verifying')
    <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden mb-10">
        <div class="p-8 border-b border-slate-100 bg-emerald-50/30">
            <h3 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                </div>
                Unggah Bukti Pembayaran
            </h3>
        </div>
        <div class="p-8">
            <form action="{{ route('tenant.invoice.upload-receipt', $invoice->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Pilih Foto/File Bukti Transfer</label>
                    <div class="relative group">
                        <input type="file" name="payment_receipt" id="payment_receipt" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required accept="image/*,.pdf">
                        <div class="border-2 border-dashed border-slate-200 rounded-3xl p-10 text-center group-hover:border-primary-400 group-hover:bg-primary-50/30 transition-all duration-300">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-slate-400 group-hover:text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <p class="text-slate-600 font-bold mb-1">Klik atau seret file ke sini</p>
                            <p class="text-slate-400 text-sm">JPG, PNG atau PDF (Maks. 2MB)</p>
                            <div id="file-name" class="mt-4 text-primary-600 font-bold hidden"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-amber-50 rounded-2xl p-4 border border-amber-100 flex gap-4 items-start">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <p class="text-xs text-amber-700 leading-relaxed font-medium">
                        Mohon pastikan bukti transfer terlihat jelas terutama pada bagian **tanggal, jumlah transfer, dan rekening tujuan**. Proses verifikasi membutuhkan waktu maksimal 1x24 jam.
                    </p>
                </div>

                <button type="submit" class="w-full bg-slate-900 text-white p-5 rounded-3xl font-black text-xl shadow-xl shadow-slate-200 hover:shadow-2xl hover:shadow-slate-300 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                    Kirim Bukti Pembayaran
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7-7 7"/></svg>
                </button>
            </form>
        </div>
    </div>
    @elseif($invoice->status === 'verifying')
    <div class="bg-white rounded-[2.5rem] border border-amber-100 premium-shadow overflow-hidden mb-10 text-center p-12">
        <div class="w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-amber-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="text-2xl font-black text-slate-900 mb-2">Pembayaran Sedang Diverifikasi</h3>
        <p class="text-slate-500 max-w-sm mx-auto mb-8">Bukti pembayaran Anda telah kami terima. Admin sedang melakukan pengecekan mutasi bank. Status akan berubah otomatis menjadi **Lunas** setelah diverifikasi.</p>
        <a href="{{ route('tenant.invoice.show', $invoice->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-2xl hover:bg-slate-200 transition-all">
            Kembali ke Detail
        </a>
    </div>
    @endif

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row gap-4">
        <button onclick="window.print()" class="flex-1 px-8 py-4 bg-slate-100 text-slate-700 font-bold rounded-2xl hover:bg-slate-200 transition-all flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Instruksi
        </button>
        <a href="{{ route('tenant.invoice.show', $invoice->id) }}" class="flex-1 px-8 py-4 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-primary-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Selesai
        </a>
    </div>

    <script>
        document.getElementById('payment_receipt').addEventListener('change', function(e) {
            const fileName = e.target.files[0].name;
            const el = document.getElementById('file-name');
            el.textContent = 'Terpilih: ' + fileName;
            el.classList.remove('hidden');
        });
    </script>
</div>
@endsection
