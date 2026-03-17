@extends('layouts.app')

@section('title', 'PPDB - Penerimaan Peserta Didik Baru')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
        <div class="container mx-auto px-4 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Penerimaan Peserta Didik Baru
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8">
                    Daftar online untuk tahun ajaran {{ date('Y') }}/{{ date('Y') + 1 }}
                </p>
                <div class="flex justify-center space-x-4">
                    <div class="bg-white/20 rounded-lg px-4 py-2 backdrop-blur">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pendaftaran: {{ now()->format('d F Y') }}
                    </div>
                    <div class="bg-white/20 rounded-lg px-4 py-2 backdrop-blur">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Biaya Terjangkau
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="container mx-auto px-4 -mt-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $waves->count() }}</div>
                <div class="text-gray-600">Gelombang Tersedia</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">
                    {{ $waves->where('is_full', false)->count() }}
                </div>
                <div class="text-gray-600">Gelombang Terbuka</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">
                    {{ $waves->sum('applicants_count') }}
                </div>
                <div class="text-gray-600">Total Pendaftar</div>
            </div>
        </div>
    </div>

    <!-- Available Waves -->
    <div class="container mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Gelombang Pendaftaran</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Pilih gelombang pendaftaran yang sesuai dengan jadwal dan preferensi Anda
            </p>
        </div>

        @if($waves->isEmpty())
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Gelombang</h3>
                <p class="text-gray-600 max-w-md mx-auto">
                    Saat ini belum ada gelombang pendaftaran yang tersedia. Silakan cek kembali nanti.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($waves as $wave)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <!-- Wave Header -->
                        <div class="bg-gradient-to-r {{ $wave->is_full ? 'from-gray-500 to-gray-600' : 'from-blue-500 to-indigo-600' }} p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-white">{{ $wave->name }}</h3>
                                @if($wave->is_full)
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">Penuh</span>
                                @else
                                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">Terbuka</span>
                                @endif
                            </div>
                            <div class="text-white/90 text-sm">
                                @if($wave->registration_start && $wave->registration_end)
                                    <div class="flex items-center mb-2">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $wave->registration_start->format('d M Y') }} - {{ $wave->registration_end->format('d M Y') }}
                                    </div>
                                @endif
                                @if($wave->quota)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Kuota: {{ $wave->applicants_count }}/{{ $wave->quota }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Wave Content -->
                        <div class="p-6">
                            @if($wave->description)
                                <p class="text-gray-600 mb-4">{{ Str::limit($wave->description, 100) }}</p>
                            @endif

                            @if($wave->major)
                                <div class="flex items-center text-sm text-gray-500 mb-4">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    {{ $wave->major->name }}
                                </div>
                            @endif

                            <!-- Progress Bar -->
                            @if($wave->quota)
                                <div class="mb-4">
                                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                                        <span>Kuota Terisi</span>
                                        <span>{{ round(($wave->applicants_count / $wave->quota) * 100) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, round(($wave->applicants_count / $wave->quota) * 100)) }}%"></div>
                                    </div>
                                </div>
                            @endif

                            <!-- Action Button -->
                            <div class="mt-6">
                                @if($wave->is_full)
                                    <button disabled class="w-full bg-gray-300 text-gray-500 py-3 px-4 rounded-lg font-medium cursor-not-allowed">
                                        Gelombang Penuh
                                    </button>
                                @else
                                    <a href="{{ route('ppdb.public.register', $wave->id) }}" 
                                       class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium transition-colors text-center block">
                                        Daftar Sekarang
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Information Section -->
    <div class="bg-gray-50 py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Informasi Pendaftaran</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Persiapkan dokumen dan informasi yang dibutuhkan untuk proses pendaftaran
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Dokumen Diperlukan</h3>
                    <ul class="text-gray-600 text-left space-y-1">
                        <li>• Akta Kelahiran</li>
                        <li>• Kartu Keluarga</li>
                        <li>• Ijazah/STL</li>
                        <li>• Pas Foto</li>
                    </ul>
                </div>

                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Proses Cepat</h3>
                    <ul class="text-gray-600 text-left space-y-1">
                        <li>• Pendaftaran Online</li>
                        <li>• Verifikasi Otomatis</li>
                        <li>• Tracking Status</li>
                        <li>• Notifikasi Email</li>
                    </ul>
                </div>

                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Bantuan</h3>
                    <ul class="text-gray-600 text-left space-y-1">
                        <li>• Cek Status Online</li>
                        <li>• Upload Dokumen</li>
                        <li>• Hubungi Admin</li>
                        <li>• FAQ Lengkap</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-blue-600 py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Siap Mendaftar?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Bergabung dengan ribuan siswa yang telah mendaftar melalui sistem online kami
            </p>
            <div class="space-x-4">
                <a href="{{ route('ppdb.public.check-status') }}" class="bg-white text-blue-600 hover:bg-gray-100 py-3 px-6 rounded-lg font-medium transition-colors">
                    Cek Status
                </a>
                <a href="#" onclick="window.print()" class="border border-white text-white hover:bg-white hover:text-blue-600 py-3 px-6 rounded-lg font-medium transition-colors">
                    Cetak Informasi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
