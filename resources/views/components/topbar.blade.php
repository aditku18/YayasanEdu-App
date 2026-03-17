<header class="h-20 bg-white border-b border-slate-200 px-8 flex items-center justify-between flex-shrink-0">
    
    <!-- Left: Mobile Menu Toggle & Breadcrumbs -->
    <div class="flex items-center gap-4">
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-lg hover:bg-slate-50 text-slate-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
            </svg>
        </button>
        
        <!-- Search Bar (SaaS Style) -->
        <div class="hidden md:flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 w-80 focus-within:ring-2 focus-within:ring-primary-500/20 focus-within:border-primary-500 transition-all">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" placeholder="Cari data, unit, atau siswa..." 
                   class="bg-transparent border-none text-sm focus:outline-none w-full text-slate-600 placeholder:text-slate-400">
        </div>
    </div>

    <!-- Right: Actions & User -->
    <div class="flex items-center gap-6">
        
        <!-- Trial Badge -->
        @if(isset($trialDaysLeft) && $trialDaysLeft > 0)
            <div class="hidden sm:flex items-center gap-2 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-full">
                <div class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></div>
                <span class="text-xs font-bold text-amber-700">Trial: {{ $trialDaysLeft }} Hari Lagi</span>
            </div>
        @endif

        <!-- Notifications -->
        <button class="relative p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full border-2 border-white"></span>
        </button>

        <!-- User Dropdown -->
        <div class="relative" x-data="{ userOpen: false }">
            <button @click="userOpen = !userOpen" class="flex items-center gap-2 p-1 rounded-xl hover:bg-slate-50 transition-colors">
                <div class="w-9 h-9 bg-primary-100 text-primary-700 rounded-lg flex items-center justify-center font-bold">
                    @auth
                        {{ substr(Auth::user()->name, 0, 1) }}
                    @else
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    @endauth
                </div>
                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="userOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="userOpen" 
                 x-cloak
                 @click.away="userOpen = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="absolute right-0 mt-3 w-56 bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 p-2">
                
                @auth
                <div class="px-3 py-3 border-b border-slate-100 mb-1">
                    <p class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                </div>
                @else
                <div class="px-3 py-3 border-b border-slate-100 mb-1 text-center">
                    <p class="text-sm font-bold text-slate-900">Guest User</p>
                </div>
                @endauth

                <a href="#" class="flex items-center gap-3 px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl transition-colors">
                    Profil Saya
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl transition-colors">
                    Pengaturan
                </a>
                
                <div class="h-px bg-slate-100 my-1"></div>
            </div>
        </div>
    </div>
</header>
