<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'PPDB') — Portal Penerimaan Siswa Baru</title>

    <!-- Modern Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind Config & Alpine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f7ff', 100: '#e0effe', 200: '#bae0fd', 300: '#7cc7fc',
                            400: '#38a9f8', 500: '#0e89e9', 600: '#026bc7', 700: '#0355a1',
                            800: '#074885', 900: '#0c3a6e',
                        },
                        accent: {
                            50: '#fff7ed', 100: '#ffedd5', 200: '#fed7aa', 300: '#fdba74',
                            400: '#fb923c', 500: '#f97316', 600: '#ea580c', 700: '#c2410c',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        
        body {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(14, 137, 233, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(249, 115, 22, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(14, 137, 233, 0.05) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(249, 115, 22, 0.03) 0px, transparent 50%);
            background-attachment: fixed;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px) saturate(180%);
            -webkit-backdrop-filter: blur(15px) saturate(180%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .premium-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.035);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .bg-grid {
            background-size: 30px 30px;
            background-image: linear-gradient(to right, rgba(0,0,0,0.02) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(0,0,0,0.02) 1px, transparent 1px);
        }

        .gradient-text {
            background: linear-gradient(135deg, #0e89e9 0%, #026bc7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="min-h-screen font-sans antialiased text-slate-800 bg-grid">
    
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 glass-nav no-print">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-4 group cursor-pointer">
                <div class="w-11 h-11 bg-gradient-to-tr from-primary-600 to-primary-400 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/20 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <span class="font-extrabold text-xl tracking-tight text-slate-900 block leading-tight">SIS EDU</span>
                    <span class="text-[10px] font-bold text-primary-600 uppercase tracking-[0.2em]">Pendaftaran PPDB</span>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('tenant.ppdb.public.index') }}" class="text-sm font-bold text-slate-600 hover:text-primary-600 transition-colors relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-primary-500 hover:after:w-full after:transition-all">Beranda</a>
                <a href="{{ route('tenant.ppdb.public.check-status') }}" class="text-sm font-bold text-slate-600 hover:text-primary-600 transition-colors relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-primary-500 hover:after:w-full after:transition-all">Cek Status</a>
                <a href="#" class="text-sm font-bold text-slate-600 hover:text-primary-600 transition-colors relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-primary-500 hover:after:w-full after:transition-all">Pusat Bantuan</a>
                <a href="{{ route('login') }}" class="px-6 py-2.5 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 hover:shadow-xl hover:shadow-slate-900/10 transition-all text-sm">Portal Admin</a>
            </div>

            <!-- Mobile Menu Btn -->
            <button class="md:hidden w-10 h-10 flex items-center justify-center text-slate-600 bg-white rounded-xl border border-slate-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </nav>

    <main class="py-16">
        <div class="max-w-7xl mx-auto px-6">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-16 bg-white border-t border-slate-100 no-print">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">EduSIS Management System</p>
                </div>
                <p class="text-slate-400 text-sm">© {{ date('Y') }} Hak Cipta Dilindungi. Dikembangkan oleh Tim IT Yayasan.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
