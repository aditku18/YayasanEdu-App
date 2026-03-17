@extends('layouts.tenant-platform')

@section('title', 'API Integrations')

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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">API Integrations</h1>
                </div>
                <p class="text-primary-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Kelola API keys dan integrasi eksternal untuk sistem Anda
                </p>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">5</p>
                        <p class="text-primary-100 text-sm">Total Integrations</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">3</p>
                        <p class="text-primary-100 text-sm">Active</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">2</p>
                        <p class="text-primary-100 text-sm">Inactive</p>
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

    <!-- API Keys Section -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">API Keys</h2>
                            <p class="text-slate-600">Kelola kunci akses API untuk integrasi eksternal</p>
                        </div>
                    </div>
                    <button onclick="showCreateApiKeyModal()" class="px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            Generate API Key
                        </span>
                    </button>
                </div>
            </div>
            
            <div class="p-8">
                <div class="space-y-4">
                    <!-- API Key 1 -->
                    <div class="bg-slate-50 rounded-xl p-6 hover-lift animate-slide-in-left animate-delay-1">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900">Production API Key</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <code class="text-xs bg-slate-200 px-2 py-1 rounded">sk_live_51H3...2k9J</code>
                                        <button onclick="copyToClipboard('sk_live_51H3...2k9J')" class="text-primary-600 hover:text-primary-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Active</span>
                                <button class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors" title="Revoke">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-slate-600">
                            <p>Created: 15 Mar 2026 • Last used: 2 hours ago • 1,234 requests</p>
                        </div>
                    </div>

                    <!-- API Key 2 -->
                    <div class="bg-slate-50 rounded-xl p-6 hover-lift animate-slide-in-left animate-delay-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900">Development API Key</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <code class="text-xs bg-slate-200 px-2 py-1 rounded">sk_test_51H3...8kL2</code>
                                        <button onclick="copyToClipboard('sk_test_51H3...8kL2')" class="text-primary-600 hover:text-primary-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">Testing</span>
                                <button class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors" title="Revoke">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-slate-600">
                            <p>Created: 10 Mar 2026 • Last used: 1 day ago • 567 requests</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Webhooks Section -->
    <div class="max-w-7xl mx-auto">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">Webhooks</h2>
                            <p class="text-slate-600">Konfigurasi webhook untuk notifikasi real-time</p>
                        </div>
                    </div>
                    <button onclick="showCreateWebhookModal()" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            Add Webhook
                        </span>
                    </button>
                </div>
            </div>
            
            <div class="p-8">
                <div class="space-y-4">
                    <!-- Webhook 1 -->
                    <div class="bg-slate-50 rounded-xl p-6 hover-lift animate-slide-in-left animate-delay-1">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900">Payment Webhook</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <code class="text-xs bg-slate-200 px-2 py-1 rounded">https://api.example.com/webhooks/payment</code>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Active</span>
                                <button class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-4h-4v4m0 0h4m0 0v-4"/>
                                    </svg>
                                </button>
                                <button class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-slate-600">
                            <p>Events: payment.success, payment.failed • Created: 12 Mar 2026 • Last delivered: 5 min ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create API Key Modal -->
<div id="createApiKeyModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-6 rounded-t-2xl">
            <h3 class="text-xl font-bold text-white">Generate API Key</h3>
        </div>
        <form id="createApiKeyForm" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Key Name</label>
                <input type="text" name="name" required
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="e.g., Production API Key">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Environment</label>
                <select name="environment" required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="production">Production</option>
                    <option value="development">Development</option>
                    <option value="testing">Testing</option>
                </select>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300">
                    Generate
                </button>
                <button type="button" onclick="closeCreateApiKeyModal()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2 px-4 rounded-lg transition-all duration-300">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Create Webhook Modal -->
<div id="createWebhookModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-t-2xl">
            <h3 class="text-xl font-bold text-white">Add Webhook</h3>
        </div>
        <form id="createWebhookForm" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Webhook Name</label>
                <input type="text" name="name" required
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="e.g., Payment Webhook">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Endpoint URL</label>
                <input type="url" name="url" required
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="https://api.example.com/webhooks/payment">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Events</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="events[]" value="payment.success" class="mr-2">
                        <span>Payment Success</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="events[]" value="payment.failed" class="mr-2">
                        <span>Payment Failed</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="events[]" value="user.created" class="mr-2">
                        <span>User Created</span>
                    </label>
                </div>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300">
                    Add Webhook
                </button>
                <button type="button" onclick="closeCreateWebhookModal()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2 px-4 rounded-lg transition-all duration-300">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showCreateApiKeyModal() {
    document.getElementById('createApiKeyModal').classList.remove('hidden');
}

function closeCreateApiKeyModal() {
    document.getElementById('createApiKeyModal').classList.add('hidden');
}

function showCreateWebhookModal() {
    document.getElementById('createWebhookModal').classList.remove('hidden');
}

function closeCreateWebhookModal() {
    document.getElementById('createWebhookModal').classList.add('hidden');
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-emerald-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        toast.textContent = 'Copied to clipboard!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    });
}

// Handle form submissions
document.getElementById('createApiKeyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Simulate API key generation
    alert('API Key generated successfully! (This is a demo)');
    closeCreateApiKeyModal();
});

document.getElementById('createWebhookForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Simulate webhook creation
    alert('Webhook added successfully! (This is a demo)');
    closeCreateWebhookModal();
});
</script>
@endsection
