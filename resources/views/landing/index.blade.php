<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Google Analytics 4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-XXXXXXXXXX');
        
        // Track CTA clicks
        function trackCTAClick(ctaName, plan) {
            gtag('event', 'cta_click', {
                'event_category': 'engagement',
                'event_label': ctaName,
                'plan_selected': plan || 'none'
            });
        }
        
        // Track form starts
        function trackFormStart() {
            gtag('event', 'form_start', {
                'event_category': 'conversion',
                'event_label': 'registration_form'
            });
        }
    </script>
    
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', 'XXXXXXXXXX');
    fbq('track', 'PageView');
    </script>
    
    <!-- Microsoft Clarity -->
    <script type="text/javascript">
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window,document,"clarity","script","XXXXXXXXXX");
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YayasanEdu.id - Sistem Informasi Sekolah SaaS Terlengkap</title>
    <meta name="description" content="Platform SIS (Student Information System) multi-tenant untuk pengelolaan pendidikan Indonesia. Mulai trial gratis 14 hari.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        html { scroll-behavior: smooth; }
        .gradient-text {
            background: linear-gradient(135deg, #0ea5e9 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-gradient {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 50%, #7c3aed 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .pattern-dots {
            background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 20px 20px;
        }
        /* Modern Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #8b5cf6 100%);
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(14, 165, 233, 0.4);
            background: linear-gradient(135deg, #0284c7 0%, #7c3aed 100%);
        }
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .btn-primary:hover::before {
            left: 100%;
        }
        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-1px);
        }
        .btn-outline {
            background: transparent;
            border: 2px solid rgba(14, 165, 233, 0.5);
            color: #0ea5e9;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-outline:hover {
            background: rgba(14, 165, 233, 0.1);
            border-color: #0ea5e9;
            color: #0284c7;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.2);
        }
        .btn-cta {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(16, 185, 129, 0.4);
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }
        .btn-cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s;
        }
        .btn-cta:hover::before {
            left: 100%;
        }
        /* Pulse Animation for Important Buttons */
        .btn-pulse {
            animation: pulse-glow 2s ease-in-out infinite alternate;
        }
        @keyframes pulse-glow {
            from { box-shadow: 0 0 20px rgba(14, 165, 233, 0.3); }
            to { box-shadow: 0 0 30px rgba(14, 165, 233, 0.6); }
        }
        /* Navbar Specific Styles */
        .navbar-link {
            position: relative;
            transition: all 0.3s ease;
        }
        .navbar-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background: linear-gradient(135deg, #0ea5e9 0%, #8b5cf6 100%);
            transition: width 0.3s ease;
        }
        .navbar-link:hover::after {
            width: 100%;
        }
        .navbar-link:hover {
            transform: translateY(-1px);
            color: #0ea5e9 !important;
        }
        .logo-container {
            transition: all 0.3s ease;
        }
        .logo-container:hover {
            transform: scale(1.05);
        }
        .mobile-menu-btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .mobile-menu-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(14, 165, 233, 0.1);
            border-radius: 6px;
            transform: scale(0);
            transition: transform 0.3s ease;
        }
        .mobile-menu-btn:hover::before {
            transform: scale(1);
        }
        .mobile-menu-btn:hover svg {
            transform: rotate(90deg);
        }
        .mobile-menu-btn svg {
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="font-sans bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-sm shadow-sm border-b border-gray-100 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="ml-3 text-xl font-bold text-gray-900">YayasanEdu</span>
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-primary-600 font-medium transition">Fitur</a>
                    <a href="#pricing" class="text-gray-600 hover:text-primary-600 font-medium transition">Harga</a>
                    <a href="#testimonials" class="text-gray-600 hover:text-primary-600 font-medium transition">Testimoni</a>
                    <a href="#faq" class="text-gray-600 hover:text-primary-600 font-medium transition">FAQ</a>
                    <a href="/login" class="inline-flex items-center px-5 py-2.5 border-2 border-primary-500 text-primary-600 font-semibold rounded-xl hover:bg-primary-50 transition">
                        Login
                    </a>
                    <a href="/register-foundation/step1" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-primary-500 to-secondary-500 text-white font-semibold rounded-xl hover:opacity-90 transition shadow-md hover:shadow-lg transform hover:scale-105">
                        Daftar Gratis
                    </a>
                </div>
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-600 hover:text-gray-900" aria-label="Buka menu" aria-expanded="false" aria-controls="mobile-menu">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t" role="menu" aria-label="Menu navigasi">
            <div class="px-4 py-3 space-y-2">
                <a href="#features" class="block py-2 text-gray-600">Fitur</a>
                <a href="#pricing" class="block py-2 text-gray-600">Harga</a>
                <a href="#testimonials" class="block py-2 text-gray-600">Testimoni</a>
                <a href="#faq" class="block py-2 text-gray-600">FAQ</a>
                <a href="{{ route('register.foundation.step1') }}" class="block py-2 text-primary-600 font-medium">Daftar Gratis</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 pt-24 pb-20 lg:pt-32 lg:pb-28 pattern-dots">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-16 items-center">
                <div class="lg:col-span-7 text-center lg:text-left">
                    <div class="inline-flex items-center px-4 py-2 bg-white/20 rounded-full text-white text-sm font-medium mb-6">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                        Trial Gratis 14 Hari - Tanpa Kartu Kredit
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight">
                        Kelola Sekolah<br>
                        <span class="text-yellow-300">Lebih Mudah & Modern</span>
                    </h1>
                    <p class="mt-6 text-lg sm:text-xl text-primary-100 max-w-2xl mx-auto lg:mx-0">
                        Platform SIS (School Information System) terlengkap untuk pengelolaan pendidikan Indonesia. 
                        Satu platform untuk semua jenjang: KB/TK, SD, SMP, SMA, SMK, dan MA.
                    </p>
                    <div class="mt-8 sm:flex sm:justify-center lg:justify-start gap-4">
                        <a href="{{ route('register.foundation.step1') }}" onclick="trackCTAClick('hero_primary', 'trial'); fbq('track', 'Lead');" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 bg-white text-primary-600 font-semibold rounded-xl hover:bg-gray-50 transition shadow-xl transform hover:scale-105">
                            Mulai Gratis Sekarang
                            <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                        <a href="#features" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 border-2 border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition mt-3 sm:mt-0">
                            Pelajari Fitur
                        </a>
                    </div>
                    <!-- Trust Signals -->
                    <div class="mt-8 flex flex-wrap items-center justify-center lg:justify-start gap-6 text-sm text-primary-100">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Tanpa kartu kredit</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Cancel anytime</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Dukungan 24/7</span>
                        </div>
                    </div>
                    <div class="mt-10 flex items-center justify-center lg:justify-start gap-8 text-white/80">
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ number_format($stats['foundations']) }}+</div>
                            <div class="text-sm">Sekolah</div>
                        </div>
                        <div class="w-px h-10 bg-white/20"></div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ number_format($stats['students']) }}+</div>
                            <div class="text-sm">Siswa</div>
                        </div>
                        <div class="w-px h-10 bg-white/20"></div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ number_format($stats['users']) }}+</div>
                            <div class="text-sm">Pengguna</div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-5 mt-12 lg:mt-0">
                    <div class="relative">
                        <div class="absolute -inset-4 bg-gradient-to-r from-yellow-400 to-pink-500 rounded-2xl blur-2xl opacity-30"></div>
                        <div class="relative bg-white rounded-2xl shadow-2xl p-6">
                            <div class="space-y-4">
                                <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-semibold text-gray-900">Data Aman & Terenkripsi</div>
                                        <div class="text-sm text-gray-500">Keamanan tingkat enterprise</div>
                                    </div>
                                </div>
                                <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-semibold text-gray-900">Akses Kapan Saja</div>
                                        <div class="text-sm text-gray-500">Cloud-based 24/7</div>
                                    </div>
                                </div>
                                <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-semibold text-gray-900">Mobile First Design</div>
                                        <div class="text-sm text-gray-500">Akses dari HP maupun Laptop</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Wave Divider -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#f9fafb"/>
            </svg>
        </div>
    </div>

    <!-- Trust Badges Section -->
    <div class="py-12 bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto bg-blue-50 rounded-2xl flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">ISO 27001</h3>
                    <p class="text-xs text-gray-500">Sertifikasi Keamanan</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto bg-green-50 rounded-2xl flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">SSL Encrypted</h3>
                    <p class="text-xs text-gray-500">Enkripsi Data 256-bit</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto bg-purple-50 rounded-2xl flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">GDPR Compliant</h3>
                    <p class="text-xs text-gray-500">Perlindungan Data EU</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto bg-orange-50 rounded-2xl flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">99.9% Uptime</h3>
                    <p class="text-xs text-gray-500">Service Level Agreement</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-20 bg-gradient-to-b from-gray-50 via-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">
                    Fitur <span class="gradient-text">Lengkap</span> untuk Pendidikan
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    Semua modul yang Anda butuhkan untuk mengelola sekolah secara digital, efisien, dan transparan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Academic Module -->
                <div class="card-hover bg-white rounded-2xl p-6 shadow-md">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Modul Akademik</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> RPP Digital</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Jurnal Kelas</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> E-Rapor</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Akademik Report</li>
                    </ul>
                </div>

                <!-- CBT Module -->
                <div class="card-hover bg-white rounded-2xl p-6 shadow-md">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">CBT & Ujian</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Bank soal</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Ujian Online</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Auto Grading</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Analisis Hasil</li>
                    </ul>
                </div>

                <!-- Digital Module -->
                <div class="card-hover bg-white rounded-2xl p-6 shadow-md">
                    <div class="w-14 h-14 bg-pink-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Modul Digital</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Absensi Digital</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Dompet Digital</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Kantin Digital</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Pengumuman</li>
                    </ul>
                </div>

                <!-- Financial Module -->
                <div class="card-hover bg-white rounded-2xl p-6 shadow-md">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Modul Keuangan</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Manajemen SPP</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Invoice Otomatis</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Pembayaran Online</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Laporan Keuangan</li>
                    </ul>
                </div>

                <!-- E-Learning Module -->
                <div class="card-hover bg-white rounded-2xl p-6 shadow-md">
                    <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">E-Learning</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Video Pembelajaran</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Materi Interaktif</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Tugas Online</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Forum Diskusi</li>
                    </ul>
                </div>

                <!-- HR Module -->
                <div class="card-hover bg-white rounded-2xl p-6 shadow-md">
                    <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Kepegawaian</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Data Guru/Staf</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Slip Gaji</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Presensi Guru</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Mutasi Pegawai</li>
                    </ul>
                </div>

                <!-- Communication Module -->
                <div class="card-hover bg-white rounded-2xl p-6 shadow-md">
                    <div class="w-14 h-14 bg-cyan-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Komunikasi</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Notifikasi Push</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Email Massal</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> SMS Gateway</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Chat Orang Tua</li>
                    </ul>
                </div>

                <!-- Reports Module -->
                <div class="card-hover bg-white rounded-2xl p-6 shadow-md">
                    <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan & Analitik</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Dashboard Real-time</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Export PDF/Excel</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Grafik & Visualisasi</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Laporan Berkala</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Section -->
    <div id="pricing" class="py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">
                    Pilih <span class="gradient-text">Paket yang Tepat</span>
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    Harga transparan tanpa biaya tersembunyi. Upgrade atau downgrade kapan saja sesuai kebutuhan.
                </p>
                <!-- Billing Toggle -->
                <div class="mt-8 flex items-center justify-center gap-4">
                    <span class="text-gray-600 font-medium" id="monthly-label">Bulanan</span>
                    <button id="billing-toggle" class="relative w-14 h-7 bg-primary-600 rounded-full transition-colors">
                        <span class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full transition-transform"></span>
                    </button>
                    <span class="text-gray-600 font-medium" id="yearly-label">Tahunan <span class="text-green-600 text-sm">(Hemat 20%)</span></span>
                </div>
                <!-- Money Back Guarantee -->
                <div class="mt-6 inline-flex items-center px-6 py-3 bg-green-50 border border-green-200 rounded-full">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="text-green-800 font-medium">30-Day Money-Back Guarantee - Tanpa Syarat</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($plans as $plan)
                    <div class="bg-white border-2 {{ $plan->is_featured ? 'border-primary-500 transform scale-105' : 'border-gray-100' }} rounded-2xl p-8 card-hover relative">
                        @if($plan->is_featured)
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                <span class="bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-4 py-1 rounded-full text-sm font-medium">Most Popular</span>
                            </div>
                        @endif
                        <div class="text-center">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $plan->name }}</h3>
                            <p class="mt-2 text-gray-500 text-sm">{{ $plan->description }}</p>
                            <div class="mt-6">
                                @if($plan->price_per_month > 0)
                                    <span class="text-4xl font-bold text-gray-900" data-monthly="Rp {{ number_format($plan->price_per_month, 0, ',', '.') }}" data-yearly="Rp {{ number_format($plan->price_per_year, 0, ',', '.') }}">Rp {{ number_format($plan->price_per_month, 0, ',', '.') }}</span>
                                    <span class="text-gray-500">/bulan</span>
                                @else
                                    <span class="text-4xl font-bold text-gray-900" data-monthly="Gratis" data-yearly="Gratis">Gratis</span>
                                    <span class="text-gray-500">/bulan</span>
                                @endif
                            </div>
                            @if($plan->price_per_year > 0)
                                <div class="mt-2 text-sm text-gray-400">atau <span data-monthly="Rp {{ number_format($plan->price_per_year, 0, ',', '.') }}/tahun" data-yearly="Rp {{ number_format($plan->price_per_year, 0, ',', '.') }}/tahun">Rp {{ number_format($plan->price_per_year, 0, ',', '.') }}</span>/tahun</div>
                            @endif
                        </div>
                        <ul class="mt-8 space-y-4">
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $plan->max_schools == 99999 ? 'Unlimited Sekolah' : $plan->max_schools . ' Sekolah' }}
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $plan->max_students == 99999 ? 'Unlimited Siswa' : 'Maks. ' . $plan->max_students . ' Siswa' }}
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $plan->max_users == 99999 ? 'Unlimited Guru' : 'Maks. ' . $plan->max_users . ' Guru' }}
                            </li>
                            @if(!empty($plan->features) && is_array($plan->features))
                                @foreach(array_slice($plan->features, 0, 5) as $feature)
                                    <li class="flex items-center text-sm text-gray-600">
                                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        @if($plan->price_per_month == 0)
                            <a href="{{ route('register.foundation.step1', ['host' => request()->getHost()]) }}?plan={{ $plan->slug }}" class="mt-8 block w-full py-3 px-6 bg-gray-100 text-gray-900 font-semibold rounded-xl text-center hover:bg-gray-200 transition">
                                Pilih {{ $plan->name }}
                            </a>
                        @else
                            <a href="{{ route('register.foundation.step1', ['host' => request()->getHost()]) }}?plan={{ $plan->slug }}" onclick="trackCTAClick('pricing_{{ $plan->slug }}', '{{ $plan->slug }}');" class="mt-8 block w-full py-3 px-6 {{ $plan->is_featured ? 'bg-gradient-to-r from-primary-500 to-secondary-500 text-white shadow-lg' : 'bg-primary-600 text-white' }} font-semibold rounded-xl text-center hover:opacity-90 transition">
                                Pilih {{ $plan->name }}
                            </a>
                            <p class="text-xs text-center text-gray-400 mt-3">{{ $plan->is_featured ? 'Paling populer - ' : '' }}14 hari trial gratis</p>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">Tidak ada paket tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Multi-tenant Section -->
    <div class="py-20 bg-gradient-to-b from-white via-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">
                        Arsitektur <span class="gradient-text">Multi-Tenant</span>
                    </h2>
                    <p class="mt-4 text-lg text-gray-600">
                        Satu platform untuk banyak yayasan. Setiap yayasan mendapatkan database terpisah dengan subdomain sendiri, sehingga data lebih aman dan terisolasi.
                    </p>
                    <div class="mt-8 space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900">Database Terpisah</h4>
                                <p class="text-gray-600 text-sm">Setiap yayasan memiliki database sendiri, tidak tercampur dengan yayasan lain.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900">Subdomain Kustom</h4>
                                <p class="text-gray-600 text-sm">Setiap yayasan mendapatkan subdomain sendiri (namayayasan.yayasanedu.id).</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900">Skalabilitas Tinggi</h4>
                                <p class="text-gray-600 text-sm">Tambah sekolah tanpa batasan teknis yang kompleks.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-10 lg:mt-0">
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <div class="space-y-4">
                            @forelse($activeFoundations as $found)
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold">{{ substr($found->name, 0, 1) }}</div>
                                    <div class="ml-3">
                                        <div class="font-semibold text-gray-900">{{ $found->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $found->subdomain }}.{{ request()->getHost() }}</div>
                                    </div>
                                </div>
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">Aktif</span>
                            </div>
                            @empty
                            <div class="p-8 text-center text-gray-500 italic">
                                Bergabunglah dengan yayasan lainnya hari ini.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- School Levels -->
    <div class="py-16 bg-gradient-to-b from-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">
                    Mendukung <span class="gradient-text">Semua Jenjang Pendidikan</span>
                </h2>
                -4 text-gray<p class="mt-600">
                    Dari Kelompok Bermain hingga Sekolah Menengah Kejuruan
                </p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-4">
                @foreach(['KB', 'TK', 'SD', 'SMP', 'SMA', 'SMK', 'MA'] as $level)
                <div class="bg-gradient-to-br from-primary-50 to-secondary-50 rounded-xl p-6 text-center border border-primary-100 card-hover">
                    <span class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">{{ $level }}</span>
                    <p class="text-xs text-gray-500 mt-2">
                        @if($level == 'KB') Kelompok Bermain
                        @elseif($level == 'TK') Taman Kanak-Kanak
                        @elseif($level == 'SD') Sekolah Dasar
                        @elseif($level == 'SMP') Sekolah Menengah Pertama
                        @elseif($level == 'SMA') Sekolah Menengah Atas
                        @elseif($level == 'SMK') Sekolah Menengah Kejuruan
                        @else Madrasah Aliyah
                        @endif
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </div>


    <!-- FAQ Section -->
    <div id="faq" class="py-20 bg-gradient-to-b from-white to-gray-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">
                    Pertanyaan <span class="gradient-text">yang Sering Diajukan</span>
                </h2>
                <p class="mt-4 text-gray-600">
                    Temukan jawaban untuk pertanyaan umum tentang YayasanEdu
                </p>
            </div>

            <div class="space-y-4">
                <!-- FAQ 1 -->
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition" aria-expanded="false" aria-controls="faq1">
                        <span class="font-semibold text-gray-900">Apa itu YayasanEdu?</span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="faq1" class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600">YayasanEdu adalah platform SIS (School Information System) berbasis SaaS (Software as a Service) untuk pengelolaan pendidikan Indonesia. Sistem ini mendukung multi-tenant, memungkinkan banyak yayasan untuk menggunakan satu platform dengan database terpisah.</p>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition" aria-expanded="false" aria-controls="faq2">
                        <span class="font-semibold text-gray-900">Bagaimana cara trial gratis?</span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="faq2" class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600">Cukup klik tombol "Daftar Gratis" dan isi formulir registrasi. Anda akan langsung mendapatkan akses trial 14 hari ke semua fitur paket Pro tanpa perlu kartu kredit.</p>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition" aria-expanded="false" aria-controls="faq3">
                        <span class="font-semibold text-gray-900">Apakah data aman?</span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="faq3" class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600">Tentu! Kami menggunakan enkripsi tingkat enterprise, server dengan standar keamanan tinggi, dan backup otomatis harian. Data Anda tersimpan dengan aman di server cloud Indonesia.</p>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition" aria-expanded="false" aria-controls="faq4">
                        <span class="font-semibold text-gray-900">Bisakah upgrade atau downgrade paket?</span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="faq4" class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600">Ya, Anda dapat upgrade atau downgrade paket kapan saja melalui dashboard admin. Perubahan akan berlaku di bulan berikutnya.</p>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition" aria-expanded="false" aria-controls="faq5">
                        <span class="font-semibold text-gray-900">Apakah termasuk pelatihan dan support?</span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="faq5" class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600">Ya! Kami menyediakan video tutorial, dokumentasi lengkap, dan support via email dan chat. Untuk paket Enterprise, Anda juga mendapat priority support dan training langsung.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-primary-600 to-secondary-600 py-20">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6">
            <h2 class="text-3xl sm:text-4xl font-bold text-white">
                Siap Mengubah Manajemen Sekolah Anda?
            </h2>
            <p class="mt-4 text-xl text-primary-100">
                Mulai trial gratis 14 hari sekarang. Tidak perlu kartu kredit.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register.foundation.step1', ['host' => request()->getHost()]) }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-primary-600 font-semibold rounded-xl hover:bg-gray-50 transition shadow-xl">
                    Daftar Gratis Sekarang
                    <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                <a href="#contact" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition">
                    Hubungi Sales
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <!-- Company -->
                <div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="ml-3 text-xl font-bold">YayasanEdu</span>
                    </div>
                    <p class="mt-4 text-gray-400 text-sm">
                        Platform SIS (School Information System) terlengkap untuk pengelolaan pendidikan Indonesia.
                    </p>
                    <div class="mt-6 flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.757-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Products -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Produk</h3>
                    <ul class="space-y-3">
                        <li><a href="#features" class="text-gray-400 hover:text-white transition">Fitur</a></li>
                        <li><a href="#pricing" class="text-gray-400 hover:text-white transition">Harga</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Demo</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">API Dokumentasi</a></li>
                    </ul>
                </div>

                <!-- Company Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Perusahaan</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Karir</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Blog</a></li>
                        <li><a href="#contact" class="text-gray-400 hover:text-white transition">Kontak</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Legal</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">SLA</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Ketentuan Pembayaran</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-800">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">
                        &copy; {{ date('Y') }} YayasanEdu. All rights reserved.
                    </p>
                    <div class="mt-4 md:mt-0 flex items-center gap-2 text-gray-400 text-sm">
                        <span>Made with</span>
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                        </svg>
                        <span>in Indonesia</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuBtn.addEventListener('click', () => {
            const isExpanded = mobileMenuBtn.getAttribute('aria-expanded') === 'true';
            mobileMenuBtn.setAttribute('aria-expanded', !isExpanded);
            mobileMenu.classList.toggle('hidden');
        });

                <!-- FAQ toggle -->
                document.querySelectorAll('.faq-toggle').forEach(button => {
                    button.addEventListener('click', () => {
                        const content = button.nextElementSibling;
                        const icon = button.querySelector('svg:last-child');
                        const isExpanded = button.getAttribute('aria-expanded') === 'true';
                        
                        button.setAttribute('aria-expanded', !isExpanded);
                        content.classList.toggle('hidden');
                        icon.classList.toggle('rotate-180');
                    });
                });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu if open
                    mobileMenu.classList.add('hidden');
                }
            });
        });

        // Billing toggle - Monthly/Yearly
        const billingToggle = document.getElementById('billing-toggle');
        const toggleKnob = billingToggle.querySelector('span');
        let isYearly = false;
        
        // Price elements
        const priceElements = document.querySelectorAll('[data-monthly]');
        const yearlyLabel = document.getElementById('yearly-label');
        const monthlyLabel = document.getElementById('monthly-label');
        
        billingToggle.addEventListener('click', () => {
            isYearly = !isYearly;
            
            // Animate toggle
            if (isYearly) {
                toggleKnob.style.transform = 'translateX(28px)';
                toggleKnob.classList.add('bg-primary-500');
                toggleKnob.classList.remove('bg-white');
                yearlyLabel.classList.add('text-primary-600', 'font-bold');
                monthlyLabel.classList.remove('text-primary-600', 'font-bold');
            } else {
                toggleKnob.style.transform = 'translateX(0)';
                toggleKnob.classList.remove('bg-primary-500');
                toggleKnob.classList.add('bg-white');
                monthlyLabel.classList.add('text-primary-600', 'font-bold');
                yearlyLabel.classList.remove('text-primary-600', 'font-bold');
            }
            
            // Update prices
            priceElements.forEach(el => {
                const monthlyPrice = el.getAttribute('data-monthly');
                const yearlyPrice = el.getAttribute('data-yearly');
                
                if (isYearly && yearlyPrice) {
                    el.textContent = yearlyPrice;
                } else {
                    el.textContent = monthlyPrice;
                }
            });
        });

        // Set initial state
        monthlyLabel.classList.add('text-primary-600', 'font-bold');
    </script>
</body>
</html>
