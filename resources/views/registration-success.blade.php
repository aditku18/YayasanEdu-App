<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Berhasil - YayasanEdu Platform</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                        },
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 3s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-in': 'bounceIn 0.6s ease-out',
                        'fade-in-up': 'fadeInUp 0.8s ease-out 0.3s both',
                        'scale-in': 'scaleIn 0.5s ease-out forwards',
                        'shimmer': 'shimmer 2s infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        bounceIn: {
                            '0%': { transform: 'scale(0.3)', opacity: '0' },
                            '50%': { transform: 'scale(1.05)' },
                            '70%': { transform: 'scale(0.9)' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        },
                        fadeInUp: {
                            'from': { transform: 'translateY(30px)', opacity: '0' },
                            'to': { transform: 'translateY(0)', opacity: '1' },
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0)', opacity: '0' },
                            '50%': { transform: 'scale(1.1)' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-200% 0' },
                            '100%': { backgroundPosition: '200% 0' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6 0%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .success-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .btn-shine::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }
        .btn-shine:hover::before {
            left: 100%;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-primary-50 via-white to-emerald-50 min-h-screen">
    <!-- Background Pattern -->
    <div class="fixed inset-0 opacity-40">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <div class="relative min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-2xl w-full">
            
            <!-- Success Card -->
            <div class="success-card rounded-3xl p-8 md:p-12 text-center relative overflow-hidden">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-full -mr-16 -mt-16 opacity-60"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-primary-100 to-primary-50 rounded-full -ml-12 -mb-12 opacity-60"></div>

                <!-- Success Icon -->
                <div class="relative mb-8">
                    <div class="w-32 h-32 mx-auto bg-gradient-to-br from-emerald-400 via-emerald-500 to-green-500 rounded-full flex items-center justify-center shadow-2xl animate-scale-in relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-400 to-green-500 rounded-full animate-ping opacity-20"></div>
                        <i class="fas fa-check text-white text-5xl relative z-10"></i>
                    </div>
                    <!-- Confetti Effect -->
                    <div class="absolute inset-0 pointer-events-none">
                        <div class="absolute top-0 left-1/4 w-2 h-2 bg-yellow-400 rounded-full animate-bounce-in" style="animation-delay: 0.1s"></div>
                        <div class="absolute top-0 right-1/4 w-2 h-2 bg-pink-400 rounded-full animate-bounce-in" style="animation-delay: 0.2s"></div>
                        <div class="absolute bottom-0 left-1/3 w-2 h-2 bg-blue-400 rounded-full animate-bounce-in" style="animation-delay: 0.3s"></div>
                        <div class="absolute bottom-0 right-1/3 w-2 h-2 bg-purple-400 rounded-full animate-bounce-in" style="animation-delay: 0.4s"></div>
                    </div>
                </div>

                <!-- Success Message -->
                <div class="animate-fade-in-up">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        <span class="gradient-text">Pendaftaran Berhasil!</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 max-w-lg mx-auto">
                        🎉 Selamat! Pendaftaran yayasan Anda telah berhasil dikirim. 
                        Tim kami akan memverifikasi data Anda dalam <span class="font-semibold text-primary-600">maksimal 1x24 jam</span>.
                    </p>

                    <!-- Status Timeline -->
                    <div class="bg-gradient-to-r from-primary-50 to-emerald-50 rounded-2xl p-6 mb-8 border border-primary-100">
                        <h3 class="font-bold text-gray-800 mb-6 flex items-center justify-center gap-2">
                            <i class="fas fa-tasks text-primary-500"></i>
                            Proses Verifikasi & Aktivasi
                        </h3>
                        <div class="flex items-center justify-between max-w-xl mx-auto">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                    <i class="fas fa-check text-sm"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-600 mt-2">Submitted</span>
                            </div>
                            <div class="flex-1 h-1 bg-gradient-to-r from-emerald-500 to-yellow-400 rounded-full mx-2"></div>
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold shadow-lg animate-pulse-slow">
                                    <i class="fas fa-envelope text-sm"></i>
                                </div>
                                <span class="text-xs font-medium text-yellow-600 mt-2">Verifikasi Email</span>
                            </div>
                            <div class="flex-1 h-1 bg-gray-200 rounded-full mx-2"></div>
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 font-bold">
                                    <i class="fas fa-file-check text-sm"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-400 mt-2">Review Dokumen</span>
                            </div>
                            <div class="flex-1 h-1 bg-gray-200 rounded-full mx-2"></div>
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 font-bold">
                                    <i class="fas fa-rocket text-sm"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-400 mt-2">Aktif</span>
                            </div>
                        </div>
                    </div>

                    <!-- Info Cards Grid -->
                    <div class="grid md:grid-cols-3 gap-4 mb-8">
                        <div class="glass-effect rounded-xl p-4 border border-yellow-200 bg-yellow-50/50">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-envelope-open text-yellow-600"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800 mb-1">1. Cek Email Anda</h4>
                            <p class="text-sm text-gray-600">Klik link verifikasi yang dikirim ke email Anda</p>
                        </div>
                        <div class="glass-effect rounded-xl p-4 border border-white/20">
                            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-file-alt text-primary-600"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800 mb-1">2. Review Dokumen</h4>
                            <p class="text-sm text-gray-600">Tim kami akan memverifikasi dokumen Anda</p>
                        </div>
                        <div class="glass-effect rounded-xl p-4 border border-white/20">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-rocket text-emerald-600"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800 mb-1">3. Mulai Gunakan</h4>
                            <p class="text-sm text-gray-600">Login setelah di-approve admin</p>
                        </div>
                    </div>

                    <!-- Alert Box for Email -->
                    <div class="mb-6 p-4 bg-yellow-50 rounded-xl border border-yellow-300">
                        <p class="text-sm text-yellow-800 font-medium">
                            <i class="fas fa-exclamation-triangle mr-2 text-yellow-600"></i>
                            <strong>Penting:</strong> Silakan cek kotak masuk (dan folder spam) untuk email verifikasi. 
                            Anda harus memverifikasi email sebelum akun dapat diproses lebih lanjut.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('landing') }}" class="btn-shine flex-1 group relative inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl font-semibold hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 overflow-hidden">
                            <i class="fas fa-home mr-2 group-hover:animate-bounce"></i>
                            Kembali ke Beranda
                        </a>
                        <a href="{{ route('login') }}" class="flex-1 inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 rounded-xl font-semibold border-2 border-gray-200 hover:border-primary-300 hover:bg-primary-50 transition-all duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Login (setelah verifikasi email)
                        </a>
                    </div>

                    <!-- Contact Info -->
                    <div class="mt-8 p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Butuh bantuan? Hubungi kami di 
                            <a href="mailto:support@yayasanedu.com" class="font-semibold text-blue-600 hover:underline">support@yayasanedu.com</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-sm text-gray-500">
                <p>&copy; 2026 YayasanEdu Platform. All rights reserved.</p>
                <p class="mt-1">Made with <i class="fas fa-heart text-red-500"></i> for Education</p>
            </div>
        </div>
    </div>
</body>
</html>
