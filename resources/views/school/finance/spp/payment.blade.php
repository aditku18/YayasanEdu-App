@extends('layouts.dashboard')

@section('title', 'Pembayaran SPP')

@section('content')
<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Pembayaran SPP</h1>
            <p class="text-slate-500">Catat pembayaran SPP siswa</p>
        </div>
    </div>

    <!-- Student Selection -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <form method="GET" class="flex gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Siswa</label>
                <select name="student_id" onchange="this.form.submit()" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }} - {{ $student->classRoom->name ?? 'Belum Kelas' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    @if($selectedStudent)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Student Info -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-semibold text-slate-900 mb-4">Informasi Siswa</h3>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-2xl font-bold text-primary-600">{{ substr($selectedStudent->name, 0, 1) }}</span>
                </div>
                <div>
                    <p class="font-semibold text-slate-900">{{ $selectedStudent->name }}</p>
                    <p class="text-sm text-slate-500">NIS: {{ $selectedStudent->nis ?? '-' }}</p>
                    <p class="text-sm text-slate-500">{{ $selectedStudent->classRoom->name ?? 'Belum Kelas' }}</p>
                </div>
            </div>
            
            <!-- Summary -->
            <div class="space-y-2 text-sm">
                <div class="flex justify-between p-2 bg-slate-50 rounded-lg">
                    <span class="text-slate-500">Total Tagihan Belum Lunas</span>
                    <span class="font-semibold text-red-600">Rp {{ number_format($unpaidInvoices->sum('remaining_amount'), 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between p-2 bg-slate-50 rounded-lg">
                    <span class="text-slate-500">Jumlah Tagihan</span>
                    <span class="font-semibold">{{ $unpaidInvoices->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Unpaid Invoices -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-semibold text-slate-900 mb-4">Tagihan Belum Lunas</h3>
            
            @if($unpaidInvoices->count() > 0)
                <form action="{{ route('tenant.school.finance.spp.process') }}" method="POST" id="paymentForm">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}">
                    
                    <div class="space-y-3 mb-6">
                        @foreach($unpaidInvoices as $invoice)
                            <label class="flex items-center justify-between p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="invoice_ids[]" value="{{ $invoice->id }}" checked class="w-4 h-4 text-primary-600 rounded border-slate-300 focus:ring-primary-500">
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $invoice->billType->name }}</p>
                                        <p class="text-xs text-slate-500">{{ Carbon\Carbon::parse($invoice->month)->format('F Y') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-slate-900">Rp {{ number_format($invoice->remaining_amount, 0, ',', '.') }}</p>
                                    <p class="text-xs text-red-500">Jatuh tempo: {{ $invoice->due_date ? Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') : '-' }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="border-t pt-4 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="font-semibold text-slate-900">Total Pembayaran</span>
                            <span class="text-2xl font-bold text-primary-600" id="totalAmount">Rp {{ number_format($unpaidInvoices->sum('remaining_amount'), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Pembayaran <span class="text-red-500">*</span></label>
                            <input type="date" name="payment_date" value="{{ now()->format('Y-m-d') }}" required class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                            <select name="payment_method" required class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <option value="cash">Tunai</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="w-full px-6 py-3 bg-primary-600 text-white rounded-xl text-sm font-medium hover:bg-primary-700 transition-colors">
                        Simpan Pembayaran
                    </button>
                </form>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-slate-600 font-medium">Semua tagihan SPP sudah lunas!</p>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    const checkboxes = document.querySelectorAll('input[name="invoice_ids[]"]');
    const totalAmountEl = document.getElementById('totalAmount');

    function updateTotal() {
        let total = 0;
        checkboxes.forEach(cb => {
            if (cb.checked) {
                const parent = cb.closest('label');
                const amountText = parent.querySelector('.font-semibold.text-slate-900').textContent;
                const amount = parseFloat(amountText.replace(/[Rp.,]/g, '')) || 0;
                total += amount;
            }
        });
        totalAmountEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateTotal));
</script>
@endpush
@endsection
