@extends('layouts.tenant-platform')

@section('title', 'Legalitas Yayasan')

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
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
    }
    .document-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }
    .document-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border-color: rgba(99, 102, 241, 0.3);
    }
    .status-badge {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 4px 20px -8px rgba(16, 185, 129, 0.4);
    }
    .upload-zone {
        border: 2px dashed rgba(99, 102, 241, 0.3);
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
        transition: all 0.3s ease;
    }
    .upload-zone:hover {
        border-color: rgba(99, 102, 241, 0.6);
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
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
            <div class="absolute top-0 left-1/4 w-32 h-32 bg-white/5 rounded-full animate-pulse-slow" style="animation-delay: 2s;"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-6">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-green-500 rounded-2xl opacity-20 blur-xl"></div>
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center relative">
                            <svg class="w-8 h-8 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-white to-primary-100 bg-clip-text text-transparent">
                            Legalitas & Akta
                        </h1>
                        <p class="text-primary-100 text-xl leading-relaxed mt-2">
                            Informasi perizinan dan dokumen hukum yayasan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Card -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="document-card rounded-3xl p-8 animate-fade-in-up animate-delay-1">
            <div class="flex items-center gap-6">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-green-500 rounded-2xl opacity-20 blur-xl animate-pulse-slow"></div>
                    <div class="w-16 h-16 status-badge rounded-2xl flex items-center justify-center relative">
                        <svg class="w-8 h-8 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">Status Legalitas Aktif</h3>
                    <p class="text-sm text-slate-600 uppercase tracking-wider font-semibold">Tervalidasi oleh sistem</p>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="text-sm text-emerald-700 font-medium">Dokumen lengkap dan valid</span>
                    </div>
                </div>
                <div class="text-right">
                    <button class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-medium rounded-xl shadow-lg shadow-primary-500/25 hover:shadow-xl hover:shadow-primary-500/40 transition-all duration-200 hover:-translate-y-1">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        Edit Dokumen
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Details Section -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Document -->
        <div class="lg:col-span-2 space-y-6">
            <div class="document-card rounded-3xl p-8 animate-fade-in-up animate-delay-2">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-slate-900">Detail Dokumen Legalitas</h3>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-xs font-medium">PDF</span>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">Terverifikasi</span>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <!-- Document Content -->
                    <div class="p-6 bg-gradient-to-br from-slate-50 to-blue-50 rounded-2xl border border-slate-200">
                        <div class="prose prose-slate max-w-none">
                            <p class="text-slate-700 leading-relaxed whitespace-pre-wrap">
                                {{ $yayasan->legalitas ?? 'Belum ada data legalitas yang diinput.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Document Metadata -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border border-amber-200">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-900">Tanggal Penerbitan</span>
                            </div>
                            <p class="text-slate-600 text-sm">{{ $yayasan->tanggal_legalitas ? \Carbon\Carbon::parse($yayasan->tanggal_legalitas)->format('d M Y') : 'Belum ditetapkan' }}</p>
                        </div>
                        
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-900">Nomor Akta</span>
                            </div>
                            <p class="text-slate-600 text-sm">{{ $yayasan->nomor_akta ?? 'Belum tersedia' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Section -->
            <div class="document-card rounded-3xl p-8 animate-fade-in-up animate-delay-3">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Upload Dokumen Baru</h3>
                <div class="upload-zone rounded-2xl p-8 text-center cursor-pointer hover-lift">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-slate-900 mb-2">Seret dan Lepas File</h4>
                    <p class="text-slate-600 mb-4">atau klik untuk memilih file</p>
                    <div class="flex items-center justify-center gap-4 text-xs text-slate-500">
                        <span class="px-2 py-1 bg-slate-100 rounded">PDF</span>
                        <span class="px-2 py-1 bg-slate-100 rounded">DOC</span>
                        <span class="px-2 py-1 bg-slate-100 rounded">DOCX</span>
                        <span class="px-2 py-1 bg-slate-100 rounded">Max 10MB</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="document-card rounded-3xl p-6 animate-slide-in-left animate-delay-1">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <button class="w-full px-4 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all duration-200 text-sm font-medium flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Lihat Dokumen
                    </button>
                    <button class="w-full px-4 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200 text-sm font-medium flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Unduh PDF
                    </button>
                    <button class="w-full px-4 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-all duration-200 text-sm font-medium flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m9.032 4.026a9.001 9.001 0 01-7.432 0m9.032-4.026A9.001 9.001 0 0112 3c-4.474 0-8.268 2.943-9.543 7a9.97 9.97 0 011.842 3.026m9.032-4.026A9.97 9.97 0 0112 21c-4.474 0-8.268-2.943-9.543-7"/>
                        </svg>
                        Bagikan
                    </button>
                </div>
            </div>

            <!-- Information -->
            <div class="document-card rounded-3xl p-6 animate-slide-in-left animate-delay-2">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Informasi Penting</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-900">Verifikasi Otomatis</p>
                            <p class="text-xs text-slate-600 mt-1">Dokumen akan diverifikasi secara otomatis oleh sistem kami.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-900">Keamanan Terjamin</p>
                            <p class="text-xs text-slate-600 mt-1">Semua dokumen tersimpan dengan enkripsi tingkat enterprise.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-900">Backup Otomatis</p>
                            <p class="text-xs text-slate-600 mt-1">Dokumen Anda akan di-backup secara otomatis setiap hari.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
