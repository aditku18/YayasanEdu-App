@extends('layouts.tenant-platform')

@section('title', 'Payment Integration')

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
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">Payment Integration</h1>
                </div>
                <p class="text-primary-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Integrasi payment gateway untuk transaksi pembayaran yang aman dan mudah
                </p>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">5</p>
                        <p class="text-primary-100 text-sm">Active Gateways</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">Rp 45.2M</p>
                        <p class="text-primary-100 text-sm">Monthly Volume</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">99.9%</p>
                        <p class="text-primary-100 text-sm">Success Rate</p>
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
                        <p class="font-bold text-emerald-800">Success!</p>
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
                        <p class="font-bold text-red-800">Error!</p>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Payment Gateways Section -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">Payment Gateways</h2>
                            <p class="text-slate-600">Kelola gateway pembayaran terdaftar</p>
                        </div>
                    </div>
                    <button onclick="showAddGatewayModal()" class="px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            Add Gateway
                        </span>
                    </button>
                </div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Midtrans -->
                    <div class="bg-slate-50 rounded-xl p-6 hover-lift animate-slide-in-left animate-delay-1">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900">Midtrans</h3>
                            <p class="text-sm text-slate-600 mt-1">Payment gateway Indonesia</p>
                            <div class="mt-3">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Active</span>
                            </div>
                            <div class="mt-4 w-full">
                                <div class="flex justify-between text-xs text-slate-500 mb-1">
                                    <span>Success Rate</span>
                                    <span>98.5%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-emerald-500 h-2 rounded-full" style="width: 98.5%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Xendit -->
                    <div class="bg-slate-50 rounded-xl p-6 hover-lift animate-slide-in-left animate-delay-2">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900">Xendit</h3>
                            <p class="text-sm text-slate-600 mt-1">Modern payment solution</p>
                            <div class="mt-3">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Active</span>
                            </div>
                            <div class="mt-4 w-full">
                                <div class="flex justify-between text-xs text-slate-500 mb-1">
                                    <span>Success Rate</span>
                                    <span>99.2%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: 99.2%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DOKU -->
                    <div class="bg-slate-50 rounded-xl p-6 hover-lift animate-slide-in-left animate-delay-3">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900">DOKU</h3>
                            <p class="text-sm text-slate-600 mt-1">E-payment solution</p>
                            <div class="mt-3">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Active</span>
                            </div>
                            <div class="mt-4 w-full">
                                <div class="flex justify-between text-xs text-slate-500 mb-1">
                                    <span>Success Rate</span>
                                    <span>97.8%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-purple-500 h-2 rounded-full" style="width: 97.8%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PayPal -->
                    <div class="bg-slate-50 rounded-xl p-6 hover-lift animate-slide-in-left animate-delay-1">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900">PayPal</h3>
                            <p class="text-sm text-slate-600 mt-1">International payments</p>
                            <div class="mt-3">
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">Testing</span>
                            </div>
                            <div class="mt-4 w-full">
                                <div class="flex justify-between text-xs text-slate-500 mb-1">
                                    <span>Success Rate</span>
                                    <span>N/A</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-amber-500 h-2 rounded-full" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stripe -->
                    <div class="bg-slate-50 rounded-xl p-6 hover-lift animate-slide-in-left animate-delay-2">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900">Stripe</h3>
                            <p class="text-sm text-slate-600 mt-1">Global payment platform</p>
                            <div class="mt-3">
                                <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-bold rounded-full">Inactive</span>
                            </div>
                            <div class="mt-4 w-full">
                                <div class="flex justify-between text-xs text-slate-500 mb-1">
                                    <span>Success Rate</span>
                                    <span>N/A</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-slate-400 h-2 rounded-full" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Transfer -->
                    <div class="bg-slate-50 rounded-xl p-6 hover-lift animate-slide-in-left animate-delay-3">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900">Bank Transfer</h3>
                            <p class="text-sm text-slate-600 mt-1">Direct bank payments</p>
                            <div class="mt-3">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Active</span>
                            </div>
                            <div class="mt-4 w-full">
                                <div class="flex justify-between text-xs text-slate-500 mb-1">
                                    <span>Success Rate</span>
                                    <span>95.3%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-teal-500 h-2 rounded-full" style="width: 95.3%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Settings Section -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">Transaction Settings</h2>
                        <p class="text-slate-600">Konfigurasi pengaturan transaksi pembayaran</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <form class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Default Gateway</label>
                            <select name="default_gateway" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="midtrans">Midtrans</option>
                                <option value="xendit">Xendit</option>
                                <option value="doku">DOKU</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Currency</label>
                            <select name="currency" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="IDR">Indonesian Rupiah (IDR)</option>
                                <option value="USD">US Dollar (USD)</option>
                                <option value="EUR">Euro (EUR)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Minimum Amount</label>
                            <input type="number" name="min_amount" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   value="10000" min="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Maximum Amount</label>
                            <input type="number" name="max_amount" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   value="10000000" min="0">
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="auto_confirm" class="mr-3" checked>
                                    <span class="text-sm font-medium text-slate-700">Auto-confirm successful payments</span>
                                </label>
                                <p class="text-xs text-slate-500 mt-1">Automatically confirm payments after successful verification</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="email_notifications" class="mr-3" checked>
                                    <span class="text-sm font-medium text-slate-700">Email notifications</span>
                                </label>
                                <p class="text-xs text-slate-500 mt-1">Send email receipts to customers</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="webhook_enabled" class="mr-3" checked>
                                    <span class="text-sm font-medium text-slate-700">Webhook notifications</span>
                                </label>
                                <p class="text-xs text-slate-500 mt-1">Receive payment status updates via webhook</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" onclick="saveTransactionSettings()" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Test Transaction Section -->
    <div class="max-w-7xl mx-auto">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">Test Transaction</h2>
                        <p class="text-slate-600">Uji proses pembayaran dengan gateway</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <form class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Select Gateway</label>
                            <select name="gateway" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                <option value="">Choose gateway...</option>
                                <option value="midtrans">Midtrans</option>
                                <option value="xendit">Xendit</option>
                                <option value="doku">DOKU</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Amount</label>
                            <input type="number" name="amount" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                   placeholder="10000" min="1000" value="10000">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Payment Method</label>
                            <select name="payment_method" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                <option value="credit_card">Credit Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="ewallet">E-Wallet</option>
                                <option value="va">Virtual Account</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Test Mode</label>
                            <select name="test_mode" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                <option value="success">Success</option>
                                <option value="failed">Failed</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" onclick="testTransaction()" class="px-6 py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        Test Transaction
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Gateway Modal -->
<div id="addGatewayModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-6 rounded-t-2xl">
            <h3 class="text-xl font-bold text-white">Add Payment Gateway</h3>
        </div>
        <form id="addGatewayForm" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Gateway Type</label>
                <select name="gateway_type" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Choose gateway...</option>
                    <option value="midtrans">Midtrans</option>
                    <option value="xendit">Xendit</option>
                    <option value="doku">DOKU</option>
                    <option value="paypal">PayPal</option>
                    <option value="stripe">Stripe</option>
                    <option value="bank_transfer">Bank Transfer</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">API Key / Client ID</label>
                <input type="text" name="api_key" required
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="Enter API key or client ID">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Secret Key</label>
                <input type="password" name="secret_key" required
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="Enter secret key">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Environment</label>
                <select name="environment" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="sandbox">Sandbox</option>
                    <option value="production">Production</option>
                </select>
            </div>
            
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" class="mr-2" checked>
                    <span class="text-sm font-medium text-slate-700">Enable gateway</span>
                </label>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300">
                    Add Gateway
                </button>
                <button type="button" onclick="closeAddGatewayModal()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2 px-4 rounded-lg transition-all duration-300">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showAddGatewayModal() {
    document.getElementById('addGatewayModal').classList.remove('hidden');
}

function closeAddGatewayModal() {
    document.getElementById('addGatewayModal').classList.add('hidden');
}

function saveTransactionSettings() {
    // Simulate saving settings
    alert('Transaction settings saved successfully! (This is a demo)');
}

function testTransaction() {
    // Simulate transaction test
    const button = event.target;
    button.disabled = true;
    button.textContent = 'Processing...';
    
    setTimeout(() => {
        button.disabled = false;
        button.textContent = 'Test Transaction';
        alert('Test transaction completed! (This is a demo)');
    }, 2000);
}

// Handle form submission
document.getElementById('addGatewayForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Payment gateway added successfully! (This is a demo)');
    closeAddGatewayModal();
});
</script>
@endsection
