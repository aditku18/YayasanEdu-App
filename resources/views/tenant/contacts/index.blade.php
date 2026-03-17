@extends('layouts.tenant-platform')

@section('title', 'Kontak & Dukungan')

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
    .faq-item {
        transition: all 0.3s ease;
    }
    .faq-item:hover {
        transform: translateY(-2px);
    }
    .contact-method {
        transition: all 0.3s ease;
    }
    .contact-method:hover {
        transform: translateY(-4px);
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">Kontak & Dukungan</h1>
                </div>
                <p class="text-primary-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Kami siap membantu Anda dengan pertanyaan, dukungan teknis, atau informasi lebih lanjut tentang EduSaaS
                </p>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">< 1h</p>
                        <p class="text-primary-100 text-sm">Rata-rata Response</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">24/7</p>
                        <p class="text-primary-100 text-sm">Support Online</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">98%</p>
                        <p class="text-primary-100 text-sm">Kepuasan Pelanggan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(isset($message))
        <div class="max-w-7xl mx-auto mb-6">
            <div class="glass-effect rounded-2xl p-6 border border-emerald-200 bg-emerald-50 animate-fade-in-up">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-emerald-800">Message Sent!</p>
                        <p class="text-emerald-700">{{ $message }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Contact Methods Section -->
    <div class="max-w-7xl mx-auto mb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Email Support -->
            <div class="contact-method glass-effect rounded-2xl p-6 hover-lift animate-fade-in-up animate-delay-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-sm text-blue-600 font-medium">Primary</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Email Support</h3>
                <p class="text-slate-600 text-sm mb-4">Kirim email untuk dukungan lengkap</p>
                <div class="space-y-2">
                    <a href="mailto:support@edusaas.com" class="block text-blue-600 hover:text-blue-700 font-medium text-sm">
                        support@edusaas.com
                    </a>
                    <p class="text-xs text-slate-500">Response dalam 24 jam</p>
                </div>
            </div>

            <!-- Phone Support -->
            <div class="contact-method glass-effect rounded-2xl p-6 hover-lift animate-fade-in-up animate-delay-2">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <span class="text-sm text-emerald-600 font-medium">Fast</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Hotline Support</h3>
                <p class="text-slate-600 text-sm mb-4">Panggilan langsung untuk bantuan urgent</p>
                <div class="space-y-2">
                    <a href="tel:+62211234567" class="block text-emerald-600 hover:text-emerald-700 font-medium text-sm">
                        (021) 1234-5678
                    </a>
                    <p class="text-xs text-slate-500">Senin - Jumat, 08:00 - 17:00 WIB</p>
                </div>
            </div>

            <!-- WhatsApp Support -->
            <div class="contact-method glass-effect rounded-2xl p-6 hover-lift animate-fade-in-up animate-delay-3">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <span class="text-sm text-green-600 font-medium">Instant</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">WhatsApp Support</h3>
                <p class="text-slate-600 text-sm mb-4">Chat langsung via WhatsApp</p>
                <div class="space-y-2">
                    <a href="https://wa.me/6281234567890" target="_blank" class="block text-green-600 hover:text-green-700 font-medium text-sm">
                        +62 812-3456-7890
                    </a>
                    <p class="text-xs text-slate-500">Response dalam 1 jam</p>
                </div>
            </div>

            <!-- Office Address -->
            <div class="contact-method glass-effect rounded-2xl p-6 hover-lift animate-fade-in-up animate-delay-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm text-purple-600 font-medium">Location</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Kantor Pusat</h3>
                <p class="text-slate-600 text-sm mb-4">Kunjungi kantor kami</p>
                <div class="space-y-2">
                    <p class="text-slate-900 font-medium text-sm">Jl. Pendidikan No. 123</p>
                    <p class="text-slate-900 font-medium text-sm">Jakarta Selatan, DKI Jakarta</p>
                    <p class="text-xs text-slate-500">Senin - Jumat, 08:00 - 17:00 WIB</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Form and FAQ -->
    <div class="max-w-7xl mx-auto mb-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="glass-effect rounded-3xl p-8 animate-fade-in-up">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">Kirim Pesan</h2>
                    <p class="text-slate-600">Isi form di bawah ini dan kami akan segera menghubungi Anda</p>
                </div>

                <form action="{{ route('tenant.contact.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap *</label>
                            <input type="text" name="name" required
                                   class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Email *</label>
                            <input type="email" name="email" required
                                   class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nomor Telepon</label>
                            <input type="tel" name="phone"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Kategori *</label>
                            <select name="category" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Pilih Kategori</option>
                                <option value="general">Umum</option>
                                <option value="technical">Teknis</option>
                                <option value="sales">Penjualan</option>
                                <option value="support">Dukungan</option>
                                <option value="billing">Tagihan</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Prioritas *</label>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                                <input type="radio" name="priority" value="low" required class="text-primary-600">
                                <span class="ml-2 text-sm">Rendah</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="priority" value="normal" required class="text-primary-600">
                                <span class="ml-2 text-sm">Normal</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="priority" value="high" required class="text-primary-600">
                                <span class="ml-2 text-sm">Tinggi</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="priority" value="urgent" required class="text-primary-600">
                                <span class="ml-2 text-sm">Mendesak</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Subjek *</label>
                        <input type="text" name="subject" required
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Pesan *</label>
                        <textarea name="message" rows="6" required
                                  class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 resize-none"
                                  placeholder="Jelaskan pertanyaan atau masalah Anda dengan detail..."></textarea>
                    </div>

                    <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold rounded-xl transition-all duration-300">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Kirim Pesan
                        </span>
                    </button>
                </form>
            </div>

            <!-- FAQ Section -->
            <div class="space-y-6">
                <div class="glass-effect rounded-3xl p-8 animate-fade-in-up">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-slate-900 mb-2">Pertanyaan Umum</h2>
                        <p class="text-slate-600">Temukan jawaban untuk pertanyaan yang sering ditanyakan</p>
                    </div>

                    <div class="space-y-4">
                        <div class="faq-item glass-effect rounded-xl p-4 hover-lift">
                            <h4 class="font-semibold text-slate-900 mb-2">Apakah EduSaaS aman digunakan?</h4>
                            <p class="text-slate-600 text-sm">Ya, EduSaaS menggunakan teknologi keamanan terkini dengan enkripsi data 256-bit, backup otomatis, dan compliance dengan standar keamanan internasional.</p>
                        </div>

                        <div class="faq-item glass-effect rounded-xl p-4 hover-lift">
                            <h4 class="font-semibold text-slate-900 mb-2">Berapa biaya langganan EduSaaS?</h4>
                            <p class="text-slate-600 text-sm">Biaya langganan tergantung pada jumlah sekolah dan fitur yang digunakan. Hubungi tim sales kami untuk mendapatkan penawaran yang sesuai dengan kebutuhan yayasan Anda.</p>
                        </div>

                        <div class="faq-item glass-effect rounded-xl p-4 hover-lift">
                            <h4 class="font-semibold text-slate-900 mb-2">Apakah ada demo atau trial yang bisa dicoba?</h4>
                            <p class="text-slate-600 text-sm">Ya, kami menyediakan demo gratis selama 14 hari dengan akses penuh ke semua fitur. Anda dapat mendaftar melalui website kami.</p>
                        </div>

                        <div class="faq-item glass-effect rounded-xl p-4 hover-lift">
                            <h4 class="font-semibold text-slate-900 mb-2">Bagaimana cara migrasi data dari sistem lama?</h4>
                            <p class="text-slate-600 text-sm">Tim teknis kami akan membantu proses migrasi data secara gratis. Kami mendukung berbagai format data dan memastikan proses berjalan lancar.</p>
                        </div>

                        <div class="faq-item glass-effect rounded-xl p-4 hover-lift">
                            <h4 class="font-semibold text-slate-900 mb-2">Apakah ada pelatihan untuk pengguna baru?</h4>
                            <p class="text-slate-600 text-sm">Ya, kami menyediakan pelatihan online dan offline untuk administrator sekolah. Dokumentasi lengkap juga tersedia di portal help center.</p>
                        </div>

                        <div class="faq-item glass-effect rounded-xl p-4 hover-lift">
                            <h4 class="font-semibold text-slate-900 mb-2">Apakah EduSaaS mendukung integrasi dengan sistem lain?</h4>
                            <p class="text-slate-600 text-sm">Ya, EduSaaS mendukung integrasi dengan berbagai sistem seperti payment gateway, WhatsApp API, Google Workspace, dan sistem absensi.</p>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="{{ route('tenant.documentation.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                            Lihat Semua FAQ →
                        </a>
                    </div>
                </div>

                <!-- Office Hours -->
                <div class="glass-effect rounded-3xl p-8 animate-fade-in-up">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-slate-900 mb-2">Jam Kerja</h2>
                        <p class="text-slate-600">Waktu operasional tim support kami</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-700 font-medium">Senin - Jumat</span>
                            <span class="text-slate-600">08:00 - 17:00 WIB</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-700 font-medium">Sabtu - Minggu</span>
                            <span class="text-slate-600">09:00 - 15:00 WIB</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-700 font-medium">Timezone</span>
                            <span class="text-slate-600">WIB (GMT+7)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Members Section -->
    <div class="max-w-7xl mx-auto mb-12">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Tim Support Kami</h2>
            <p class="text-slate-600 max-w-2xl mx-auto">
                Tim profesional yang siap membantu Anda dengan segala kebutuhan EduSaaS
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="glass-effect rounded-3xl p-6 text-center hover-lift animate-fade-in-up animate-delay-1">
                <div class="w-20 h-20 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <span class="text-2xl text-white font-bold">A</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Dr. Ahmad Rahman</h3>
                <p class="text-primary-600 font-medium mb-4">CEO & Founder</p>
                <div class="space-y-2 text-sm">
                    <div>
                        <a href="mailto:ahmad@edusaas.com" class="text-slate-600 hover:text-primary-600">
                            ahmad@edusaas.com
                        </a>
                    </div>
                    <div>
                        <a href="tel:+6281234567890" class="text-slate-600 hover:text-primary-600">
                            +62 812-3456-7890
                        </a>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-3xl p-6 text-center hover-lift animate-fade-in-up animate-delay-2">
                <div class="w-20 h-20 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <span class="text-2xl text-white font-bold">S</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Siti Nurhaliza</h3>
                <p class="text-emerald-600 font-medium mb-4">Head of Customer Success</p>
                <div class="space-y-2 text-sm">
                    <div>
                        <a href="mailto:siti@edusaas.com" class="text-slate-600 hover:text-emerald-600">
                            siti@edusaas.com
                        </a>
                    </div>
                    <div>
                        <a href="tel:+6281234567891" class="text-slate-600 hover:text-emerald-600">
                            +62 812-3456-7891
                        </a>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-3xl p-6 text-center hover-lift animate-fade-in-up animate-delay-3">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <span class="text-2xl text-white font-bold">B</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Budi Santoso</h3>
                <p class="text-blue-600 font-medium mb-4">Technical Lead</p>
                <div class="space-y-2 text-sm">
                    <div>
                        <a href="mailto:budi@edusaas.com" class="text-slate-600 hover:text-blue-600">
                            budi@edusaas.com
                        </a>
                    </div>
                    <div>
                        <a href="tel:+6281234567892" class="text-slate-600 hover:text-blue-600">
                            +62 812-3456-7892
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Social Media Section -->
    <div class="max-w-7xl mx-auto">
        <div class="glass-effect rounded-3xl p-8 md:p-12 animate-fade-in-up">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Ikuti Kami</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">
                    Dapatkan update terbaru, tips, dan informasi menarik tentang EduSaaS melalui media sosial kami
                </p>
            </div>

            <div class="flex flex-wrap justify-center gap-8">
                <a href="https://facebook.com/edusaas" target="_blank" class="flex items-center gap-3 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span class="font-medium">Facebook</span>
                </a>

                <a href="https://twitter.com/edusaas" target="_blank" class="flex items-center gap-3 px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                    <span class="font-medium">Twitter</span>
                </a>

                <a href="https://linkedin.com/company/edusaas" target="_blank" class="flex items-center gap-3 px-6 py-3 bg-blue-700 hover:bg-blue-800 text-white rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                    <span class="font-medium">LinkedIn</span>
                </a>

                <a href="https://instagram.com/edusaas" target="_blank" class="flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-500 hover:from-pink-600 hover:to-purple-600 text-white rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.017 0C8.396 0 7.966.021 6.677.072 5.393.124 4.434.249 3.627.464c-.806.211-1.488.494-2.164 1.17C.783 2.316.5 2.998.289 3.804.074 4.611-.051 5.57-.051 6.854c0 3.621.021 4.051.072 5.34.053 1.289.178 2.248.393 3.055.211.806.494 1.488 1.17 2.164.676.676 1.358.959 2.164 1.17.807.215 1.766.34 3.055.393 1.289.053 1.719.072 5.34.072s4.051-.019 5.34-.072c1.289-.053 2.248-.178 3.055-.393.806-.211 1.488-.494 2.164-1.17.676-.676.959-1.358 1.17-2.164.215-.807.34-1.766.393-3.055.053-1.289.072-1.719.072-5.34s-.019-4.051-.072-5.34c-.053-1.289-.178-2.248-.393-3.055-.211-.806-.494-1.488-1.17-2.164C21.684.5 21.002.217 20.196.006c-.807-.215-1.766-.34-3.055-.393C15.852.021 15.422 0 11.801 0h.216zm-.001 2.25c3.554 0 3.97.016 5.372.085 1.293.064 1.992.27 2.461.45.47.181.862.423 1.238.799.376.376.618.768.799 1.238.181.469.386 1.168.45 2.461.069 1.402.085 1.818.085 5.372s-.016 3.97-.085 5.372c-.064 1.293-.27 1.992-.45 2.461-.181.47-.423.862-.799 1.238-.376.376-.768.618-1.238.799-.469.181-1.168.386-2.461.45-1.402.069-1.818.085-5.372.085s-3.97-.016-5.372-.085c-1.293-.064-1.992-.27-2.461-.45-.47-.181-.862-.423-1.238-.799-.376-.376-.618-.768-.799-1.238-.181-.469-.386-1.168-.45-2.461-.069-1.402-.085-1.818-.085-5.372s.016-3.97.085-5.372c.064-1.293.27-1.992.45-2.461.181-.47.423-.862.799-1.238.376-.376.768-.618 1.238-.799.469-.181 1.168-.386 2.461-.45 1.402-.069 1.818-.085 5.372-.085zM12.017 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998zm6.406-11.845a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z"/>
                        <path d="M12.017 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998zm6.406-11.845a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z"/>
                    </svg>
                    <span class="font-medium">Instagram</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
