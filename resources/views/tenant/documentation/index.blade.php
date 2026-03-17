@extends('layouts.tenant-platform')

@section('title', 'Dokumentasi')

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
    .category-card {
        background: linear-gradient(135deg, var(--bg-color) 0%, var(--bg-color-light) 100%);
        transition: all 0.3s ease;
    }
    .category-card:hover {
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332-.477-4.5-1.253"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">Dokumentasi EduSaaS</h1>
                </div>
                <p class="text-primary-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Temukan panduan lengkap, tutorial, dan referensi untuk memaksimalkan penggunaan platform EduSaaS
                </p>

                <!-- Search Bar -->
                <div class="max-w-md mx-auto mb-8">
                    <form action="{{ route('tenant.documentation.search') }}" method="GET" class="relative">
                        <div class="relative">
                            <input type="text" name="q" placeholder="Cari dokumentasi..."
                                   class="w-full px-4 py-3 pl-12 pr-4 text-slate-900 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 placeholder-slate-300">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">6</p>
                        <p class="text-primary-100 text-sm">Kategori</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">76</p>
                        <p class="text-primary-100 text-sm">Artikel</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">24/7</p>
                        <p class="text-primary-100 text-sm">Support</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Articles -->
    @if(isset($featuredArticles) && count($featuredArticles) > 0)
    <div class="max-w-7xl mx-auto mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Artikel Populer</h2>
            <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">Lihat Semua →</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($featuredArticles as $article)
            <div class="glass-effect rounded-2xl p-6 hover-lift animate-fade-in-up animate-delay-{{ $loop->index + 1 }}">
                <div class="flex items-start justify-between mb-4">
                    <span class="px-3 py-1 bg-primary-100 text-primary-800 text-xs font-medium rounded-full">
                        {{ $article->category }}
                    </span>
                    <span class="text-slate-500 text-sm">{{ $article->read_time }}</span>
                </div>

                <h3 class="text-lg font-bold text-slate-900 mb-3">{{ $article->title }}</h3>
                <p class="text-slate-600 text-sm mb-4">{{ $article->description }}</p>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500">Diperbarui {{ $article->updated_at }}</span>
                    <a href="{{ route('tenant.documentation.article', ['category' => 'getting-started', 'article' => $article->id]) }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm">
                        Baca →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Documentation Categories -->
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-2">Kategori Dokumentasi</h2>
            <p class="text-slate-600">Jelajahi panduan berdasarkan kategori untuk memudahkan pencarian</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('tenant.documentation.category', $category->id) }}" class="block category-card rounded-2xl p-6 text-white animate-slide-in-left animate-delay-{{ $loop->index % 3 + 1 }}"
               style="--bg-color: rgb(var(--color-{{ $category->color }}-500)); --bg-color-light: rgb(var(--color-{{ $category->color }}-400));">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-3xl">{{ $category->icon }}</div>
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                        {{ $category->article_count }} artikel
                    </span>
                </div>

                <h3 class="text-xl font-bold mb-2">{{ $category->name }}</h3>
                <p class="text-white/80 text-sm mb-4">{{ $category->description }}</p>

                @if(isset($category->articles) && count($category->articles) > 0)
                <div class="space-y-1">
                    @foreach(array_slice($category->articles, 0, 3) as $article)
                    <div class="flex items-center text-sm text-white/70">
                        <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $article->title }}
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="mt-4 flex items-center text-sm font-medium">
                    <span>Jelajahi Kategori</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Help Section -->
    <div class="max-w-7xl mx-auto mt-16">
        <div class="glass-effect rounded-3xl p-8 md:p-12 animate-fade-in-up">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Butuh Bantuan Lebih?</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">
                    Jika Anda tidak menemukan jawaban yang dicari, tim support kami siap membantu 24/7
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Email Support</h3>
                    <p class="text-slate-600 text-sm mb-4">Kirim email ke tim support kami</p>
                    <a href="mailto:support@edusaas.com" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            support@edusaas.com
                        </span>
                    </a>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Live Chat</h3>
                    <p class="text-slate-600 text-sm mb-4">Chat langsung dengan support</p>
                    <button class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Mulai Chat
                        </span>
                    </button>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Hotline</h3>
                    <p class="text-slate-600 text-sm mb-4">Hubungi via telepon</p>
                    <a href="tel:+62211234567" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            (021) 1234-5678
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
