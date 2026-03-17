@extends('layouts.tenant-platform')

@section('title', 'Pembayaran Invoice')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header Section --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pembayaran</h1>
            <p class="text-slate-500 mt-1">Selesaikan pembayaran invoice Anda dengan aman.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('tenant.invoice.show', $invoice->id) }}" class="px-6 py-3 bg-white text-slate-700 font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Detail
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Payment Methods (Main Column) --}}
        <div class="lg:col-span-2 space-y-6">
            @if($invoice->status !== 'paid' && $invoice->status !== 'completed')
            <div class="bg-white rounded-[2.5rem] border border-slate-100 premium-shadow overflow-hidden">
                <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        Pilih Metode Pembayaran
                    </h3>
                </div>

                <div class="p-8">
                    @if($paymentGateways->count() > 0)
                        <form action="{{ route('tenant.invoice.process-payment', $invoice->id) }}" method="POST" id="payment-form">
                        @csrf
                        <input type="hidden" name="payment_method" value="online">
                        
                        <div class="space-y-4 mb-8">
                            @foreach($paymentGateways as $gateway)
                            <label class="relative block cursor-pointer group">
                                <input type="radio" name="payment_gateway_id" value="{{ $gateway->id }}" class="sr-only peer" required>
                                <div class="p-5 border-2 border-slate-100 rounded-3xl transition-all duration-300 peer-checked:border-primary-500 peer-checked:bg-primary-50/30 group-hover:bg-slate-50 peer-checked:ring-4 peer-checked:ring-primary-50">
                                    <div class="flex items-center gap-5">
                                        <div class="w-14 h-14 bg-white rounded-2xl border border-slate-100 flex items-center justify-center p-2 shadow-sm group-hover:scale-110 transition-transform">
                                            @if($gateway->logo)
                                                <img src="{{ asset('storage/' . $gateway->logo) }}" alt="{{ $gateway->name }}" class="max-w-full max-h-full object-contain">
                                            @else
                                                <div class="w-8 h-8 text-slate-300">
                                                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-lg font-bold text-slate-900">{{ $gateway->display_name ?? $gateway->name }}</h4>
                                            <p class="text-sm text-slate-500">{{ $gateway->description ?? 'Proses pembayaran instan dan aman.' }}</p>
                                            
                                            @if($gateway->fee_percentage > 0 || $gateway->fee_fixed > 0)
                                                <div class="mt-2 flex items-center gap-2">
                                                    <span class="px-2 py-0.5 rounded-lg bg-slate-100 text-[10px] font-bold text-slate-500 border border-slate-200 uppercase tracking-tighter">
                                                        Biaya Layanan
                                                    </span>
                                                    <p class="text-xs text-slate-400 font-medium">
                                                        @if($gateway->fee_percentage > 0) {{ $gateway->fee_percentage }}% @endif
                                                        @if($gateway->fee_fixed > 0) + Rp{{ number_format($gateway->fee_fixed, 0, ',', '.') }} @endif
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="w-6 h-6 rounded-full border-2 border-slate-200 flex items-center justify-center peer-checked:border-primary-500 transition-colors">
                                            <div class="w-3 h-3 bg-primary-500 rounded-full scale-0 peer-checked:scale-100 transition-transform"></div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Custom Selection Overlay for Peer --}}
                                <div class="absolute inset-0 rounded-3xl pointer-events-none border-2 border-transparent peer-checked:border-primary-500"></div>
                            </label>
                            @endforeach
                        </div>

                        <button type="submit" 
                                id="payment-submit-btn"
                                class="w-full relative group overflow-hidden bg-slate-900 text-white p-5 rounded-3xl font-black text-xl shadow-xl shadow-slate-200 hover:shadow-2xl hover:shadow-slate-300 hover:-translate-y-1 transition-all duration-300">
                            <span class="relative z-10 flex items-center justify-center gap-3">
                                <span class="button-text">Bayar Sekarang</span>
                                <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7-7 7"/></svg>
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-primary-700 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </button>
                            
                            <p class="text-center text-slate-400 text-sm mt-6 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.9L9.03 17.003c.457.777 1.474.777 1.93 0l6.87-12.103A1.125 1.125 0 0016.86 3.25H3.14a1.125 1.125 0 00-.974 1.65zm9.008 6.908a1 1 0 01-1.353-.36l-1.03-1.83a1 1 0 111.758-.99l1.03 1.83a1 1 0 01-.36 1.353l-.045.027z" clip-rule="evenodd"/></svg>
                                Pembayaran diproses secara aman dengan enkripsi SSL 256-bit.
                            </p>
                        </form>
                    @else
                        {{-- No Gateways State --}}
                        <div class="text-center py-10 px-6">
                            <div class="w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <h4 class="text-xl font-bold text-slate-900 mb-2">Metode Pembayaran Tidak Tersedia</h4>
                            <p class="text-slate-500 max-w-sm mx-auto mb-8">Saat ini belum ada sistem pembayaran yang aktif. Silakan hubungi admin di <strong>support@edusaas.com</strong>.</p>
                            <a href="{{ route('tenant.invoice.show', $invoice->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-2xl hover:bg-slate-200 transition-all">
                                Kembali ke Detail
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @else
            {{-- Paid State --}}
            <div class="bg-white rounded-[2.5rem] border border-emerald-100 premium-shadow overflow-hidden text-center p-12">
                <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-8 animate-bounce">
                    <svg class="w-12 h-12 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-900 mb-4">Invoice Telah Lunas!</h3>
                <p class="text-slate-500 mb-8 max-w-md mx-auto">Terima kasih atas pembayaran Anda. Invoice ini telah dibayar pada <span class="font-bold text-slate-900">{{ $invoice->paid_at ? $invoice->paid_at->format('d M Y H:i') : '-' }}</span>.</p>
                
                @if(isset($invoice->items) && json_decode($invoice->items, true)['type'] === 'plugin_purchase')
                    <div class="mb-8 p-6 bg-emerald-50 rounded-2xl border border-emerald-200">
                        <div class="flex items-center justify-center gap-3 mb-4">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-emerald-800 font-bold text-lg">Plugin Berhasil Diinstall!</span>
                        </div>
                        <p class="text-emerald-700 text-sm">Plugin yang Anda beli telah diinstall dan siap digunakan.</p>
                    </div>
                @endif
                
                <div class="flex items-center justify-center gap-4">
                    <a href="{{ route('tenant.invoice.show', $invoice->id) }}" class="px-8 py-4 bg-primary-600 text-white font-bold rounded-2xl shadow-lg shadow-primary-200 hover:bg-primary-700 transition-all">
                        Lihat Invoice
                    </a>
                    @if(isset($invoice->items) && json_decode($invoice->items, true)['type'] === 'plugin_purchase')
                        <a href="{{ route('tenant.plugins.index') }}" class="px-8 py-4 bg-emerald-600 text-white font-bold rounded-2xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all">
                            Lihat Plugin
                        </a>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Summary (Sidebar) --}}
        <div class="space-y-6">
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                
                <h3 class="text-lg font-bold text-white/60 uppercase tracking-widest mb-6 relative">Ringkasan Tagihan</h3>
                
                <div class="space-y-4 mb-8 relative">
                    <div class="flex justify-between">
                        <span class="text-white/60">Nomor</span>
                        <span class="font-bold">#{{ $invoice->invoice_number }}</span>
                    </div>
                    <div class="flex justify-between border-t border-white/10 pt-4">
                        <span class="text-white/60">Jatuh Tempo</span>
                        <span class="font-bold {{ $invoice->due_date && $invoice->due_date->isPast() && $invoice->status !== 'paid' ? 'text-red-400' : 'text-white' }}">
                            {{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '-' }}
                        </span>
                    </div>
                </div>

                <div class="bg-white/10 rounded-3xl p-6 mb-4 backdrop-blur-sm border border-white/10">
                    <p class="text-xs font-bold text-white/50 uppercase tracking-widest mb-2">Total Yang Harus Dibayar</p>
                    <p class="text-3xl font-black">Rp{{ number_format($invoice->amount, 0, ',', '.') }}</p>
                </div>

                <div class="flex items-center gap-3 px-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                    <p class="text-[10px] text-white/40 uppercase font-black tracking-widest tracking-tighter">Secure Transaction</p>
                </div>
            </div>

            {{-- Support Card --}}
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 premium-shadow">
                <h4 class="font-bold text-slate-900 mb-4">Butuh Bantuan?</h4>
                <div class="space-y-4">
                    <a href="mailto:support@edusaas.com" class="flex items-center gap-4 group">
                        <div class="w-10 h-10 bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 rounded-xl flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-slate-600 group-hover:text-slate-900 transition-colors">Hubungi Support</span>
                    </a>
                    <a href="#" class="flex items-center gap-4 group">
                        <div class="w-10 h-10 bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 rounded-xl flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-slate-600 group-hover:text-slate-900 transition-colors">Pusat Bantuan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .premium-shadow {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.03);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('payment-form');
    const submitBtn = document.getElementById('payment-submit-btn');
    const buttonText = submitBtn.querySelector('.button-text');
    
    if (paymentForm && submitBtn) {
        paymentForm.addEventListener('submit', function(e) {
            // Check if payment gateway is selected
            const selectedGateway = document.querySelector('input[name="payment_gateway_id"]:checked');
            
            if (!selectedGateway) {
                e.preventDefault();
                alert('Silakan pilih metode pembayaran terlebih dahulu.');
                return false;
            }
            
            // Disable button and show loading
            submitBtn.disabled = true;
            buttonText.textContent = 'Memproses...';
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            
            console.log('Payment submitted:', {
                gatewayId: selectedGateway.value,
                invoiceId: '{{ $invoice->id }}'
            });
        });
        
        // Add visual feedback for gateway selection
        const gatewayInputs = document.querySelectorAll('input[name="payment_gateway_id"]');
        gatewayInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.checked) {
                    console.log('Gateway selected:', this.value);
                    submitBtn.classList.remove('opacity-50');
                    submitBtn.disabled = false;
                }
            });
        });
    }
});
</script>
@endsection
