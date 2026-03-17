@extends('layouts.dashboard')

@section('title', 'Detail Tagihan')

@section('content')
<div class="space-y-6 pb-12">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('tenant.school.finance.invoices.index') }}" class="p-2 hover:bg-slate-100 rounded-xl transition-colors">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Tagihan {{ $invoice->invoice_number }}</h1>
                <p class="text-slate-500">Detail tagihan siswa</p>
            </div>
        </div>
        <div class="flex gap-2">
            @if($invoice->status !== 'paid')
                <a href="{{ route('tenant.school.finance.payments.create', ['invoice_id' => $invoice->id]) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-medium hover:bg-emerald-700 transition-colors">
                    Catat Pembayaran
                </a>
            @endif
            <a href="{{ route('tenant.school.finance.invoices.edit', $invoice->id) }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-50 transition-colors">
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Invoice Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="font-semibold text-slate-900 mb-4">Informasi Tagihan</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-slate-500">Nomor Tagihan</p>
                        <p class="font-medium text-slate-900">{{ $invoice->invoice_number }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">Status</p>
                        <span class="px-2 py-1 text-xs font-medium rounded-lg
                            @if($invoice->status === 'paid') bg-emerald-100 text-emerald-700
                            @elseif($invoice->status === 'partial') bg-blue-100 text-blue-700
                            @elseif($invoice->status === 'overdue') bg-red-100 text-red-700
                            @else bg-amber-100 text-amber-700 @endif">
                            @if($invoice->status === 'unpaid') Belum Bayar
                            @elseif($invoice->status === 'partial') Dibayar Sebagian
                            @elseif($invoice->status === 'paid') Lunas
                            @elseif($invoice->status === 'overdue') Jatuh Tempo
                            @else {{ ucfirst($invoice->status) }}
                            @endif
                        </span>
                    </div>
                    <div>
                        <p class="text-slate-500">Jenis Tagihan</p>
                        <p class="font-medium text-slate-900">{{ $invoice->billType->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">Bulan</p>
                        <p class="font-medium text-slate-900">{{ $invoice->month ? Carbon\Carbon::parse($invoice->month)->format('F Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">Tanggal Jatuh Tempo</p>
                        <p class="font-medium text-slate-900">{{ $invoice->due_date ? Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">Tahun Ajaran</p>
                        <p class="font-medium text-slate-900">{{ $invoice->academicYear->name ?? '-' }}</p>
                    </div>
                </div>
                
                @if($invoice->description)
                <div class="mt-4 pt-4 border-t">
                    <p class="text-slate-500 text-sm">Keterangan</p>
                    <p class="text-slate-900">{{ $invoice->description }}</p>
                </div>
                @endif
            </div>

            <!-- Student Info -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="font-semibold text-slate-900 mb-4">Informasi Siswa</h3>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                        <span class="text-lg font-bold text-primary-600">{{ substr($invoice->student->name ?? 'N/A', 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">{{ $invoice->student->name ?? 'N/A' }}</p>
                        <p class="text-sm text-slate-500">NIS: {{ $invoice->student->nis ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="font-semibold text-slate-900 mb-4">Riwayat Pembayaran</h3>
                @if($invoice->payments->count() > 0)
                    <div class="space-y-3">
                        @foreach($invoice->payments as $payment)
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $payment->payment_number }}</p>
                                        <p class="text-xs text-slate-500">{{ $payment->payment_date->format('d/m/Y') }} - {{ $payment->payment_method }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-slate-900">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
                                    <span class="text-xs text-emerald-600">Confirmed</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-slate-400 py-8">Belum ada pembayaran</p>
                @endif
            </div>
        </div>

        <!-- Summary -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="font-semibold text-slate-900 mb-4">Ringkasan</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Jumlah Tagihan</span>
                        <span class="font-medium">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Diskon</span>
                        <span class="font-medium text-red-600">- Rp {{ number_format($invoice->discount, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between">
                        <span class="font-semibold">Total Tagihan</span>
                        <span class="font-bold">Rp {{ number_format($invoice->final_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Sudah Dibayar</span>
                        <span class="font-medium text-emerald-600">Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between">
                        <span class="font-semibold">Sisa Tagihan</span>
                        <span class="font-bold text-red-600">Rp {{ number_format($invoice->remaining_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-amber-50 rounded-2xl p-6 border border-amber-100">
                <h3 class="font-semibold text-amber-900 mb-2">Aksi Cepat</h3>
                <div class="space-y-2">
                    @if($invoice->remaining_amount > 0)
                        <a href="{{ route('tenant.school.finance.payments.create', ['invoice_id' => $invoice->id]) }}" class="block w-full px-4 py-2 bg-amber-600 text-white text-center rounded-xl text-sm font-medium hover:bg-amber-700 transition-colors">
                            Catat Pembayaran
                        </a>
                    @endif
                    <form action="{{ route('tenant.school.finance.invoices.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tagihan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block w-full px-4 py-2 border border-red-200 text-red-600 text-center rounded-xl text-sm font-medium hover:bg-red-50 transition-colors">
                            Hapus Tagihan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
