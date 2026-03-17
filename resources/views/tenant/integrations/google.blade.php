@extends('layouts.tenant-platform')

@section('title', 'Google Integration')

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
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">Google Workspace</h1>
                </div>
                <p class="text-primary-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Integrasi Google Workspace untuk produktivitas dan kolaborasi tim yang lebih baik
                </p>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">8</p>
                        <p class="text-primary-100 text-sm">Connected Services</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">156</p>
                        <p class="text-primary-100 text-sm">Active Users</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">24/7</p>
                        <p class="text-primary-100 text-sm">Sync Status</p>
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

    <!-- Google Services Section -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">Google Services</h2>
                            <p class="text-slate-600">Kelola integrasi Google Workspace</p>
                        </div>
                    </div>
                    <button onclick="showConnectServiceModal()" class="px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            Connect Service
                        </span>
                    </button>
                </div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Gmail -->
                    <div class="bg-slate-50 rounded-xl p-6 hover-lift animate-slide-in-left animate-delay-1">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900">Gmail</h3>
                            <p class="text-sm text-slate-600 mt-1">Email service</p>
                            <div class="mt-3">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Connected</span>
                            </div>
                        </div>
                    </div>

                    <!-- Google Drive -->
                    <div class="bg-slate-50 rounded-xl p-6 hover-lift animate-slide-in-left animate-delay-2">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900">Google Drive</h3>
                            <p class="text-sm text-slate-600 mt-1">Cloud storage</p>
                            <div class="mt-3">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Connected</span>
                            </div>
                        </div>
                    </div>

                    <!-- Google Calendar -->
                    <div class="bg-slate-50 rounded-xl p-6 hover-lift animate-slide-in-left animate-delay-3">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900">Google Calendar</h3>
                            <p class="text-sm text-slate-600 mt-1">Schedule management</p>
                            <div class="mt-3">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Connected</span>
                            </div>
                        </div>
                    </div>

                    <!-- Google Meet -->
                    <div class="bg-slate-50 rounded-xl p-6 hover-lift animate-slide-in-left animate-delay-1">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900">Google Meet</h3>
                            <p class="text-sm text-slate-600 mt-1">Video conferencing</p>
                            <div class="mt-3">
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">Not Connected</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sync Settings Section -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">Sync Settings</h2>
                        <p class="text-slate-600">Konfigurasi sinkronisasi Google Workspace</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <form class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Sync Frequency</label>
                            <select name="sync_frequency" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="realtime">Real-time</option>
                                <option value="5">Every 5 minutes</option>
                                <option value="15">Every 15 minutes</option>
                                <option value="30">Every 30 minutes</option>
                                <option value="60">Every hour</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Default Domain</label>
                            <input type="text" name="domain" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="yourdomain.com" value="yayasan-kemala-bhayangkari.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Admin Email</label>
                            <input type="email" name="admin_email" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="admin@yourdomain.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">API Quota Limit</label>
                            <input type="number" name="api_quota" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   value="1000" min="100" max="10000">
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="sync_contacts" class="mr-3" checked>
                                    <span class="text-sm font-medium text-slate-700">Sync Google Contacts</span>
                                </label>
                                <p class="text-xs text-slate-500 mt-1">Sync contacts with user directory</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="sync_calendar" class="mr-3" checked>
                                    <span class="text-sm font-medium text-slate-700">Sync Google Calendar</span>
                                </label>
                                <p class="text-xs text-slate-500 mt-1">Import calendar events and meetings</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="sync_drive" class="mr-3">
                                    <span class="text-sm font-medium text-slate-700">Sync Google Drive</span>
                                </label>
                                <p class="text-xs text-slate-500 mt-1">Backup documents to Google Drive</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" onclick="saveSyncSettings()" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Test Connection Section -->
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
                        <h2 class="text-2xl font-bold text-slate-900">Test Connection</h2>
                        <p class="text-slate-600">Uji koneksi ke Google Workspace API</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <form class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Service to Test</label>
                            <select name="service" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                <option value="gmail">Gmail</option>
                                <option value="drive">Google Drive</option>
                                <option value="calendar">Google Calendar</option>
                                <option value="meet">Google Meet</option>
                                <option value="admin">Admin SDK</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Test Type</label>
                            <select name="test_type" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                <option value="authentication">Authentication</option>
                                <option value="api_access">API Access</option>
                                <option value="permissions">Permissions</option>
                                <option value="full">Full Test</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" onclick="testGoogleConnection()" class="px-6 py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        Test Connection
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Connect Service Modal -->
<div id="connectServiceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-6 rounded-t-2xl">
            <h3 class="text-xl font-bold text-white">Connect Google Service</h3>
        </div>
        <form id="connectServiceForm" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Select Service</label>
                <select name="service" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Choose service...</option>
                    <option value="gmail">Gmail</option>
                    <option value="drive">Google Drive</option>
                    <option value="calendar">Google Calendar</option>
                    <option value="meet">Google Meet</option>
                    <option value="sheets">Google Sheets</option>
                    <option value="docs">Google Docs</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Client ID</label>
                <input type="text" name="client_id" required
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="Enter Google Client ID">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Client Secret</label>
                <input type="password" name="client_secret" required
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="Enter Google Client Secret">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Redirect URI</label>
                <input type="url" name="redirect_uri" required
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="https://yourdomain.com/google/callback">
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300">
                    Connect Service
                </button>
                <button type="button" onclick="closeConnectServiceModal()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2 px-4 rounded-lg transition-all duration-300">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showConnectServiceModal() {
    document.getElementById('connectServiceModal').classList.remove('hidden');
}

function closeConnectServiceModal() {
    document.getElementById('connectServiceModal').classList.add('hidden');
}

function saveSyncSettings() {
    // Simulate saving settings
    alert('Sync settings saved successfully! (This is a demo)');
}

function testGoogleConnection() {
    // Simulate connection test
    const button = event.target;
    button.disabled = true;
    button.textContent = 'Testing...';
    
    setTimeout(() => {
        button.disabled = false;
        button.textContent = 'Test Connection';
        alert('Google connection test completed! (This is a demo)');
    }, 2000);
}

// Handle form submission
document.getElementById('connectServiceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Google service connected successfully! (This is a demo)');
    closeConnectServiceModal();
});
</script>
@endsection
