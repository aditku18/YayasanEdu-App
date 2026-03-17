@extends('layouts.tenant-platform')

@section('title', 'Pengaturan Notifikasi')

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
    .notification-item {
        transition: all 0.3s ease;
    }
    .notification-item:hover {
        transform: translateY(-2px);
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">Pengaturan Notifikasi</h1>
                </div>
                <p class="text-primary-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Kelola preferensi notifikasi untuk tetap update dengan informasi penting sistem Anda
                </p>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">3</p>
                        <p class="text-primary-100 text-sm">Jenis Notifikasi</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">24/7</p>
                        <p class="text-primary-100 text-sm">Real-time Updates</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">100%</p>
                        <p class="text-primary-100 text-sm">Customizable</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto mb-6">
            <div class="glass-effect rounded-2xl p-6 border border-emerald-200 bg-emerald-50 animate-fade-in-up">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-emerald-800">Berhasil Disimpan!</p>
                        <p class="text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto mb-6">
            <div class="glass-effect rounded-2xl p-6 border border-red-200 bg-red-50 animate-fade-in-up">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-red-800">Terjadi Kesalahan!</p>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Notification Settings -->
    <div class="max-w-7xl mx-auto">
        <form method="POST" action="{{ route('tenant.setting.notification.update') }}" class="space-y-8">
            @csrf
            
            <!-- Email Notifications -->
            <div class="notification-item glass-effect rounded-2xl p-8 hover-lift animate-fade-in-up animate-delay-1">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Notifikasi Email</h3>
                        <p class="text-slate-600">Terima notifikasi penting melalui email</p>
                    </div>
                    <span class="px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-medium">Recommended</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                        <input type="checkbox" name="email_new_user" value="1" {{ old('email_new_user', 1) ? 'checked' : '' }} class="w-5 h-5 text-green-600 border-slate-300 rounded focus:ring-green-500">
                        <div>
                            <div class="font-semibold text-slate-900">Pengguna Baru</div>
                            <div class="text-sm text-slate-500">Pendaftaran baru</div>
                        </div>
                    </label>
                    
                    <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                        <input type="checkbox" name="email_payment" value="1" {{ old('email_payment', 1) ? 'checked' : '' }} class="w-5 h-5 text-green-600 border-slate-300 rounded focus:ring-green-500">
                        <div>
                            <div class="font-semibold text-slate-900">Pembayaran</div>
                            <div class="text-sm text-slate-500">Tagihan & konfirmasi</div>
                        </div>
                    </label>
                    
                    <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                        <input type="checkbox" name="email_system" value="1" {{ old('email_system', 1) ? 'checked' : '' }} class="w-5 h-5 text-green-600 border-slate-300 rounded focus:ring-green-500">
                        <div>
                            <div class="font-semibold text-slate-900">Sistem</div>
                            <div class="text-sm text-slate-500">Update & maintenance</div>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- SMS Notifications -->
            <div class="notification-item glass-effect rounded-2xl p-8 hover-lift animate-fade-in-up animate-delay-2">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Notifikasi SMS</h3>
                        <p class="text-slate-600">Notifikasi penting melalui SMS</p>
                    </div>
                    <span class="px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">Fast</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                        <input type="checkbox" name="sms_payment" value="1" {{ old('sms_payment') ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-slate-300 rounded focus:ring-purple-500">
                        <div>
                            <div class="font-semibold text-slate-900">Pembayaran via SMS</div>
                            <div class="text-sm text-slate-500">Konfirmasi instan</div>
                        </div>
                    </label>
                    
                    <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                        <input type="checkbox" name="sms_urgent" value="1" {{ old('sms_urgent') ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-slate-300 rounded focus:ring-purple-500">
                        <div>
                            <div class="font-semibold text-slate-900">Notifikasi Penting</div>
                            <div class="text-sm text-slate-500">Alert darurat</div>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Push Notifications -->
            <div class="notification-item glass-effect rounded-2xl p-8 hover-lift animate-fade-in-up animate-delay-3">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Notifikasi Push</h3>
                        <p class="text-slate-600">Notifikasi real-time di browser</p>
                    </div>
                    <span class="px-4 py-2 bg-orange-100 text-orange-700 rounded-full text-sm font-medium">Real-time</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                        <input type="checkbox" name="push_announcements" value="1" {{ old('push_announcements', 1) ? 'checked' : '' }} class="w-5 h-5 text-orange-600 border-slate-300 rounded focus:ring-orange-500">
                        <div>
                            <div class="font-semibold text-slate-900">Pengumuman</div>
                            <div class="text-sm text-slate-500">Info & update terbaru</div>
                        </div>
                    </label>
                    
                    <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                        <input type="checkbox" name="push_reminders" value="1" {{ old('push_reminders', 1) ? 'checked' : '' }} class="w-5 h-5 text-orange-600 border-slate-300 rounded focus:ring-orange-500">
                        <div>
                            <div class="font-semibold text-slate-900">Pengingat</div>
                            <div class="text-sm text-slate-500">Jadwal & deadline</div>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="glass-effect rounded-2xl p-6 animate-fade-in-up">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-center md:text-left">
                        <h4 class="font-semibold text-slate-900 mb-1">Simpan Perubahan</h4>
                        <p class="text-sm text-slate-500">Perubahan akan diterapkan secara otomatis</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="resetForm()" class="px-6 py-3 text-slate-600 hover:text-slate-900 font-medium transition-colors">
                            Reset
                        </button>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold rounded-xl shadow-lg transition-all duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Info Card -->
    <div class="max-w-7xl mx-auto mt-8">
        <div class="glass-effect rounded-2xl p-6 border border-blue-200 bg-blue-50 animate-fade-in-up">
            <div class="flex gap-4">
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-blue-900 mb-2">💡 Tips Notifikasi</h4>
                    <p class="text-blue-700 mb-2">
                        Pastikan notifikasi email dan SMS diaktifkan untuk informasi penting terkait:
                    </p>
                    <ul class="text-blue-700 space-y-1 text-sm">
                        <li>• Keamanan akun dan peringatan login</li>
                        <li>• Konfirmasi pembayaran dan tagihan</li>
                        <li>• Update sistem dan maintenance</li>
                        <li>• Informasi penting lainnya</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset semua pengaturan notifikasi?')) {
        document.querySelector('form').reset();
    }
}
</script>
@endsection
