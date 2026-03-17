@extends('layouts.tenant-platform')

@section('title', 'Pengaturan Keamanan')

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
    .security-item {
        transition: all 0.3s ease;
    }
    .security-item:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto mb-12">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-red-600 via-red-500 to-orange-500 p-8 md:p-12 text-white animate-fade-in-up shadow-2xl shadow-red-500/20">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2 animate-pulse-slow" style="animation-delay: 1s;"></div>

            <div class="relative z-10 text-center">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">Pengaturan Keamanan</h1>
                </div>
                <p class="text-red-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Kelola keamanan sistem dan perlindungan data untuk menjaga integritas platform Anda
                </p>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">256-bit</p>
                        <p class="text-red-100 text-sm">Enkripsi SSL</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">24/7</p>
                        <p class="text-red-100 text-sm">Monitoring</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">99.9%</p>
                        <p class="text-red-100 text-sm">Uptime</p>
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

    <!-- Security Settings -->
    <div class="max-w-7xl mx-auto">
        <form method="POST" action="{{ route('tenant.setting.security.update') }}" class="space-y-8">
            @csrf
            
            <!-- Password Policy -->
            <div class="security-item glass-effect rounded-2xl p-8 hover-lift animate-fade-in-up animate-delay-1">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Kebijakan Kata Sandi</h3>
                        <p class="text-slate-600">Atur standar keamanan kata sandi untuk semua pengguna</p>
                    </div>
                    <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">Essential</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Panjang minimum kata sandi</label>
                        <input type="number" name="min_password_length" value="{{ old('min_password_length', 8) }}" min="6" max="20" 
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                    
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                            <input type="checkbox" name="require_uppercase" value="1" {{ old('require_uppercase', 1) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <div>
                                <div class="font-semibold text-slate-900">Huruf Besar</div>
                                <div class="text-sm text-slate-500">Wajib menggunakan A-Z</div>
                            </div>
                        </label>
                        
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                            <input type="checkbox" name="require_numbers" value="1" {{ old('require_numbers', 1) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <div>
                                <div class="font-semibold text-slate-900">Angka</div>
                                <div class="text-sm text-slate-500">Wajib menggunakan 0-9</div>
                            </div>
                        </label>
                        
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                            <input type="checkbox" name="require_symbols" value="1" {{ old('require_symbols') ? 'checked' : '' }} class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <div>
                                <div class="font-semibold text-slate-900">Simbol</div>
                                <div class="text-sm text-slate-500">Wajib menggunakan !@#$%</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Session Security -->
            <div class="security-item glass-effect rounded-2xl p-8 hover-lift animate-fade-in-up animate-delay-2">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Keamanan Sesi</h3>
                        <p class="text-slate-600">Kelola durasi dan keamanan sesi pengguna</p>
                    </div>
                    <span class="px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">Advanced</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Timeout sesi (menit)</label>
                        <input type="number" name="session_timeout" value="{{ old('session_timeout', 120) }}" min="5" max="480" 
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <p class="text-xs text-slate-500 mt-1">Sesi akan otomatis logout setelah periode ini</p>
                    </div>
                    
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                            <input type="checkbox" name="require_2fa" value="1" {{ old('require_2fa') ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-slate-300 rounded focus:ring-purple-500">
                            <div>
                                <div class="font-semibold text-slate-900">2FA untuk Admin</div>
                                <div class="text-sm text-slate-500">Wajib 2 faktor autentikasi</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Login Security -->
            <div class="security-item glass-effect rounded-2xl p-8 hover-lift animate-fade-in-up animate-delay-3">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Keamanan Login</h3>
                        <p class="text-slate-600">Pengaturan keamanan untuk proses login</p>
                    </div>
                    <span class="px-4 py-2 bg-red-100 text-red-700 rounded-full text-sm font-medium">Critical</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Maksimum percobaan login</label>
                        <input type="number" name="max_login_attempts" value="{{ old('max_login_attempts', 5) }}" min="3" max="10" 
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all">
                        <p class="text-xs text-slate-500 mt-1">Akun akan dikunci setelah percobaan gagal</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Durasi lockout (menit)</label>
                        <input type="number" name="lockout_duration" value="{{ old('lockout_duration', 15) }}" min="1" max="60" 
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all">
                        <p class="text-xs text-slate-500 mt-1">Durasi penguncian akun otomatis</p>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="glass-effect rounded-2xl p-6 animate-fade-in-up">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-center md:text-left">
                        <h4 class="font-semibold text-slate-900 mb-1">Simpan Pengaturan Keamanan</h4>
                        <p class="text-sm text-slate-500">Perubahan akan diterapkan secara global</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="resetForm()" class="px-6 py-3 text-slate-600 hover:text-slate-900 font-medium transition-colors">
                            Reset
                        </button>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-xl shadow-lg transition-all duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.586-4L12 3l-4.586 4.414M3 7h6l4 4 4-4h6"/>
                            </svg>
                            Simpan Keamanan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Security Tips Card -->
    <div class="max-w-7xl mx-auto mt-8">
        <div class="glass-effect rounded-2xl p-6 border border-red-200 bg-red-50 animate-fade-in-up">
            <div class="flex gap-4">
                <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-red-900 mb-2">🔒 Tips Keamanan</h4>
                    <p class="text-red-700 mb-2">
                        Pastikan pengaturan keamanan Anda optimal untuk melindungi data:
                    </p>
                    <ul class="text-red-700 space-y-1 text-sm">
                        <li>• Gunakan kata sandi minimal 8 karakter dengan kombinasi huruf, angka, dan simbol</li>
                        <li>• Aktifkan 2FA untuk semua akun administrator</li>
                        <li>• Set session timeout yang wajar (30-120 menit)</li>
                        <li>• Batasi percobaan login untuk mencegah brute force attack</li>
                        <li>• Regular backup dan monitoring aktivitas mencurigakan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset semua pengaturan keamanan?')) {
        document.querySelector('form').reset();
    }
}
</script>
@endsection
