<!-- Sidebar Backdrop (Mobile Only) -->
<?php

$schoolSlug = getSchoolSlug();
?>

<div x-show="mobileMenuOpen" 
     x-cloak
     @click="mobileMenuOpen = false"
     class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 lg:hidden transition-opacity"></div>

<!-- Sidebar Container -->
<aside :class="sidebarOpen ? 'w-72' : 'w-20'"
       x-show="mobileMenuOpen || true"
       class="fixed inset-y-0 left-0 bg-white border-r border-slate-200 z-50 flex flex-col transition-all duration-300 transform lg:static lg:translate-x-0"
       :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

    <!-- Logo Section -->
    <div class="h-20 flex items-center justify-between px-6 border-b border-slate-100 flex-shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/30 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="font-bold text-xl tracking-tight text-slate-900 whitespace-nowrap overflow-hidden transition-all"
                  :class="sidebarOpen ? 'w-auto opacity-100' : 'w-0 opacity-0'">
                Edu<span class="text-primary-600">SIS</span>
            </span>
        </div>
        <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:flex p-1.5 rounded-lg hover:bg-slate-50 text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-5 h-5 transition-transform duration-300" :class="sidebarOpen ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-6 sidebar-scroll">
        
        <!-- Dashboard -->
        <div class="space-y-1">
            <a href="{{ $schoolSlug ? '/' . $schoolSlug . '/dashboard' : '/dashboard' }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group {{ request()->routeIs('tenant.dashboard*') ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span x-show="sidebarOpen" class="font-medium whitespace-nowrap">Dashboard</span>
            </a>
            
            <!-- Profil Unit -->
            <a href="{{ $schoolSlug ? route('tenant.school.profile', ['school' => $schoolSlug]) : '#' }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group {{ request()->routeIs('tenant.school.profile') ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span x-show="sidebarOpen" class="font-medium whitespace-nowrap">Profil Unit</span>
            </a>
        </div>

        @hasrole('foundation_admin')
        <!-- Yayasan Section -->
        <div>
            <div x-show="sidebarOpen" class="px-2 mb-2 text-[10px] uppercase font-bold text-slate-400 tracking-[0.1em]">Yayasan</div>
            <div x-data="{ open: {{ request()->is('profil*') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl transition-all text-slate-600 hover:bg-slate-50">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span x-show="sidebarOpen" class="font-medium text-sm">Profil Yayasan</span>
                    </div>
                    <svg x-show="sidebarOpen" class="w-4 h-4 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div x-show="open && sidebarOpen" class="ml-8 border-l-2 border-slate-100 space-y-1">
                    <a href="{{ $schoolSlug ? route('tenant.school.yayasan.profil', ['school' => $schoolSlug]) : route('tenant.yayasan.profil') }}" class="block px-4 py-1.5 text-xs text-slate-500 hover:text-primary-600">Info Profil</a>
                    <a href="{{ $schoolSlug ? route('tenant.school.yayasan.legalitas', ['school' => $schoolSlug]) : route('tenant.yayasan.legalitas') }}" class="block px-4 py-1.5 text-xs text-slate-500 hover:text-primary-600">Legalitas</a>
                    <a href="{{ $schoolSlug ? route('tenant.school.yayasan.struktur', ['school' => $schoolSlug]) : route('tenant.yayasan.struktur') }}" class="block px-4 py-1.5 text-xs text-slate-500 hover:text-primary-600">Struktur</a>
                </div>
            </div>
        </div>

        <!-- Unit Sekolah -->
        <div>
            <div x-show="sidebarOpen" class="px-2 mb-2 text-[10px] uppercase font-bold text-slate-400 tracking-[0.1em]">Unit Sekolah</div>
            <div x-data="{ open: false }" class="space-y-1">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl transition-all text-slate-600 hover:bg-slate-50">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                        <span x-show="sidebarOpen" class="font-medium text-sm">Manajemen Unit</span>
                    </div>
                </button>
                <div x-show="open && sidebarOpen" class="ml-8 border-l-2 border-slate-100 space-y-1">
                    <a href="{{ route('tenant.units.index') }}" class="block px-4 py-1.5 text-xs text-slate-500 hover:text-primary-600">Daftar Unit</a>
                    <a href="{{ route('tenant.units.create') }}" class="block px-4 py-1.5 text-xs text-slate-500 hover:text-primary-600">Tambah Unit</a>
                </div>
            </div>
        </div>
        @endhasrole

        @hasrole('school_admin')
        <!-- Menu Operasional Unit -->
        <div>
            <div x-show="sidebarOpen" class="px-2 mb-2 text-[10px] uppercase font-bold text-slate-400 tracking-[0.1em]">Operasional Unit</div>
            <div class="space-y-1">
                @if($schoolSlug)
                <a href="/{{ $schoolSlug }}/students" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Data Siswa</span>
                </a>
                <a href="/{{ $schoolSlug }}/teachers" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Data Guru</span>
                </a>
                <a href="/{{ $schoolSlug }}/classrooms" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Rombel / Kelas</span>
                </a>
                <a href="/{{ $schoolSlug }}/attendance" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Presensi Siswa</span>
                </a>
                <a href="/{{ $schoolSlug }}/ppdb" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Penerimaan Siswa (PPDB)</span>
                </a>
                
                <!-- Penilaian -->
                <div x-data="{ open: {{ request()->is('*penilaian*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span x-show="sidebarOpen" class="font-medium text-sm">Penilaian</span>
                        <svg x-show="sidebarOpen" class="w-4 h-4 ml-auto transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <div x-show="open && sidebarOpen" class="ml-8 border-l-2 border-slate-100 space-y-1">
                        <a href="{{ route('tenant.school.grades.index', ['school' => $schoolSlug]) }}" class="block px-4 py-1.5 text-xs text-slate-500 hover:text-primary-600">Dashboard</a>
                        <a href="{{ route('tenant.school.grades.rekap', ['school' => $schoolSlug]) }}" class="block px-4 py-1.5 text-xs text-slate-500 hover:text-primary-600">Rekap Nilai</a>
                        <a href="{{ route('tenant.school.grades.analisis', ['school' => $schoolSlug]) }}" class="block px-4 py-1.5 text-xs text-slate-500 hover:text-primary-600">Analisis</a>
                        <a href="{{ route('tenant.school.grades.sikap.index', ['school' => $schoolSlug]) }}" class="block px-4 py-1.5 text-xs text-slate-500 hover:text-primary-600">Penilaian Sikap</a>
                        <a href="{{ route('tenant.school.grades.raport', ['school' => $schoolSlug]) }}" class="block px-4 py-1.5 text-xs text-slate-500 hover:text-primary-600">Raport</a>
                        <a href="{{ route('tenant.school.grades.import-form', ['school' => $schoolSlug]) }}" class="block px-4 py-1.5 text-xs text-slate-500 hover:text-primary-600">Import/Export</a>
                    </div>
                </div>
                
                @else
                <a href="{{ $schoolSlug ? '/' . $schoolSlug . '/students' : '/school/students' }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Data Siswa</span>
                </a>
                @endif
            </div>
        </div>

        <!-- Akademik Unit -->
        <div>
            <div x-show="sidebarOpen" class="px-2 mb-2 text-[10px] uppercase font-bold text-slate-400 tracking-[0.1em]">Akademik & Kurikulum</div>
            <div class="space-y-1">
                @if(Auth::user()->school_unit_id)
                <a href="{{ $schoolSlug ? '/' . $schoolSlug . '/classrooms' : '/school/classrooms' }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Rombel / Kelas</span>
                </a>
                <a href="{{ $schoolSlug ? '/' . $schoolSlug . '/subjects' : '/school/subjects' }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Mata Pelajaran</span>
                </a>
                <a href="{{ $schoolSlug ? '/' . $schoolSlug . '/schedule' : '/school/schedule' }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Jadwal Pelajaran</span>
                </a>
                @endif
            </div>
        </div>
        @endhasrole

        @hasrole('foundation_admin')
        <!-- SDM & Siswa -->
        <div>
            <div x-show="sidebarOpen" class="px-2 mb-2 text-[10px] uppercase font-bold text-slate-400 tracking-[0.1em]">SDM & Siswa (Global)</div>
            <div class="space-y-1">
                <a href="{{ route('tenant.teachers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Data Guru Global</span>
                </a>
                <a href="{{ route('tenant.students.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Data Siswa Global</span>
                </a>
            </div>
        </div>

        <!-- Laporan & Monitoring -->
        <div>
            <div x-show="sidebarOpen" class="px-2 mb-2 text-[10px] uppercase font-bold text-slate-400 tracking-[0.1em]">Laporan & Log</div>
            <div class="space-y-1">
                <a href="{{ route('tenant.reports.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Laporan Yayasan</span>
                </a>
                <a href="{{ route('tenant.monitoring.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Monitoring Unit</span>
                </a>
            </div>
        </div>
        @endhasrole

        <!-- Pengguna & Billing -->
        <div>
            <div x-show="sidebarOpen" class="px-2 mb-2 text-[10px] uppercase font-bold text-slate-400 tracking-[0.1em]">Sistem & Akun</div>
            <div class="space-y-1">
                <a href="{{ $schoolSlug ? '/' . $schoolSlug . '/staff' : '/school/staff' }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Daftar Staff Unit</span>
                </a>
                <a href="{{ $schoolSlug ? '/' . $schoolSlug . '/finance' : '/school/finance' }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Keuangan Unit</span>
                </a>
                @hasrole('foundation_admin')
                <a href="{{ route('tenant.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Users Global</span>
                </a>
                <a href="{{ route('tenant.billing.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Billing Yayasan</span>
                </a>
                <a href="{{ $schoolSlug ? route('tenant.school.yayasan.profil', ['school' => $schoolSlug]) : route('tenant.yayasan.profil') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Pengaturan Yayasan</span>
                </a>
                <a href="{{ route('tenant.monitoring.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Monitoring Unit</span>
                </a>
                @endhasrole

                @hasrole('school_admin')
                @if(Auth::user()->school_unit_id)
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span x-show="sidebarOpen" class="font-medium text-sm">Akun Saya</span>
                </a>
                @endif
                @endhasrole
            </div>
        </div>
    </nav>
    
    <!-- Footer Profile (Always Minimized when closed) -->
    <div class="px-6 py-4 border-t border-slate-100">
        <div class="flex items-center gap-3">
            @auth
            <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-600 flex-shrink-0">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div x-show="sidebarOpen" class="overflow-hidden">
                <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-slate-400 font-medium truncate uppercase tracking-widest">{{ Auth::user()->roles->first()?->name ?? 'Administrator' }}</p>
            </div>
            @else
            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div x-show="sidebarOpen">
                <p class="text-sm font-bold text-slate-900">Guest</p>
            </div>
            @endauth
        </div>
    </div>
</aside>
