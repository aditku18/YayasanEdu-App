<nav :class="{'bg-white/95 dark:bg-slate-900/95 backdrop-blur-md shadow-lg': scrolled, 'bg-transparent': !scrolled}" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 border-b border-transparent" :class="{'border-slate-200 dark:border-slate-800': scrolled}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-20">
            {{-- Logo --}}
            <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-primary-500/30 group-hover:scale-110 transition-transform duration-300">
                    S
                </div>
                <span class="font-bold text-xl tracking-tight text-slate-900 dark:text-white hidden sm:block">
                    SIS<span class="text-primary-600">Platform</span>
                </span>
            </a>

            {{-- Desktop Navigation Menu --}}
            <div class="hidden lg:flex items-center gap-1">
                <a href="{{ route('landing') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-primary-600 hover:bg-primary-50 dark:text-slate-300 dark:hover:text-primary-400 dark:hover:bg-primary-900/20 transition-all duration-200">
                    Home
                </a>
                <a href="#fitur" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-primary-600 hover:bg-primary-50 dark:text-slate-300 dark:hover:text-primary-400 dark:hover:bg-primary-900/20 transition-all duration-200">
                    Fitur
                </a>
                <a href="#harga" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-primary-600 hover:bg-primary-50 dark:text-slate-300 dark:hover:text-primary-400 dark:hover:bg-primary-900/20 transition-all duration-200">
                    Harga
                </a>
                <a href="#demo" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-primary-600 hover:bg-primary-50 dark:text-slate-300 dark:hover:text-primary-400 dark:hover:bg-primary-900/20 transition-all duration-200 flex items-center gap-1">
                    Demo
                    <span class="text-xs bg-primary-100 dark:bg-primary-900/50 text-primary-600 dark:text-primary-400 px-1.5 py-0.5 rounded-full">Baru</span>
                </a>
                <a href="#bantuan" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-primary-600 hover:bg-primary-50 dark:text-slate-300 dark:hover:text-primary-400 dark:hover:bg-primary-900/20 transition-all duration-200">
                    Bantuan
                </a>
            </div>

            {{-- Desktop Auth Buttons --}}
            <div class="hidden lg:flex items-center gap-3">
                <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-primary-600 dark:text-slate-300 dark:hover:text-white transition-colors">
                    Masuk
                </a>
                <a href="{{ route('register.foundation', ['host' => request()->getHost()]) }}" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-primary-600 to-purple-600 text-white text-sm font-semibold shadow-lg shadow-primary-500/25 hover:shadow-xl hover:shadow-primary-500/40 hover:-translate-y-0.5 transition-all duration-300">
                    Daftar Yayasan
                </a>
            </div>

            {{-- Mobile Menu Button --}}
            <div class="lg:hidden flex items-center gap-2">
                <a href="{{ route('login') }}" class="p-2 text-slate-600 hover:text-primary-600 dark:text-slate-300 dark:hover:text-white" aria-label="Login">
                    <i data-lucide="log-in" class="w-5 h-5"></i>
                </a>
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white focus:outline-none rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" aria-label="Toggle menu">
                    <i data-lucide="menu" class="w-6 h-6" x-show="!mobileMenuOpen" x-cloak></i>
                    <i data-lucide="x" class="w-6 h-6" x-show="mobileMenuOpen" x-cloak></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen" x-collapse x-cloak class="lg:hidden bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 shadow-xl">
        <div class="px-4 py-4 space-y-2">
            <div class="pb-3 mb-3 border-b border-slate-200 dark:border-slate-800">
                <a href="{{ route('landing') }}" class="flex items-center gap-3 px-3 py-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                        S
                    </div>
                    <span class="font-bold text-slate-900 dark:text-white">SIS Platform</span>
                </a>
            </div>
            
            <a href="{{ route('landing') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base font-medium text-slate-700 hover:bg-primary-50 dark:text-slate-300 dark:hover:bg-primary-900/20 transition">
                <i data-lucide="home" class="w-5 h-5"></i>
                Home
            </a>
            <a href="#fitur" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base font-medium text-slate-700 hover:bg-primary-50 dark:text-slate-300 dark:hover:bg-primary-900/20 transition">
                <i data-lucide="layers" class="w-5 h-5"></i>
                Fitur
            </a>
            <a href="#harga" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base font-medium text-slate-700 hover:bg-primary-50 dark:text-slate-300 dark:hover:bg-primary-900/20 transition">
                <i data-lucide="tag" class="w-5 h-5"></i>
                Harga
            </a>
            <a href="#demo" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base font-medium text-slate-700 hover:bg-primary-50 dark:text-slate-300 dark:hover:bg-primary-900/20 transition">
                <i data-lucide="play-circle" class="w-5 h-5"></i>
                Demo
                <span class="text-xs bg-primary-100 dark:bg-primary-900/50 text-primary-600 dark:text-primary-400 px-2 py-0.5 rounded-full">Baru</span>
            </a>
            <a href="#bantuan" class="flex items-center gap-3 px-3 py-3 rounded-xl text-base font-medium text-slate-700 hover:bg-primary-50 dark:text-slate-300 dark:hover:bg-primary-900/20 transition">
                <i data-lucide="help-circle" class="w-5 h-5"></i>
                Bantuan
            </a>
            
            <div class="pt-4 mt-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                <a href="{{ route('register.foundation', ['host' => request()->getHost()]) }}" class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-primary-600 to-purple-600 text-white font-semibold shadow-lg hover:shadow-xl hover:shadow-primary-500/30 transition">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                    Daftar Yayasan Sekarang
                </a>
            </div>
        </div>
    </div>
</nav>
