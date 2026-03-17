<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EduSaaS') }} — Platform</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex">

            {{-- ===== SIDEBAR ===== --}}
            {{-- Mobile overlay --}}
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 z-40 lg:hidden" @click="sidebarOpen = false"></div>

            {{-- Sidebar panel --}}
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 bg-sidebar transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col">
                
                {{-- Logo --}}
                <div class="flex items-center gap-3 px-6 h-16 border-b border-white/10 flex-shrink-0">
                    <div class="w-9 h-9 bg-gradient-to-br from-primary-400 to-primary-600 rounded-lg flex items-center justify-center shadow-lg shadow-primary-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg font-bold text-white tracking-tight">EduSaaS</span>
                        <span class="block text-[10px] text-slate-500 uppercase tracking-widest font-medium">Platform Admin</span>
                    </div>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto" x-data="{ openMenus: {} }">
                    <p class="px-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-3">Menu Utama</p>

                    @if(tenant())
                        {{-- Tenant Navigation --}}
                        <a href="{{ route('tenant.dashboard') }}" class="sidebar-link {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>

                        <a href="{{ route('tenant.units.index') }}" class="sidebar-link">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Data Sekolah
                        </a>

                        <a href="{{ route('tenant.students.index') }}" class="sidebar-link">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Data Siswa
                        </a>

                        <a href="{{ route('tenant.teachers.index') }}" class="sidebar-link">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Data Guru
                        </a>

                        <p class="px-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-3 mt-8">Laporan</p>

                        <a href="{{ route('tenant.reports.index') }}" class="sidebar-link">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Laporan
                        </a>
                    @else
                        {{-- Platform Admin Navigation --}}
                        
                        {{-- Dashboard Utama --}}
                        <a href="{{ route('platform.dashboard') }}" class="sidebar-link {{ request()->routeIs('platform.dashboard') ? 'active' : '' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard Utama
                        </a>

                        {{-- Manajemen Yayasan --}}
                        <div x-data="{ open: {{ request()->routeIs('platform.foundations.*') || request()->routeIs('platform.email-verifications.*') || request()->routeIs('platform.registrations.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="sidebar-link w-full justify-between group {{ request()->routeIs('platform.foundations.*') || request()->routeIs('platform.email-verifications.*') || request()->routeIs('platform.registrations.*') ? 'active' : '' }}">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Manajemen Yayasan
                                </span>
                                <svg class="w-4 h-4 transition-transform duration-200 group-hover:text-white" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                                <a href="{{ route('platform.foundations.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.foundations.index') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Semua Yayasan
                                </a>
                                <a href="{{ route('platform.registrations.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.registrations.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Registrasi Baru
                                </a>
                                <a href="{{ route('platform.foundations.active') }}" class="sidebar-sublink {{ request()->routeIs('platform.foundations.active') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Yayasan Aktif
                                </a>
                                <a href="{{ route('platform.foundations.suspended') }}" class="sidebar-sublink {{ request()->routeIs('platform.foundations.suspended') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Yayasan Suspended
                                </a>
                                <a href="{{ route('platform.email-verifications.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.email-verifications.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Verifikasi Email
                                </a>
                            </div>
                        </div>

                        {{-- Subscription --}}
                        <div x-data="{ open: {{ request()->routeIs('platform.plans.*') || request()->routeIs('platform.subscriptions.*') || request()->routeIs('platform.trials.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="sidebar-link w-full justify-between group {{ request()->routeIs('platform.plans.*') || request()->routeIs('platform.subscriptions.*') || request()->routeIs('platform.trials.*') ? 'active' : '' }}">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                    </svg>
                                    Subscription
                                </span>
                                <svg class="w-4 h-4 transition-transform duration-200 group-hover:text-white" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                                <a href="{{ route('platform.plans.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.plans.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Paket Langganan
                                </a>
                                <a href="{{ route('platform.subscriptions.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.subscriptions.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Langganan Yayasan
                                </a>
                                <a href="{{ route('platform.trials.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.trials.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Masa Trial
                                </a>
                            </div>
                        </div>

                        {{-- Plugin / Modules --}}
                        <div x-data="{ open: {{ request()->routeIs('platform.plugins.*') || request()->routeIs('platform.marketplace.*') || request()->routeIs('platform.attendance.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="sidebar-link w-full justify-between group {{ request()->routeIs('platform.plugins.*') || request()->routeIs('platform.marketplace.*') || request()->routeIs('platform.attendance.*') ? 'active' : '' }}">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                    </svg>
                                    Plugin / Modules
                                </span>
                                <svg class="w-4 h-4 transition-transform duration-200 group-hover:text-white" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                                <a href="{{ route('platform.plugins.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.plugins.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Daftar Plugin
                                </a>
                                <a href="{{ route('platform.plugins.active') }}" class="sidebar-sublink {{ request()->routeIs('platform.plugins.active') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Plugin Aktif
                                </a>
                                <a href="{{ route('platform.marketplace.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.marketplace.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Marketplace Plugin
                                </a>
                                <a href="{{ route('platform.attendance.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.attendance.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Modul Absensi
                                </a>
                            </div>
                        </div>

                        {{-- Keuangan --}}
                        <div x-data="{ open: {{ request()->routeIs('platform.invoices.*') || request()->routeIs('platform.payments.*') || request()->routeIs('platform.transactions.*') || request()->routeIs('platform.refunds.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="sidebar-link w-full justify-between group {{ request()->routeIs('platform.invoices.*') || request()->routeIs('platform.payments.*') || request()->routeIs('platform.transactions.*') || request()->routeIs('platform.refunds.*') ? 'active' : '' }}">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Keuangan
                                </span>
                                <svg class="w-4 h-4 transition-transform duration-200 group-hover:text-white" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                                <a href="{{ route('platform.invoices.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.invoices.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Invoice
                                </a>
                                <a href="{{ route('platform.payments.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.payments.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Pembayaran
                                </a>
                                <a href="{{ route('platform.transactions.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.transactions.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Transaksi
                                </a>
                                <a href="{{ route('platform.refunds.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.refunds.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Refund
                                </a>
                            </div>
                        </div>

                        {{-- Monitoring --}}
                        <div x-data="{ open: {{ request()->routeIs('platform.statistics.*') || request()->routeIs('platform.storage.*') || request()->routeIs('platform.webhooks.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="sidebar-link w-full justify-between group {{ request()->routeIs('platform.statistics.*') || request()->routeIs('platform.storage.*') || request()->routeIs('platform.webhooks.*') ? 'active' : '' }}">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Monitoring
                                </span>
                                <svg class="w-4 h-4 transition-transform duration-200 group-hover:text-white" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                                <a href="{{ route('platform.statistics.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.statistics.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Statistik Platform
                                </a>
                                <a href="{{ route('platform.storage.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.storage.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Storage
                                </a>
                                <a href="{{ route('platform.webhooks.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.webhooks.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    System Log
                                </a>
                            </div>
                        </div>

                        {{-- User Platform --}}
                        <div x-data="{ open: {{ request()->routeIs('platform.users.*') || request()->routeIs('platform.roles.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="sidebar-link w-full justify-between group {{ request()->routeIs('platform.users.*') || request()->routeIs('platform.roles.*') ? 'active' : '' }}">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    User Platform
                                </span>
                                <svg class="w-4 h-4 transition-transform duration-200 group-hover:text-white" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                                <a href="{{ route('platform.users.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.users.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Admin Platform
                                </a>
                                <a href="{{ route('platform.roles.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.roles.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Role & Permission
                                </a>
                            </div>
                        </div>

                        {{-- Support --}}
                        <div x-data="{ open: {{ request()->routeIs('platform.tickets.*') || request()->routeIs('platform.broadcasts.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="sidebar-link w-full justify-between group {{ request()->routeIs('platform.tickets.*') || request()->routeIs('platform.broadcasts.*') ? 'active' : '' }}">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    Support
                                </span>
                                <svg class="w-4 h-4 transition-transform duration-200 group-hover:text-white" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                                <a href="{{ route('platform.tickets.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.tickets.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Ticket Support
                                </a>
                                <a href="{{ route('platform.broadcasts.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.broadcasts.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Broadcast
                                </a>
                            </div>
                        </div>

                        {{-- System --}}
                        <div x-data="{ open: {{ request()->routeIs('platform.settings.*') || request()->routeIs('platform.api-integrations.*') || request()->routeIs('platform.activity-logs.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="sidebar-link w-full justify-between group {{ request()->routeIs('platform.settings.*') || request()->routeIs('platform.api-integrations.*') || request()->routeIs('platform.activity-logs.*') ? 'active' : '' }}">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    System
                                </span>
                                <svg class="w-4 h-4 transition-transform duration-200 group-hover:text-white" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                                <a href="{{ route('platform.settings.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.settings.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Pengaturan Global
                                </a>
                                <a href="{{ route('platform.api-integrations.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.api-integrations.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Integrasi API
                                </a>
                                <a href="{{ route('platform.attendance.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.attendance.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Absensi
                                </a>
                                <a href="{{ route('platform.activity-logs.index') }}" class="sidebar-sublink {{ request()->routeIs('platform.activity-logs.*') ? 'active' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                    Activity Log
                                </a>
                            </div>
                        </div>
                    @endif
                </nav>

                {{-- User info at bottom --}}
                <div class="px-4 py-4 border-t border-white/10 flex-shrink-0">
                    <div class="flex items-center gap-3 px-2">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm shadow-lg">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                            @if(Auth::user()->email_verified_at)
                                <p class="text-xs text-emerald-400 flex items-center gap-1 mt-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Terverifikasi
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </aside>

            {{-- ===== MAIN CONTENT ===== --}}
            <div class="flex-1 flex flex-col min-w-0">

                {{-- Top bar --}}
                <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 flex-shrink-0">
                    {{-- Mobile menu button --}}
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    {{-- Page title --}}
                    <div class="hidden lg:block">
                        @isset($header)
                            <h1 class="text-lg font-semibold text-gray-900">{{ $header }}</h1>
                        @endisset
                    </div>

                    {{-- Right side --}}
                    <div class="flex items-center gap-3">
                        {{-- Notification bell --}}
                        <button class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 relative transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        {{-- User avatar (desktop) --}}
                        <div class="hidden sm:flex items-center gap-2 pl-3 border-l border-gray-200" x-data="{ profileOpen: false }">
                            <button @click="profileOpen = !profileOpen" class="flex items-center gap-2 p-1 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-xs">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            
                            {{-- Dropdown --}}
                            <div x-show="profileOpen" @click.away="profileOpen = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 top-12 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                                {{-- User Info --}}
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                            @if(Auth::user()->email_verified_at)
                                                <p class="text-xs text-green-600 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Terverifikasi
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Menu Items --}}
                                <div class="py-1">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Profil Saya
                                    </a>
                                    
                                    <a href="{{ route('platform.settings.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-1.756.426-1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-2.573-1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 001.066-2.573c.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Pengaturan
                                    </a>

                                    <div class="border-t border-gray-100 my-1"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4 4m4-4v10m-4 0v10m4-16H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- Page content --}}
                <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
