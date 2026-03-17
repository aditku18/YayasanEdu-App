@extends('layouts.tenant-platform')

@section('title', 'Dashboard Yayasan')

@php
$hour = now()->hour;
$greeting = $hour < 10 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 19 ? 'Selamat Sore' : 'Selamat Malam'));
$formattedDate = now()->locale('id')->isoFormat('dddd, D MMMM YYYY');

// Mock data untuk calendar dan notifications
$calendarEvents = [
    ['title' => 'Rapat Dewan Guru', 'date' => now()->addDays(1)->format('Y-m-d'), 'type' => 'meeting'],
    ['title' => 'Pembayaran SPP Bulan Ini', 'date' => now()->addDays(3)->format('Y-m-d'), 'type' => 'payment'],
    ['title' => 'Ujian Tengah Semester', 'date' => now()->addDays(7)->format('Y-m-d'), 'type' => 'exam'],
    ['title' => 'Minggu Efektif', 'date' => now()->addDays(10)->format('Y-m-d'), 'type' => 'event'],
    ['title' => 'Raport Semester', 'date' => now()->addDays(14)->format('Y-m-d'), 'type' => 'report'],
];

$notifications = [
    ['title' => 'Pendaftaran Siswa Baru', 'message' => '15 pendaftar baru menunggu verifikasi', 'time' => '5 menit yang lalu', 'icon' => 'student', 'color' => 'blue'],
    ['title' => 'Pembayaran SPP', 'message' => '8 siswa telah membayar SPP bulan ini', 'time' => '1 jam yang lalu', 'icon' => 'payment', 'color' => 'green'],
    ['title' => 'Aktivitas Guru', 'message' => '5 guru mengajukan cuti', 'time' => '2 jam yang lalu', 'icon' => 'teacher', 'color' => 'purple'],
    ['title' => 'Reminder', 'message' => 'Raport semester akan dicetak minggu depan', 'time' => '3 jam yang lalu', 'icon' => 'reminder', 'color' => 'amber'],
];

$upcomingEvents = [
    ['title' => 'Rapat Dewan Guru', 'date' => 'Besok', 'time' => '09:00 WIB'],
    ['title' => 'Pembayaran SPP', 'date' => '3 Hari', 'time' => 'Due Date'],
    ['title' => 'UTS', 'date' => '7 Hari', 'time' => '08:00 WIB'],
];
@endphp

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
    
    .animate-delay-1 { animation-delay: 0.1s; }
    .animate-delay-2 { animation-delay: 0.2s; }
    .animate-delay-3 { animation-delay: 0.3s; }
    .animate-delay-4 { animation-delay: 0.4s; }
    .animate-delay-5 { animation-delay: 0.5s; }
    .animate-delay-6 { animation-delay: 0.6s; }
    
    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
    }
    
    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    .stat-icon {
        transition: all 0.3s ease;
    }
    
    .quick-action-btn {
        transition: all 0.3s ease;
    }
    
    .quick-action-btn:hover {
        transform: translateY(-2px);
    }
    
    .quick-action-btn:hover .action-icon-bg {
        transform: scale(1.1);
    }
    
    .action-icon-bg {
        transition: all 0.3s ease;
    }
    
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endpush

@section('content')
<div class="space-y-8">
    <!-- Welcome Hero Section -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary-600 via-primary-500 to-indigo-500 p-8 md:p-10 text-white animate-fade-in-up">
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
        <div class="absolute top-1/2 right-1/4 w-32 h-32 bg-indigo-300/20 rounded-full blur-xl"></div>
        
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <p class="text-primary-100 text-sm font-medium mb-1">{{ $formattedDate }}</p>
                    <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ $greeting }}, Admin!</h1>
                    <p class="text-primary-100 text-base max-w-xl">Selamat datang di dashboard utama Yayasan Hidayattul Amin. Berikut ringkasan aktivitas dan performa seluruh unit sekolah.</p>
                </div>
                <div class="hidden lg:flex items-center gap-4">
                    <div class="text-center px-6 py-4 bg-white/20 backdrop-blur-sm rounded-2xl">
                        <p class="text-3xl font-bold">{{ $trialDaysLeft }}</p>
                        <p class="text-sm text-primary-100">Hari Trial</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats Card Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Units -->
        <div class="stat-card bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl animate-fade-in-up animate-delay-2">
            <div class="flex items-start justify-between">
                <div class="p-3 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl shadow-lg shadow-primary-200">
                    <svg class="w-6 h-6 text-white stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <span class="inline-flex items-center px-2 py-1 bg-emerald-50 text-emerald-600 text-xs font-semibold rounded-full">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    +12%
                </span>
            </div>
            <div class="mt-5">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Unit Sekolah</p>
                <h3 class="text-4xl font-extrabold text-slate-900 mt-2">{{ $stats['total_schools'] }}</h3>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-50">
                <p class="text-xs text-slate-500">Dari bulan lalu</p>
            </div>
        </div>

        <!-- Total Teachers -->
        <div class="stat-card bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl animate-fade-in-up animate-delay-3">
            <div class="flex items-start justify-between">
                <div class="p-3 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl shadow-lg shadow-blue-200">
                    <svg class="w-6 h-6 text-white stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <span class="inline-flex items-center px-2 py-1 bg-emerald-50 text-emerald-600 text-xs font-semibold rounded-full">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    +8%
                </span>
            </div>
            <div class="mt-5">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Guru</p>
                <h3 class="text-4xl font-extrabold text-slate-900 mt-2">{{ number_format($stats['total_teachers']) }}</h3>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-50">
                <p class="text-xs text-slate-500">Terverifikasi: {{ number_format($stats['total_teachers'] * 0.85) }}</p>
            </div>
        </div>

        <!-- Total Students -->
        <div class="stat-card bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl animate-fade-in-up animate-delay-4">
            <div class="flex items-start justify-between">
                <div class="p-3 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl shadow-lg shadow-purple-200">
                    <svg class="w-6 h-6 text-white stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span class="inline-flex items-center px-2 py-1 bg-emerald-50 text-emerald-600 text-xs font-semibold rounded-full">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    +15%
                </span>
            </div>
            <div class="mt-5">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Siswa</p>
                <h3 class="text-4xl font-extrabold text-slate-900 mt-2">{{ number_format($stats['total_students']) }}</h3>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-50">
                <p class="text-xs text-slate-500">Aktif: {{ number_format($stats['total_students'] * 0.92) }}</p>
            </div>
        </div>

        <!-- Trial Status -->
        <div class="stat-card bg-gradient-to-br from-amber-50 to-orange-50 p-6 rounded-2xl border border-amber-100 shadow-sm hover:shadow-xl animate-fade-in-up animate-delay-5">
            <div class="flex items-start justify-between">
                <div class="p-3 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl shadow-lg shadow-amber-200">
                    <svg class="w-6 h-6 text-white stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="inline-flex items-center px-2 py-1 bg-white/50 text-amber-600 text-xs font-semibold rounded-full">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Remaining
                </span>
            </div>
            <div class="mt-5">
                <p class="text-amber-600 text-xs font-bold uppercase tracking-wider">Status Trial</p>
                <h3 class="text-4xl font-extrabold text-slate-900 mt-2">{{ $trialDaysLeft }} <span class="text-lg font-medium text-slate-500">Hari</span></h3>
            </div>
            <div class="mt-4">
                <div class="w-full bg-amber-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-amber-400 to-orange-500 h-2 rounded-full" style="width: {{ ($trialDaysLeft / 30) * 100 }}%"></div>
                </div>
                <p class="text-xs text-amber-600 mt-2">Upgrade ke premium untuk akses penuh</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section - Moved after Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-fade-in-up animate-delay-1">
        <a href="{{ route('tenant.units.create') }}" class="quick-action-btn group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-primary-200">
            <div class="action-icon-bg w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <h4 class="font-semibold text-slate-800 text-sm">Tambah Unit</h4>
            <p class="text-xs text-slate-500 mt-1">Buat sekolah baru</p>
        </a>
        
        <a href="#" class="quick-action-btn group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-primary-200">
            <div class="action-icon-bg w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <h4 class="font-semibold text-slate-800 text-sm">Tambah Guru</h4>
            <p class="text-xs text-slate-500 mt-1">Rekrut pendidik</p>
        </a>
        
        <a href="#" class="quick-action-btn group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-primary-200">
            <div class="action-icon-bg w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h4 class="font-semibold text-slate-800 text-sm">Laporan</h4>
            <p class="text-xs text-slate-500 mt-1">Lihat statistik</p>
        </a>
        
        <a href="#" class="quick-action-btn group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-primary-200">
            <div class="action-icon-bg w-12 h-12 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h4 class="font-semibold text-slate-800 text-sm">Pengaturan</h4>
            <p class="text-xs text-slate-500 mt-1">Konfigurasi</p>
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Distribution Chart -->
        <div class="lg:col-span-2 bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg transition-shadow animate-fade-in-up animate-delay-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Distribusi Siswa per Unit</h3>
                    <p class="text-sm text-slate-500 mt-1">Perbandingan jumlah siswa di setiap sekolah</p>
                </div>
                <div class="flex items-center gap-3">
                    <select class="bg-slate-50 border-none text-xs font-semibold text-slate-600 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:outline-none cursor-pointer">
                        <option>Seluruh Jenjang</option>
                        <option>SMA/SMK Only</option>
                        <option>SD</option>
                        <option>SMP</option>
                    </select>
                </div>
            </div>
            <div id="distributionChart" class="h-72"></div>
        </div>

        <!-- Enhanced Activity Feed with Notifications -->
        <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg transition-shadow animate-fade-in-up animate-delay-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Notifikasi Terbaru</h3>
                    <p class="text-sm text-slate-500 mt-1">Pengumuman dan aktivitas</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 bg-primary-50 text-primary-600 text-xs font-semibold rounded-full">
                    {{ count($notifications) }} baru
                </span>
            </div>
            <div class="space-y-1 max-h-96 overflow-y-auto custom-scrollbar pr-2">
                @foreach($notifications as $notification)
                <div class="flex gap-4 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                    <div class="relative flex-shrink-0">
                        @if($notification['icon'] == 'student')
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"/></svg>
                        </div>
                        @elseif($notification['icon'] == 'payment')
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                        </div>
                        @elseif($notification['icon'] == 'teacher')
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        @else
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 leading-tight line-clamp-1">{{ $notification['title'] }}</p>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $notification['message'] }}</p>
                        <p class="text-xs text-slate-400 mt-1.5 font-medium">{{ $notification['time'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <button class="w-full mt-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-sm font-semibold rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all shadow-lg shadow-primary-200 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                Lihat Semua Notifikasi
            </button>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
        <!-- Calendar Widget -->
        <div class="lg:col-span-2 bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg transition-shadow animate-fade-in-up">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Kalender Akademik</h3>
                    <p class="text-sm text-slate-500 mt-1">Jadwal dan acara penting</p>
                </div>
                <div class="flex items-center gap-2">
                    <button class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <span class="text-sm font-semibold text-slate-800">Maret 2026</span>
                    <button class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
            
            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-2 mb-4">
                @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                <div class="text-center text-xs font-semibold text-slate-500 py-2">{{ $day }}</div>
                @endforeach
                
                @for($i = 1; $i <= 31; $i++)
                    @php
                    $isToday = $i == now()->day;
                    $hasEvent = in_array($i, [2, 5, 10, 15, 20, 25]);
                    @endphp
                    <div class="relative p-2 text-center rounded-lg hover:bg-slate-50 transition-colors cursor-pointer {{ $isToday ? 'bg-primary-50' : '' }}">
                        <span class="text-sm font-medium {{ $isToday ? 'text-primary-600' : 'text-slate-700' }}">{{ $i }}</span>
                        @if($hasEvent)
                        <span class="absolute bottom-1 left-1/2 transform -translate-x-1/2 w-1.5 h-1.5 bg-primary-500 rounded-full"></span>
                        @endif
                    </div>
                @endfor
            </div>

            <!-- Upcoming Events -->
            <div class="border-t border-slate-100 pt-4 mt-4">
                <h4 class="text-sm font-semibold text-slate-800 mb-3">Acara Mendatang</h4>
                <div class="space-y-3">
                    @foreach($upcomingEvents as $event)
                    <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-xl">
                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-800">{{ $event['title'] }}</p>
                            <p class="text-xs text-slate-500">{{ $event['date'] }} • {{ $event['time'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Quick Stats & Info -->
        <div class="space-y-6">
            <!-- Financial Summary -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Ringkasan Keuangan</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Pemasukan Bulan Ini</p>
                                <p class="text-lg font-bold text-slate-900">Rp 125.000.000</p>
                            </div>
                        </div>
                        <span class="text-xs text-emerald-600 font-semibold">+12%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Pengeluaran Bulan Ini</p>
                                <p class="text-lg font-bold text-slate-900">Rp 85.000.000</p>
                            </div>
                        </div>
                        <span class="text-xs text-red-600 font-semibold">+5%</span>
                    </div>
                    <div class="pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-slate-500">Saldo Kas</p>
                            <p class="text-xl font-bold text-primary-600">Rp 40.000.000</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Summary -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Kehadiran Hari Ini</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-600">Siswa</span>
                            <span class="font-semibold text-slate-900">92%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-primary-500 h-2 rounded-full" style="width: 92%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-600">Guru</span>
                            <span class="font-semibold text-slate-900">95%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: 95%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-600">Staff</span>
                            <span class="font-semibold text-slate-900">88%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-amber-500 h-2 rounded-full" style="width: 88%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced chart configuration
        var options = {
            series: [{
                name: 'Siswa',
                data: [{{ implode(',', $schools->pluck('students_count')->toArray()) }}]
            }],
            chart: {
                type: 'area',
                height: 300,
                toolbar: { 
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                },
                zoom: { enabled: false },
                fontFamily: 'Inter, sans-serif'
            },
            dataLabels: { enabled: false },
            colors: ['#6366f1'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.5,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                }
            },
            stroke: { 
                curve: 'smooth', 
                width: 3,
                lineCap: 'round'
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                xaxis: { lines: { show: true } },
                yaxis: { lines: { show: false } },
                padding: { left: 10, right: 10 }
            },
            xaxis: {
                categories: {!! json_encode($schools->pluck('name')->toArray()) !!},
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { 
                    style: { 
                        colors: '#64748b', 
                        fontWeight: 500,
                        fontSize: '11px'
                    },
                    rotate: -45,
                    rotateAlways: false
                }
            },
            yaxis: {
                labels: { 
                    style: { 
                        colors: '#64748b', 
                        fontWeight: 500,
                        fontSize: '11px'
                    },
                    formatter: function(value) {
                        return value.toLocaleString('id-ID');
                    }
                }
            },
            tooltip: {
                theme: 'light',
                style: {
                    fontSize: '12px',
                    fontFamily: 'Inter, sans-serif'
                },
                y: {
                    formatter: function(val) {
                        return val.toLocaleString('id-ID') + ' siswa';
                    }
                }
            },
            markers: {
                size: 0,
                hover: {
                    size: 6,
                    offsetX: 0,
                    offsetY: 0
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#distributionChart"), options);
        chart.render();
    });
</script>
@endpush
