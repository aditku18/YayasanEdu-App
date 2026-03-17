<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EduSaaS') }} — @yield('title', $header ?? 'Dashboard')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: {
                                50: '#eef2ff',
                                100: '#e0e7ff',
                                200: '#c7d2fe',
                                300: '#a5b4fc',
                                400: '#818cf8',
                                500: '#6366f1',
                                600: '#4f46e5',
                                700: '#4338ca',
                                800: '#3730a3',
                                900: '#312e81',
                                950: '#1e1b4b',
                            },
                        }
                    }
                }
            }
        </script>
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        
        <style>
            body { font-family: 'Inter', sans-serif; }
            
            .bg-sidebar {
                background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            }
            
            .sidebar-link {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.625rem 1rem;
                font-size: 0.875rem;
                font-weight: 500;
                color: #94a3b8;
                border-radius: 0.5rem;
                transition: all 0.2s ease;
                text-decoration: none;
            }
            
            .sidebar-link:hover {
                background: rgba(255, 255, 255, 0.08);
                color: #e2e8f0;
            }
            
            .sidebar-link.active {
                background: rgba(99, 102, 241, 0.2);
                color: #a5b4fc;
            }
            
            .sidebar-link-sub {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                font-size: 0.8125rem;
                font-weight: 500;
                color: #64748b;
                border-radius: 0.375rem;
                transition: all 0.2s ease;
                text-decoration: none;
                margin-left: 0.5rem;
            }
            
            .sidebar-link-sub:hover {
                background: rgba(255, 255, 255, 0.05);
                color: #e2e8f0;
            }
            
            .sidebar-link-sub.active {
                background: rgba(99, 102, 241, 0.15);
                color: #a5b4fc;
            }
            
            .sidebar-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 32px;
                height: 32px;
                border-radius: 0.5rem;
                background: rgba(255, 255, 255, 0.08);
                flex-shrink: 0;
            }
            
            .sidebar-icon svg {
                width: 18px;
                height: 18px;
                color: #94a3b8;
            }
            
            .sidebar-link:hover .sidebar-icon,
            .sidebar-link.active .sidebar-icon {
                background: rgba(99, 102, 241, 0.2);
            }
            
            .sidebar-link:hover .sidebar-icon svg,
            .sidebar-link.active .sidebar-icon svg {
                color: #a5b4fc;
            }
            
            .sidebar-section-title {
                padding: 1rem 1rem 0.5rem;
                font-size: 0.7rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #475569;
            }
            
            .submenu-enter {
                display: none;
            }
            
            .submenu-enter-active {
                display: block;
            }
        </style>
    </head>
    <body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen" x-data="{ 
        sidebarOpen: false,
        menuYayasan: {{ request()->routeIs('tenant.yayasan.*') ? 'true' : 'false' }},
        menuSekolah: {{ request()->routeIs('tenant.school.*') || request()->routeIs('tenant.units.*') ? 'true' : 'false' }},
        menuLangganan: {{ request()->routeIs('tenant.subscription.*') ? 'true' : 'false' }},
        menuPlugin: {{ request()->routeIs('tenant.plugin.*') || request()->routeIs('tenant.marketplace.*') ? 'true' : 'false' }},
        menuPengguna: {{ request()->routeIs('tenant.user.*') ? 'true' : 'false' }},
        menuIntegrasi: {{ request()->routeIs('tenant.integration.*') ? 'true' : 'false' }},
        menuKeuangan: {{ request()->routeIs('tenant.finance.*') ? 'true' : 'false' }},
        menuAnalitik: {{ request()->routeIs('tenant.analytics.*') || request()->routeIs('tenant.report.*') ? 'true' : 'false' }},
        menuSupport: {{ request()->routeIs('tenant.support.*') ? 'true' : 'false' }},
        menuPengaturan: {{ request()->routeIs('tenant.setting.*') ? 'true' : 'false' }}
    }">
        <div class="flex h-screen overflow-hidden">

            {{-- ===== SIDEBAR ===== --}}
            {{-- Mobile overlay --}}
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 z-40 lg:hidden" @click="sidebarOpen = false"></div>

            {{-- Sidebar panel --}}
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 bg-sidebar transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col">

                {{-- Logo --}}
                <div class="flex items-center gap-3 px-5 h-14 border-b border-white/10 flex-shrink-0">
                    <div class="w-8 h-8 bg-gradient-to-br from-primary-400 to-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-white tracking-tight">Portal Yayasan</span>
                    </div>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                    
                    {{-- ===== OVERVIEW ===== --}}
                    <p class="sidebar-section-title">Overview</p>

                    <a href="{{ route('tenant.dashboard') }}" class="sidebar-link {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
                        <div class="flex items-center gap-3">
                            <div class="sidebar-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <span>Dashboard</span>
                        </div>
                    </a>

                    {{-- ===== MANAJEMEN YAYASAN ===== --}}
                    <p class="sidebar-section-title">Manajemen Yayasan</p>

                    <div x-data="{ open: menuYayasan }">
                        <button @click="open = !open" class="sidebar-link w-full justify-between" :class="open ? 'active' : ''">
                            <div class="flex items-center gap-3">
                                <div class="sidebar-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <span>Profil Yayasan</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                            <a href="{{ route('tenant.yayasan.profil') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.yayasan.profil') ? 'active' : '' }}">
                                <span>Profil</span>
                            </a>
                            <a href="{{ route('tenant.yayasan.legalitas') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.yayasan.legalitas') ? 'active' : '' }}">
                                <span>Dokumen Legal</span>
                            </a>
                            <a href="{{ route('tenant.yayasan.branding') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.yayasan.branding') ? 'active' : '' }}">
                                <span>Branding</span>
                            </a>
                            <a href="{{ route('tenant.yayasan.domain') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.yayasan.domain') ? 'active' : '' }}">
                                <span>Domain</span>
                            </a>
                        </div>
                    </div>

                    {{-- ===== MANAJEMEN SEKOLAH ===== --}}
                    <p class="sidebar-section-title">Manajemen Sekolah</p>

                    <div x-data="{ open: menuSekolah }">
                        <button @click="open = !open" class="sidebar-link w-full justify-between" :class="open ? 'active' : ''">
                            <div class="flex items-center gap-3">
                                <div class="sidebar-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <span>Sekolah</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                            <a href="{{ route('tenant.units.index') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.units.index') ? 'active' : '' }}">
                                <span>Daftar Sekolah</span>
                            </a>
                            <a href="{{ route('tenant.units.create') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.units.create') ? 'active' : '' }}">
                                <span>Tambah Sekolah</span>
                            </a>
                        </div>
                    </div>

                    {{-- ===== LANGGANAN ===== --}}
                    <p class="sidebar-section-title">Langganan</p>

                    <div x-data="{ open: menuLangganan }">
                        <button @click="open = !open" class="sidebar-link w-full justify-between" :class="open ? 'active' : ''">
                            <div class="flex items-center gap-3">
                                <div class="sidebar-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                    </svg>
                                </div>
                                <span>Langganan</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                            <a href="{{ route('tenant.subscription.current') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.subscription.current') ? 'active' : '' }}">
                                <span>Paket Aktif</span>
                            </a>
                            <a href="{{ route('tenant.plan.upgrade') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.plan.upgrade') ? 'active' : '' }}">
                                <span>Upgrade</span>
                            </a>
                            <a href="{{ route('tenant.addon.index') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.addon.*') ? 'active' : '' }}">
                                <span>Add-on</span>
                            </a>
                            <a href="{{ route('tenant.invoice.index') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.invoice.*') ? 'active' : '' }}">
                                <span>Invoice</span>
                            </a>
                            <a href="{{ route('tenant.payment.history') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.payment.history') ? 'active' : '' }}">
                                <span>Riwayat Pembayaran</span>
                            </a>
                        </div>
                    </div>

                    {{-- ===== PLUGIN & MARKETPLACE ===== --}}
                    <p class="sidebar-section-title">Plugin & Marketplace</p>

                    <div x-data="{ open: menuPlugin }">
                        <button @click="open = !open" class="sidebar-link w-full justify-between" :class="open ? 'active' : ''">
                            <div class="flex items-center gap-3">
                                <div class="sidebar-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                </div>
                                <span>Plugin</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                            <a href="{{ route('tenant.marketplace.index') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.marketplace.*') ? 'active' : '' }}">
                                <span>Marketplace</span>
                            </a>
                            <a href="{{ route('tenant.plugin.active') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.plugin.active') ? 'active' : '' }}">
                                <span>Plugin Aktif</span>
                            </a>
                            <a href="{{ route('tenant.plugin.installed') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.plugin.installed') ? 'active' : '' }}">
                                <span>Plugin Terpasang</span>
                            </a>
                            <a href="{{ route('tenant.plugin.purchase') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.plugin.purchase') ? 'active' : '' }}">
                                <span>Pembelian</span>
                            </a>
                        </div>
                    </div>

                    {{-- ===== MANAJEMEN PENGGUNA ===== --}}
                    <p class="sidebar-section-title">Manajemen Pengguna</p>

                    <div x-data="{ open: menuPengguna }">
                        <button @click="open = !open" class="sidebar-link w-full justify-between" :class="open ? 'active' : ''">
                            <div class="flex items-center gap-3">
                                <div class="sidebar-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <span>Pengguna</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                            <a href="{{ route('tenant.user.admin-yayasan') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.user.admin-yayasan') ? 'active' : '' }}">
                                <span>Admin Yayasan</span>
                            </a>
                            <a href="{{ route('tenant.user.admin-sekolah') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.user.admin-sekolah') ? 'active' : '' }}">
                                <span>Admin Sekolah</span>
                            </a>
                            <a href="{{ route('tenant.roles.index') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.roles.*') ? 'active' : '' }}">
                                <span>Role Permission</span>
                            </a>
                            <a href="{{ route('tenant.activity.log') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.activity.*') ? 'active' : '' }}">
                                <span>Activity Log</span>
                            </a>
                        </div>
                    </div>

                    {{-- ===== INTEGRASI ===== --}}
                    <p class="sidebar-section-title">Integrasi</p>

                    <div x-data="{ open: menuIntegrasi }">
                        <button @click="open = !open" class="sidebar-link w-full justify-between" :class="open ? 'active' : ''">
                            <div class="flex items-center gap-3">
                                <div class="sidebar-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                                    </svg>
                                </div>
                                <span>Integrasi</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                            <a href="{{ route('tenant.integration.api') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.integration.api') ? 'active' : '' }}">
                                <span>API Key</span>
                            </a>
                            <a href="{{ route('tenant.integration.whatsapp') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.integration.whatsapp') ? 'active' : '' }}">
                                <span>WhatsApp Gateway</span>
                            </a>
                            <a href="{{ route('tenant.integration.absensi') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.integration.absensi') ? 'active' : '' }}">
                                <span>Absensi Device</span>
                            </a>
                            <a href="{{ route('tenant.integration.google') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.integration.google') ? 'active' : '' }}">
                                <span>Google Workspace</span>
                            </a>
                            <a href="{{ route('tenant.integration.payment') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.integration.payment') ? 'active' : '' }}">
                                <span>Payment Gateway</span>
                            </a>
                        </div>
                    </div>

                    {{-- ===== KEUANGAN ===== --}}
                    <p class="sidebar-section-title">Keuangan</p>

                    <div x-data="{ open: menuKeuangan }">
                        <button @click="open = !open" class="sidebar-link w-full justify-between" :class="open ? 'active' : ''">
                            <div class="flex items-center gap-3">
                                <div class="sidebar-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span>Keuangan</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                            <a href="{{ route('tenant.bill.index') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.bill.*') ? 'active' : '' }}">
                                <span>Tagihan (Bills)</span>
                            </a>
                            <a href="{{ route('tenant.invoice.index') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.invoice.*') ? 'active' : '' }}">
                                <span>Invoice (Penjualan)</span>
                            </a>
                            <a href="{{ route('tenant.payment.index') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.payment.index') ? 'active' : '' }}">
                                <span>Pembayaran</span>
                            </a>
                            <a href="{{ route('tenant.finance.report') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.finance.report') ? 'active' : '' }}">
                                <span>Laporan</span>
                            </a>
                        </div>
                    </div>

                    {{-- ===== ANALITIK ===== --}}
                    <p class="sidebar-section-title">Analitik</p>

                    <div x-data="{ open: menuAnalitik }">
                        <button @click="open = !open" class="sidebar-link w-full justify-between" :class="open ? 'active' : ''">
                            <div class="flex items-center gap-3">
                                <div class="sidebar-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <span>Analitik</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                            <a href="{{ route('tenant.analytics.usage') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.analytics.usage') ? 'active' : '' }}">
                                <span>Statistik Penggunaan</span>
                            </a>
                            <a href="{{ route('tenant.report.school') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.report.school') ? 'active' : '' }}">
                                <span>Laporan Sekolah</span>
                            </a>
                            <a href="{{ route('tenant.report.system') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.report.system') ? 'active' : '' }}">
                                <span>Laporan Sistem</span>
                            </a>
                        </div>
                    </div>

                    {{-- ===== SUPPORT ===== --}}
                    <p class="sidebar-section-title">Support</p>

                    <div x-data="{ open: menuSupport }">
                        <button @click="open = !open" class="sidebar-link w-full justify-between" :class="open ? 'active' : ''">
                            <div class="flex items-center gap-3">
                                <div class="sidebar-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 109.75 9.75A9.75 9.75 0 0012 2.25z"/>
                                    </svg>
                                </div>
                                <span>Support</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                            <a href="{{ route('tenant.support.index') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.support.*') ? 'active' : '' }}">
                                <span>Tiket</span>
                            </a>
                            <a href="{{ route('tenant.documentation.index') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.documentation.*') ? 'active' : '' }}">
                                <span>Dokumentasi</span>
                            </a>
                            <a href="{{ route('tenant.contact.index') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.contact.*') ? 'active' : '' }}">
                                <span>Kontak</span>
                            </a>
                        </div>
                    </div>

                    {{-- ===== PENGATURAN SISTEM ===== --}}
                    <p class="sidebar-section-title">Pengaturan Sistem</p>

                    <div x-data="{ open: menuPengaturan }">
                        <button @click="open = !open" class="sidebar-link w-full justify-between" :class="open ? 'active' : ''">
                            <div class="flex items-center gap-3">
                                <div class="sidebar-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <span>Pengaturan</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                            <a href="{{ route('tenant.setting.notification') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.setting.notification') ? 'active' : '' }}">
                                <span>Notifikasi</span>
                            </a>
                            <a href="{{ route('tenant.setting.security') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.setting.security') ? 'active' : '' }}">
                                <span>Keamanan</span>
                            </a>
                            <a href="{{ route('tenant.setting.backup') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.setting.backup') ? 'active' : '' }}">
                                <span>Backup</span>
                            </a>
                            <a href="{{ route('tenant.audit.index') }}" class="sidebar-link-sub {{ request()->routeIs('tenant.audit.*') ? 'active' : '' }}">
                                <span>Audit Log</span>
                            </a>
                        </div>
                    </div>

                </nav>

                {{-- User Menu --}}
                <div class="p-3 border-t border-white/10">
                    <div class="flex items-center gap-3 px-2 py-2">
                        <div class="w-8 h-8 bg-blue-500/20 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-blue-400">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email ?? 'user@example.com' }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-1.5 text-slate-400 hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            {{-- ===== MAIN CONTENT ===== --}}
            <div class="flex-1 flex flex-col overflow-hidden">
                {{-- Top bar --}}
                <header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <h1 class="text-lg font-semibold text-gray-900">{{ $header ?? 'Dashboard' }}</h1>

                    <div class="flex items-center gap-3">
                        @if(isset($trialDaysLeft))
                            <div class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium">
                                Trial: {{ $trialDaysLeft }} hari
                            </div>
                        @endif
                    </div>
                </header>

                {{-- Page content --}}
                <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                    @yield('content')
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
