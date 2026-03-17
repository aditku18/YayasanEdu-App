<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EduSaaS') }} — Setup Wizard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex">

        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg border-r border-gray-200 flex flex-col">
            <!-- Logo -->
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-200">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Portal Yayasan</h1>
                    <p class="text-xs text-gray-500">{{ tenant('id') ?? 'Tenant' }}</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Setup Wizard</div>

                <div class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-blue-700 rounded-lg bg-blue-50 border-r-2 border-blue-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Setup Awal
                </div>
            </nav>

            <!-- User Menu -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-700">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Setup Awal Sistem</h1>
                        <p class="text-gray-600 mt-1">Mari lengkapi profil sekolah dan data awal Anda</p>
                    </div>
                    <div class="flex items-center gap-4">
                        @if(isset($trialDaysLeft))
                            <div class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium border border-blue-200">
                                Trial: {{ $trialDaysLeft }} hari
                            </div>
                        @endif
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-8">
                <div class="max-w-4xl mx-auto" x-data="{ step: 1 }">
                    <!-- Progress Bar -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-gray-900">Langkah Setup Yayasan</h2>
                            <span class="text-sm text-gray-500" x-text="'Langkah ' + step + ' dari 3'"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <template x-for="i in 3">
                                <div class="flex-1 h-3 rounded-full transition-all duration-300" :class="step >= i ? 'bg-primary-500' : 'bg-gray-200'"></div>
                            </template>
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-gray-500">
                            <span>1. Profil Yayasan</span>
                            <span>2. Unit Sekolah</span>
                            <span>3. Selesai</span>
                        </div>
                    </div>

                    <!-- Wizard Form -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                        <form action="{{ route('tenant.wizard.store') }}" method="POST">
                            @csrf

                            <!-- Step 1: Foundation Info -->
                            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                                <div class="mb-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                            <span class="text-primary-600 font-bold">1</span>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900">Profil Yayasan</h3>
                                    </div>
                                    <p class="text-gray-600">Lengkapi informasi dasar yayasan Anda.</p>
                                </div>
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Yayasan</label>
                                        <input type="text" name="foundation_name" value="{{ $centralFoundation->name ?? '' }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" required>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Telepon Yayasan</label>
                                            <input type="text" name="foundation_phone" value="{{ $centralFoundation->phone ?? '' }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" placeholder="021-xxxxxx">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Yayasan</label>
                                            <input type="email" name="foundation_email" value="{{ $centralFoundation->email ?? '' }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" readonly>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Yayasan</label>
                                        <textarea name="foundation_address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" placeholder="Jl. Raya Nomor..."></textarea>
                                    </div>
                                </div>
                                <div class="flex justify-end pt-6">
                                    <button type="button" @click="step = 2" class="px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl shadow-sm transition-colors">
                                        Lanjut ke Unit Sekolah →
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: School Setup -->
                            <div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                                <div class="mb-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                            <span class="text-primary-600 font-bold">2</span>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900">Unit Sekolah Pertama</h3>
                                    </div>
                                    <p class="text-gray-600">Masukkan nama unit sekolah pertama yang akan dikelola. Anda dapat menambah unit lainnya nanti di dashboard.</p>
                                </div>
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Unit Sekolah</label>
                                        <input type="text" name="school_name" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" placeholder="Contoh: SMA IT Bina Bangsa" required>
                                    </div>
                                </div>
                                <div class="flex justify-between pt-6">
                                    <button type="button" @click="step = 1" class="px-6 py-3 text-gray-600 hover:text-gray-800 font-medium rounded-xl border border-gray-300 hover:bg-gray-50 transition-colors">
                                        ← Kembali
                                    </button>
                                    <button type="button" @click="step = 3" class="px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl shadow-sm transition-colors">
                                        Lanjut ke Konfirmasi →
                                    </button>
                                </div>
                            </div>

                            <!-- Step 3: Finish -->
                            <div x-show="step === 3" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                                <div class="text-center mb-8">
                                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Hampir Selesai!</h3>
                                    <p class="text-gray-600">Klik tombol di bawah untuk menyelesaikan setup yayasan dan mulai mengelola unit sekolah Anda.</p>
                                </div>
                                <div class="flex justify-between pt-6">
                                    <button type="button" @click="step = 2" class="px-6 py-3 text-gray-600 hover:text-gray-800 font-medium rounded-xl border border-gray-300 hover:bg-gray-50 transition-colors">
                                        ← Kembali
                                    </button>
                                    <button type="submit" class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl shadow-sm transition-colors flex items-center gap-2">
                                        Selesaikan Setup & Masuk Dashboard
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
