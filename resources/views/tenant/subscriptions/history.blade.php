@extends('layouts.tenant-platform')

@section('title', 'Riwayat Berlangganan')

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
    .animate-slide-in-left {
        animation: slideInLeft 0.8s ease-out forwards;
        opacity: 0;
    }
    .animate-pulse-slow {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    .animate-delay-1 { animation-delay: 0.1s; }
    .animate-delay-2 { animation-delay: 0.2s; }
    .animate-delay-3 { animation-delay: 0.3s; }
    .glass-effect {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
    }
    .status-active {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    .status-cancelled {
        background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
        color: white;
    }
    .status-expired {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
    }
    .status-other {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto mb-12">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary-600 via-primary-500 to-indigo-500 p-8 md:p-12 text-white animate-fade-in-up shadow-2xl shadow-primary-500/20">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2 animate-pulse-slow" style="animation-delay: 1s;"></div>
            
            <div class="relative z-10 text-center">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">Riwayat Berlangganan</h1>
                </div>
                <p class="text-primary-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Daftar lengkap riwayat pembayaran dan status paket berlangganan yayasan Anda
                </p>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $subscriptions->count() }}</p>
                        <p class="text-primary-100 text-sm">Total Transaksi</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $subscriptions->where('status', 'active')->count() }}</p>
                        <p class="text-primary-100 text-sm">Aktif</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $subscriptions->where('status', 'expired')->count() }}</p>
                        <p class="text-primary-100 text-sm">Kadaluarsa</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $subscriptions->where('status', 'cancelled')->count() }}</p>
                        <p class="text-primary-100 text-sm">Dibatalkan</p>
                    </div>
                </div>
                
                <!-- Back Button -->
                <div class="flex justify-center mt-8">
                    <a href="{{ route('tenant.subscription.current') }}" class="group px-8 py-4 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white font-bold rounded-2xl transition-all duration-300 border border-white/30">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Paket Aktif
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- History Table Section -->
    <div class="max-w-7xl mx-auto">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">Riwayat Transaksi</h2>
                            <p class="text-slate-600">Daftar lengkap semua transaksi berlangganan yayasan</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-black text-primary-600">{{ $subscriptions->count() }}</p>
                        <p class="text-sm text-slate-500 font-medium">Total Transaksi</p>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-slate-50 to-white border-b border-slate-100">
                            <th class="px-8 py-6 text-left">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tanggal</span>
                            </th>
                            <th class="px-8 py-6 text-left">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Paket Layanan</span>
                            </th>
                            <th class="px-8 py-6 text-left">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Periode</span>
                            </th>
                            <th class="px-8 py-6 text-left">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Nominal</span>
                            </th>
                            <th class="px-8 py-6 text-left">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Status</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($subscriptions as $subscription)
                        <tr class="hover:bg-slate-50/50 transition-all duration-200 hover-lift">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-slate-900 font-bold">{{ $subscription->created_at->format('d M Y') }}</p>
                                        <p class="text-slate-500 text-xs">{{ $subscription->created_at->format('H:i') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-slate-900 font-bold">{{ $subscription->plan->name ?? 'Paket EduSaaS' }}</p>
                                        <p class="text-slate-500 text-xs">ID: {{ $subscription->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <div>
                                        <p class="text-slate-700 font-medium text-sm">
                                            {{ $subscription->starts_at ? $subscription->starts_at->format('d M Y') : '-' }} s/d 
                                            {{ $subscription->ends_at ? $subscription->ends_at->format('d M Y') : 'Selamanya' }}
                                        </p>
                                        <p class="text-slate-500 text-xs">Periode berlangganan</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-slate-900 font-black text-lg">Rp{{ number_format($subscription->price, 0, ',', '.') }}</p>
                                        <p class="text-slate-500 text-xs">{{ $subscription->currency ?? 'IDR' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @if($subscription->status === 'active')
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                    <span class="px-4 py-2 status-active text-xs font-bold rounded-full uppercase tracking-wider shadow-lg">
                                        Aktif
                                    </span>
                                </div>
                                @elseif($subscription->status === 'cancelled')
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-rose-500 rounded-full"></div>
                                    <span class="px-4 py-2 status-cancelled text-xs font-bold rounded-full uppercase tracking-wider shadow-lg">
                                        Dibatalkan
                                    </span>
                                </div>
                                @elseif($subscription->status === 'expired')
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-slate-400 rounded-full"></div>
                                    <span class="px-4 py-2 status-expired text-xs font-bold rounded-full uppercase tracking-wider shadow-lg">
                                        Kadaluarsa
                                    </span>
                                </div>
                                @else
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></div>
                                    <span class="px-4 py-2 status-other text-xs font-bold rounded-full uppercase tracking-wider shadow-lg">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20">
                                <div class="text-center">
                                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-8">
                                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Belum Ada Riwayat</h3>
                                    <p class="text-slate-600 max-w-md mx-auto mb-8">Anda belum memiliki riwayat transaksi berlangganan saat ini. Semua transaksi akan muncul di sini.</p>
                                    <div class="flex items-center justify-center gap-4">
                                        <a href="{{ route('tenant.subscription.current') }}" class="group px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                                </svg>
                                                Lihat Paket Aktif
                                            </span>
                                        </a>
                                        <a href="{{ route('tenant.plan.upgrade') }}" class="group px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all duration-300">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                                </svg>
                                                Upgrade Paket
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
