<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EduSaaS') }} — Setup Akademik Unit</title>

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
    <div class="min-h-screen flex flex-col">
        <!-- Top Navbar -->
        <nav class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('tenant.dashboard') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $school->name }}</h1>
                    <p class="text-xs text-gray-500">Setup Akademik & Konfigurasi Unit</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-gray-600">{{ Auth::user()->name }}</span>
                <div class="w-8 h-8 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </nav>

        <main class="flex-1 p-8">
            <div class="max-w-6xl mx-auto space-y-8">
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-primary-600 to-indigo-700 rounded-2xl p-8 text-white shadow-lg">
                    <h2 class="text-3xl font-bold mb-2">Setup Akademik Unit</h2>
                    <p class="text-primary-100 opacity-90">Lengkapi data akademik untuk mulai menjalankan operasional sekolah di unit ini.</p>
                </div>

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Setup Steps -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Step 1: Academic Year -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center font-bold">1</div>
                            <h3 class="font-bold text-gray-900">Tahun Ajaran</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <p class="text-gray-600 text-sm">Tentukan tahun ajaran aktif dan semester yang berlaku saat ini.</p>
                            <a href="#" class="block w-full text-center py-2 px-4 bg-primary-50 text-primary-600 font-semibold rounded-lg hover:bg-primary-100 transition-colors">
                                Atur Tahun Ajaran
                            </a>
                        </div>
                    </div>

                    <!-- Step 2: Teachers -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                            <div class="w-8 h-8 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center font-bold">2</div>
                            <h3 class="font-bold text-gray-900">Data Guru</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <p class="text-gray-600 text-sm">Input data guru dan staf pengajar untuk unit sekolah ini.</p>
                            <a href="#" class="block w-full text-center py-2 px-4 bg-purple-50 text-purple-600 font-semibold rounded-lg hover:bg-purple-100 transition-colors">
                                Kelola Guru
                            </a>
                        </div>
                    </div>

                    <!-- Step 3: Students -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center font-bold">3</div>
                            <h3 class="font-bold text-gray-900">Data Siswa</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <p class="text-gray-600 text-sm">Input data siswa atau impor data siswa secara massal.</p>
                            <a href="#" class="block w-full text-center py-2 px-4 bg-green-50 text-green-700 font-semibold rounded-lg hover:bg-green-100 transition-colors">
                                Kelola Siswa
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Classroom & Subjects -->
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 flex items-center gap-6">
                        <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900">Ruang Kelas</h3>
                            <p class="text-gray-600 text-sm mt-1">Atur pembagian kelas dan kapasitas siswa.</p>
                        </div>
                        <a href="#" class="p-3 text-gray-400 hover:text-primary-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 flex items-center gap-6">
                        <div class="w-16 h-16 bg-pink-100 text-pink-600 rounded-2xl flex items-center justify-center shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900">Mata Pelajaran</h3>
                            <p class="text-gray-600 text-sm mt-1">Konfigurasi kurikulum dan mata pelajaran.</p>
                        </div>
                        <a href="#" class="p-3 text-gray-400 hover:text-primary-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                 </div>
            </div>
        </main>
    </div>
</body>
</html>
