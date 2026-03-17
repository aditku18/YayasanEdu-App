@extends('layouts.tenant-platform')

@section('title', 'Laporan Sistem')

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
    .status-healthy {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    .status-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    .status-critical {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">Laporan Sistem</h1>
                </div>
                <p class="text-primary-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Monitor performa dan kesehatan sistem EduSaaS secara real-time untuk memastikan stabilitas operasional
                </p>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">99.9%</p>
                        <p class="text-primary-100 text-sm">Uptime</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">0.8s</p>
                        <p class="text-primary-100 text-sm">Response Time</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">72%</p>
                        <p class="text-primary-100 text-sm">Resource Usage</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">3</p>
                        <p class="text-primary-100 text-sm">Critical Issues</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health Cards -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="glass-effect rounded-2xl p-6 hover-lift animate-fade-in-up animate-delay-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm text-emerald-600 font-medium">Healthy</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">System Health</h3>
                <p class="text-slate-600 text-sm mt-1">Status keseluruhan sistem</p>
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                        <span>Performance</span>
                        <span>98%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: 98%"></div>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-2xl p-6 hover-lift animate-fade-in-up animate-delay-2">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                        </svg>
                    </div>
                    <span class="text-sm text-blue-600 font-medium">Optimal</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">Database</h3>
                <p class="text-slate-600 text-sm mt-1">Kondisi database sistem</p>
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                        <span>Usage</span>
                        <span>65%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 65%"></div>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-2xl p-6 hover-lift animate-fade-in-up animate-delay-3">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                    <span class="text-sm text-amber-600 font-medium">Good</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">API Response</h3>
                <p class="text-slate-600 text-sm mt-1">Performa API endpoints</p>
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                        <span>Avg Response</span>
                        <span>0.8s</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-amber-500 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-2xl p-6 hover-lift animate-fade-in-up animate-delay-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <span class="text-sm text-purple-600 font-medium">Secure</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">Security</h3>
                <p class="text-slate-600 text-sm mt-1">Status keamanan sistem</p>
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                        <span>Threat Level</span>
                        <span>Low</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: 15%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Server Performance Metrics -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
                <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">Server Performance</h2>
                            <p class="text-slate-600 text-sm">Metrik performa real-time</p>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="space-y-6">
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-slate-900">CPU Usage</span>
                                    <span class="text-sm text-slate-500">42%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full" style="width: 42%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-slate-900">Memory Usage</span>
                                    <span class="text-sm text-slate-500">68%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-3 rounded-full" style="width: 68%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-slate-900">Disk Usage</span>
                                    <span class="text-sm text-slate-500">54%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-3 rounded-full" style="width: 54%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-slate-900">Network I/O</span>
                                    <span class="text-sm text-slate-500">23%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-3 rounded-full" style="width: 23%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
                <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">Error Logs</h2>
                            <p class="text-slate-600 text-sm">Log error sistem terkini</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">System Running</p>
                                    <p class="text-xs text-slate-500">All services operational</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full">OK</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-amber-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">High Memory Usage</p>
                                    <p class="text-xs text-slate-500">Memory usage above 70%</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">Warning</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">API Slow Response</p>
                                    <p class="text-xs text-slate-500">Response time > 2 seconds</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Info</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">Database Connection</p>
                                    <p class="text-xs text-slate-500">Failed to connect to DB</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Critical</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Events Table -->
    <div class="max-w-7xl mx-auto">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">System Events</h2>
                            <p class="text-slate-600 text-sm">Log aktivitas sistem terkini</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                Filter
                            </span>
                        </button>
                        <button class="px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Export Logs
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Timestamp</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Event Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Source</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Severity</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <tr class="hover-lift animate-slide-in-left animate-delay-1">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    2024-03-16 10:30:15
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">System</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">System startup completed</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    Server Main
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-healthy px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                                        Success
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-slate-100 text-slate-800 rounded-full">Info</span>
                                </td>
                            </tr>

                            <tr class="hover-lift animate-slide-in-left animate-delay-2">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    2024-03-16 10:25:42
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full">Database</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">Database backup completed</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    MySQL Server
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-healthy px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                                        Success
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-slate-100 text-slate-800 rounded-full">Info</span>
                                </td>
                            </tr>

                            <tr class="hover-lift animate-slide-in-left animate-delay-3">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    2024-03-16 10:20:18
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">Security</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">Failed login attempt detected</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    Auth Service
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-warning px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                                        Warning
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">Warning</span>
                                </td>
                            </tr>

                            <tr class="hover-lift animate-slide-in-left animate-delay-1">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    2024-03-16 10:15:33
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Error</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">API endpoint timeout</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    Payment API
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-critical px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                                        Critical
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Critical</span>
                                </td>
                            </tr>

                            <tr class="hover-lift animate-slide-in-left animate-delay-2">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    2024-03-16 10:10:27
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">System</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">Memory usage alert resolved</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    Monitoring Service
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-healthy px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                                        Resolved
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-slate-100 text-slate-800 rounded-full">Info</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
