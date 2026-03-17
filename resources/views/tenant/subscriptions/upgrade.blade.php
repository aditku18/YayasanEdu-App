@extends('layouts.tenant-platform')

@section('title', 'Upgrade Paket Langganan')

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
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
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
    .animate-float {
        animation: float 6s ease-in-out infinite;
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
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .billing-toggle-active {
        background: linear-gradient(135deg, rgb(59 130 246) 0%, rgb(37 99 235) 100%);
        color: white;
        transform: scale(1.05);
    }
    .billing-toggle-inactive {
        background: white;
        color: rgb(148 163 184);
    }
    .price-monthly { display: none; }
    .price-yearly { display: block; }
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
            <div class="absolute top-1/2 right-1/4 w-32 h-32 bg-indigo-300/20 rounded-full blur-xl animate-pulse-slow" style="animation-delay: 2s;"></div>
            
            <div class="relative z-10 text-center">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">Upgrade Paket Langganan</h1>
                </div>
                <p class="text-primary-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Pilih paket yang sesuai dengan kebutuhan operasional yayasan dan skala pendidikan Anda. Nikmati fitur lengkap dengan harga terbaik.
                </p>
                
                <!-- Current Plan Info -->
                @if($subscription)
                <div class="inline-flex items-center gap-3 px-6 py-3 bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20">
                    <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-left">
                        <p class="text-sm text-emerald-100">Paket Saat Ini</p>
                        <p class="font-bold text-white">{{ $subscription->plan->name ?? 'Basic' }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Billing Toggle -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="glass-effect rounded-2xl shadow-xl p-2 animate-fade-in-up animate-delay-1">
            <div class="flex bg-slate-100 rounded-xl p-1">
                <button id="monthly-btn" onclick="setBillingPeriod('monthly')" class="group flex-1 px-6 py-3 rounded-lg font-bold transition-all duration-300 shadow-sm hover:shadow-md relative overflow-hidden billing-toggle-active">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h9"/>
                        </svg>
                        <span>Bulanan</span>
                    </span>
                </button>
                <button id="yearly-btn" onclick="setBillingPeriod('yearly')" class="group flex-1 px-6 py-3 rounded-lg font-medium transition-all duration-300 relative overflow-hidden billing-toggle-inactive">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        Tahunan 
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] rounded-full uppercase font-bold">Hemat 20%</span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Pricing Display -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($plans as $plan)
            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden hover-lift animate-slide-in-left {{ $loop->iteration % 3 === 0 ? 'animate-delay-1' : ($loop->iteration % 3 === 1 ? 'animate-delay-2' : 'animate-delay-3') }}">
                @if($plan->is_featured)
                <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-4 text-center">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.421.3-.897 0-1.399l.953-3.432a1 1 0 00-.01-1.004l-1.916 4.18a1 1 0 00-.95.69l-3.208-3.632a1 1 0 00-.726-.3l-4.53 2.903c-.438.292-.726.292-1.164V8a1 1 0 00-1-1H4a1 1 0 00-1 1v4.723c0 .438.292.726.292 1.164l4.53 2.902a1 1 0 00.726.3l3.208 3.632a1 1 0 00.95.69l1.916-4.18a1 1 0 00.01-1.004zM15.97 15.5c0 .321-.1.633-.292-.897l-3.416 2.896a1 1 0 00-.887-.498l-3.416-2.896a1 1 0 00-.887.498L5.649 19.4a1 1 0 00-.887-.498L2.164 16.1a1 1 0 00-.887.498L.292 15.603a1 1 0 01.292-.897V8a1 1 0 011-1h4.058a1 1 0 01.996.916l1.916 4.18a1 1 0 00.01 1.004L3.416-2.896a1 1 0 00.887-.498l3.416 2.896a1 1 0 00.887.498l2.896 3.416a1 1 0 01-.292.897z"/>
                        </svg>
                        <span class="text-xs font-bold text-white uppercase tracking-wider">Paling Populer</span>
                    </div>
                </div>
                @endif
                
                <div class="p-8">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">{{ $plan->name }}</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">{{ $plan->description }}</p>
                    </div>
                    
                    <div class="text-center mb-8">
                        <div class="flex items-baseline justify-center gap-1">
                            <span class="price-monthly text-4xl font-black text-slate-900">Rp{{ number_format($plan->price_per_month, 0, ',', '.') }}</span>
                            <span class="price-yearly text-4xl font-black text-slate-900">Rp{{ number_format($plan->price_per_year ?? $plan->price_per_month * 12, 0, ',', '.') }}</span>
                            <span class="text-slate-400 font-medium">/<span class="billing-period">bln</span></span>
                        </div>
                    </div>

                    <ul class="space-y-4 mb-8">
                        @if(is_array($plan->features))
                            @foreach($plan->features as $feature)
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-slate-700 text-sm font-medium">{{ $feature }}</span>
                            </li>
                            @endforeach
                        @else
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-slate-700 text-sm font-medium">Hingga {{ $plan->max_schools ?? 'Tidak Terbatas' }} Sekolah</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-slate-700 text-sm font-medium">Hingga {{ $plan->max_students ?? 'Tidak Terbatas' }} Siswa</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-slate-700 text-sm font-medium">Semua Fitur Premium</span>
                            </li>
                        @endif
                    </ul>
                    
                    <button class="group w-full py-4 rounded-2xl font-bold transition-all duration-300 relative overflow-hidden {{ $plan->is_featured ? 'bg-gradient-to-r from-primary-500 to-primary-600 text-white shadow-lg hover:shadow-xl hover:-translate-y-1' : 'bg-gradient-to-br from-slate-50 to-slate-100 text-slate-700 border-2 border-slate-200 hover:bg-slate-100 hover:border-slate-300 hover:shadow-md' }}">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            @if($plan->is_featured)
                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012 2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            @endif
                            <span>Pilih {{ $plan->name }}</span>
                        </span>
                        @if($plan->is_featured)
                            <div class="absolute inset-0 bg-gradient-to-r from-white to-white opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                        @else
                            <div class="absolute inset-0 bg-gradient-to-r from-primary-500 to-primary-600 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                        @endif
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full glass-effect rounded-3xl shadow-2xl p-12 text-center animate-fade-in-up">
                <div class="w-20 h-20 mx-auto bg-amber-50 rounded-3xl flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-4">Paket Tidak Tersedia</h3>
                <p class="text-slate-600 max-w-md mx-auto mb-8">
                    Saat ini tidak ada paket berlangganan yang tersedia. Silakan hubungi tim support kami untuk bantuan lebih lanjut.
                </p>
                <button class="group px-8 py-4 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 011.564.815l1.283 2.815a1 1 0 00.505 1.21l-2.382 4.423a1 1 0 01-.505 1.21L6.282 8.815A1 1 0 005.718 10H4a1 1 0 01-1-1V7a1 1 0 011-1h.718z"/>
                        </svg>
                        <span>Hubungi Support</span>
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-amber-600 to-amber-700 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                </button>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Comparison Section -->
    <div class="max-w-7xl mx-auto mt-16">
        <div class="glass-effect rounded-3xl shadow-2xl p-8 animate-fade-in-up animate-delay-2">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-4">Butuh Bantuan Memilih?</h3>
                <p class="text-slate-600 max-w-2xl mx-auto">
                    Tim kami siap membantu Anda menemukan paket yang paling sesuai dengan kebutuhan yayasan dan sekolah Anda.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6">
                    <div class="w-16 h-16 mx-auto bg-primary-50 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 011.564.815l1.283 2.815a1 1 0 00.505 1.21l-2.382 4.423a1 1 0 01-.505 1.21L6.282 8.815A1 1 0 005.718 10H4a1 1 0 01-1-1V7a1 1 0 011-1h.718z"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-2">Analisis Kebutuhan</h4>
                    <p class="text-slate-600 text-sm">Kami menganalisis kebutuhan operasional Anda secara mendalam.</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="w-16 h-16 mx-auto bg-emerald-50 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-2">Konsultasi Gratis</h4>
                    <p class="text-slate-600 text-sm">Dapatkan rekomendasi paket yang tepat untuk Anda.</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="w-16 h-16 mx-auto bg-amber-50 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.003 9.003 0 00-5.816 2.514l-4.416-2.896A1 1 0 001.614 8.814L5.704 16.1a1 1 0 00-.887.498L2.164 16.1a1 1 0 00-.887.498A1 1 0 01.292.897V8a1 1 0 011-1h4.058a1 1 0 01.996.916l1.916 4.18a1 1 0 00.01 1.004L3.416-2.896a1 1 0 00.887.498l2.896 3.416a1 1 0 01-.292.897z"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-2">Dukungan 24/7</h4>
                    <p class="text-slate-600 text-sm">Tim support siap membantu kapan saja Anda butuhkan.</p>
                </div>
            </div>
            
            <div class="text-center mt-8">
                <button class="group px-8 py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.003 9.003 0 00-5.816 2.514l-4.416-2.896A1 1 0 001.614 8.814L5.704 16.1a1 1 0 00-.887.498L2.164 16.1a1 1 0 00-.887.498A1 1 0 01.292.897V8a1 1 0 011-1h4.058a1 1 0 01.996.916l1.916 4.18a1 1 0 00.01 1.004L3.416-2.896a1 1 0 00.887.498l2.896 3.416a1 1 0 01-.292.897z"/>
                        </svg>
                        <span>Mulai Konsultasi Gratis</span>
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-primary-700 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function setBillingPeriod(period) {
    // Update button styles
    const monthlyBtn = document.getElementById('monthly-btn');
    const yearlyBtn = document.getElementById('yearly-btn');
    const billingPeriods = document.querySelectorAll('.billing-period');
    const priceMonthly = document.querySelectorAll('.price-monthly');
    const priceYearly = document.querySelectorAll('.price-yearly');
    
    // Remove active classes from all buttons
    monthlyBtn.classList.remove('billing-toggle-active');
    yearlyBtn.classList.remove('billing-toggle-active');
    monthlyBtn.classList.add('billing-toggle-inactive');
    yearlyBtn.classList.add('billing-toggle-inactive');
    
    // Add active class to selected button
    if (period === 'monthly') {
        monthlyBtn.classList.remove('billing-toggle-inactive');
        monthlyBtn.classList.add('billing-toggle-active');
        
        // Show monthly prices, hide yearly prices
        priceMonthly.forEach(el => el.style.display = 'block');
        priceYearly.forEach(el => el.style.display = 'none');
        
        billingPeriods.forEach(el => el.textContent = 'bln');
    } else {
        yearlyBtn.classList.remove('billing-toggle-inactive');
        yearlyBtn.classList.add('billing-toggle-active');
        
        // Show yearly prices, hide monthly prices
        priceMonthly.forEach(el => el.style.display = 'none');
        priceYearly.forEach(el => el.style.display = 'block');
        
        billingPeriods.forEach(el => el.textContent = 'bln');
    }
}

// Initialize with monthly as default
document.addEventListener('DOMContentLoaded', function() {
    setBillingPeriod('monthly');
});
</script>
@endsection
