<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Yayasan - Langkah {{ $step }} dari 3 | YayasanEdu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-XXXXXXXXXX');
        
        function trackStep(step, action) {
            gtag('event', 'registration_step', {
                'event_category': 'conversion',
                'event_label': 'step_' + step + '_' + action,
                'step_number': step
            });
        }
    </script>

    <style>
        .step-indicator {
            transition: all 0.3s ease;
        }
        
        .step-indicator.active {
            background: linear-gradient(135deg, #0ea5e9 0%, #8b5cf6 100%);
            color: white;
        }
        
        .step-indicator.completed {
            background: #10b981;
            color: white;
        }
        
        .step-indicator.pending {
            background: #e5e7eb;
            color: #9ca3af;
        }
        
        .form-step {
            display: none;
        }
        
        .form-step.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .password-strength {
            height: 4px;
            transition: all 0.3s ease;
        }
        
        .plan-card {
            transition: all 0.3s ease;
        }
        
        .plan-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .plan-card.selected {
            border-color: #8b5cf6;
            background: linear-gradient(to bottom, #f5f3ff, #ede9fe);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-800 min-h-screen">

    <!-- Header -->
    <header class="py-4 px-6 bg-white/10 backdrop-blur-md">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-white text-xl"></i>
                </div>
                <span class="text-white text-xl font-bold">YayasanEdu</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-white/60 text-sm">Sudah punya akun?</span>
                <a href="{{ route('login') }}" class="text-white hover:text-white/80 font-medium">Masuk</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        
        <!-- Progress Steps -->
        <div class="mb-10">
            <div class="flex items-center justify-between relative">
                <!-- Progress Line -->
                <div class="absolute top-5 left-0 right-0 h-1 bg-gray-600 -z-10"></div>
                <div class="absolute top-5 left-0 h-1 bg-gradient-to-r from-green-500 to-purple-500 -z-10 transition-all duration-500" style="width: {{ ($step - 1) * 50 }}%"></div>
                
                <!-- Step 1 -->
                <div class="step-indicator {{ $step == 1 ? 'active' : ($step > 1 ? 'completed' : 'pending') }} w-10 h-10 rounded-full flex items-center justify-center font-bold">
                    @if($step > 1)
                        <i class="fas fa-check"></i>
                    @else
                        1
                    @endif
                </div>
                
                <!-- Step 2 -->
                <div class="step-indicator {{ $step == 2 ? 'active' : ($step > 2 ? 'completed' : 'pending') }} w-10 h-10 rounded-full flex items-center justify-center font-bold">
                    @if($step > 2)
                        <i class="fas fa-check"></i>
                    @else
                        2
                    @endif
                </div>
                
                <!-- Step 3 -->
                <div class="step-indicator {{ $step == 3 ? 'active' : 'pending' }} w-10 h-10 rounded-full flex items-center justify-center font-bold">
                    3
                </div>
            </div>
            
            <!-- Step Labels -->
            <div class="flex justify-between mt-3 text-sm">
                <div class="text-center flex-1">
                    <div class="font-medium {{ $step >= 1 ? 'text-white' : 'text-white/50' }}">Email</div>
                    <div class="text-white/50 text-xs">Langkah 1</div>
                </div>
                <div class="text-center flex-1">
                    <div class="font-medium {{ $step >= 2 ? 'text-white' : 'text-white/50' }}">Akun</div>
                    <div class="text-white/50 text-xs">Langkah 2</div>
                </div>
                <div class="text-center flex-1">
                    <div class="font-medium {{ $step >= 3 ? 'text-white' : 'text-white/50' }}">Detail</div>
                    <div class="text-white/50 text-xs">Langkah 3</div>
                </div>
            </div>
        </div>

        <!-- Register Card -->
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8">
            
            <!-- Step 1: Email Only -->
            <div class="form-step {{ $step == 1 ? 'active' : '' }}" data-step="1">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">Mulai dengan Email</h1>
                    <p class="text-gray-500 mt-2">Masukkan email Anda untuk memulai</p>
                </div>

                <form method="POST" action="{{ route('register.step1') }}">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Anda <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input 
                                type="email" 
                                name="email" 
                                id="email"
                                value="{{ old('email', $data['email'] ?? '') }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                placeholder="admin@sekolah.com"
                                required
                                autofocus
                            >
                        </div>
                        @error('email')
                            <p id="error-email" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- OAuth Options -->
                    <div class="mb-6">
                        <p class="text-sm text-gray-500 text-center mb-4">atau masuk dengan</p>
                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                                Google
                            </button>
                            <button type="button" class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                Microsoft
                            </button>
                        </div>
                    </div>

                    <button type="submit" onclick="trackStep(1, 'next')" class="w-full py-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold rounded-xl hover:opacity-90 transition">
                        Lanjut ke Langkah 2 →
                    </button>
                    
                    <p class="text-xs text-gray-400 text-center mt-4">
                        Atau <a href="{{ route('register.foundation') }}" class="text-purple-600 hover:underline">isi formulir lengkap</a>
                    </p>
                </form>
            </div>

            <!-- Step 2: Name and Password -->
            <div class="form-step {{ $step == 2 ? 'active' : '' }}" data-step="2">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">Buat Akun</h1>
                    <p class="text-gray-500 mt-2">Buat password untuk mengamankan akun Anda</p>
                </div>

                <form method="POST" action="{{ route('register.step2') }}">
                    @csrf
                    
                    <div class="mb-5">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                name="name" 
                                id="name"
                                value="{{ old('name', $data['name'] ?? '') }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                placeholder="Nama Lengkap Anda"
                                required
                            >
                        </div>
                        @error('name')
                            <p id="error-name" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                placeholder="Min. 8 karakter"
                                required
                                onkeyup="checkPasswordStrength()"
                            >
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword()">
                                <i class="fas fa-eye text-gray-400" id="toggleIcon"></i>
                            </button>
                        </div>
                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <div class="password-strength bg-gray-200 rounded-full">
                                <div id="strengthBar" class="password-strength bg-red-500 rounded-full" style="width: 0%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1" id="strengthText">Minimal 8 karakter</p>
                        </div>
                        @error('password')
                            <p id="error-password" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                placeholder="Ulangi password"
                                required
                            >
                        </div>
                    </div>

                    <button type="submit" onclick="trackStep(2, 'next')" class="w-full py-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold rounded-xl hover:opacity-90 transition">
                        Lanjut ke Langkah 3 →
                    </button>
                    
                    <p class="text-xs text-gray-400 text-center mt-4">
                        <a href="{{ route('register.reset') }}" class="text-purple-600 hover:underline">Mulai dari awal</a>
                    </p>
                </form>
            </div>

            <!-- Step 3: Foundation Details -->
            <div class="form-step {{ $step == 3 ? 'active' : '' }}" data-step="3">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">Detail Yayasan</h1>
                    <p class="text-gray-500 mt-2">Lengkapi informasi sekolah Anda</p>
                </div>

                <form method="POST" action="{{ route('register.step3') }}">
                    @csrf
                    
                    <div class="mb-5">
                        <label for="foundation_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Yayasan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                name="foundation_name" 
                                id="foundation_name"
                                value="{{ old('foundation_name', $data['foundation_name'] ?? '') }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                placeholder="Yayasan Pendidikan ABC"
                                required
                            >
                        </div>
                        @error('foundation_name')
                            <p id="error-foundation_name" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Yayasan <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="address" 
                            id="address" 
                            rows="2"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                            placeholder="Jl. Pendidikan No. 123, Jakarta Selatan"
                            required
                        >{{ old('address', $data['address'] ?? '') }}</textarea>
                        @error('address')
                            <p id="error-address" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            No. Telepon / WhatsApp
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fab fa-whatsapp text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                name="phone" 
                                id="phone"
                                value="{{ old('phone', $data['phone'] ?? '') }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                placeholder="+6281234567890"
                            >
                        </div>
                    </div>

                    <!-- Plan Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Pilih Paket <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 gap-3">
                            @foreach($plans as $plan)
                            <label class="plan-card relative block cursor-pointer">
                                <input type="radio" name="plan_id" value="{{ $plan->id }}" class="sr-only" {{ old('plan_id', request('plan_id')) == $plan->id ? 'checked' : '' }} required>
                                <div class="border-2 border-gray-200 rounded-xl p-4 hover:border-purple-300 transition">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center mr-3 plan-radio">
                                                <div class="w-2.5 h-2.5 bg-purple-500 rounded-full hidden"></div>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $plan->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $plan->description }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-purple-600">
                                                {{ $plan->price_per_month == 0 ? 'Gratis' : 'Rp ' . number_format($plan->price_per_month, 0, ',', '.') }}
                                            </div>
                                            <div class="text-xs text-gray-400">/bulan</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('plan_id')
                            <p id="error-plan_id" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" name="terms" class="mt-1 mr-3" required>
                            <span class="text-sm text-gray-600">
                                Saya setuju dengan 
                                <a href="#" class="text-purple-600 hover:underline">Syarat & Ketentuan</a> 
                                dan 
                                <a href="#" class="text-purple-600 hover:underline">Kebijakan Privasi</a>
                            </span>
                        </label>
                        @error('terms')
                            <p id="error-terms" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" onclick="trackStep(3, 'submit')" class="w-full py-4 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold rounded-xl hover:opacity-90 transition">
                        Buat Akun Sekarang 🎉
                    </button>
                </form>
            </div>

        </div>

        <!-- Trust Badges -->
        <div class="mt-8 text-center">
            <div class="flex items-center justify-center gap-6 text-white/60 text-sm">
                <div class="flex items-center">
                    <i class="fas fa-shield-alt mr-2"></i>
                    <span>Data Aman</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-lock mr-2"></i>
                    <span>SSL Encrypted</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-undo mr-2"></i>
                    <span>30-Day Guarantee</span>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Password strength checker
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            if (password.length >= 8) strength += 25;
            if (password.match(/[a-z]/)) strength += 25;
            if (password.match(/[A-Z]/)) strength += 25;
            if (password.match(/[0-9]/) || password.match(/[^a-zA-Z]/)) strength += 25;
            
            strengthBar.style.width = strength + '%';
            
            if (strength <= 25) {
                strengthBar.className = 'password-strength bg-red-500 rounded-full';
                strengthText.textContent = 'Lemah - Tambahkan huruf besar & angka';
                strengthText.className = 'text-xs text-red-500 mt-1';
            } else if (strength <= 50) {
                strengthBar.className = 'password-strength bg-orange-500 rounded-full';
                strengthText.textContent = 'Cukup - Bisa lebih kuat';
                strengthText.className = 'text-xs text-orange-500 mt-1';
            } else if (strength <= 75) {
                strengthBar.className = 'password-strength bg-yellow-500 rounded-full';
                strengthText.textContent = 'Baik - Hampir sempurna';
                strengthText.className = 'text-xs text-yellow-600 mt-1';
            } else {
                strengthBar.className = 'password-strength bg-green-500 rounded-full';
                strengthText.textContent = 'Kuat - Password aman';
                strengthText.className = 'text-xs text-green-500 mt-1';
            }
        }
        
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'fas fa-eye-slash text-gray-400';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'fas fa-eye text-gray-400';
            }
        }
        
        // Plan card selection styling
        document.querySelectorAll('input[name="plan_id"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.plan-card').forEach(card => {
                    card.classList.remove('selected');
                    card.querySelector('.plan-radio div').classList.add('hidden');
                    card.querySelector('.plan-radio').classList.remove('border-purple-500');
                });
                
                if (this.checked) {
                    const selectedCard = this.closest('.plan-card');
                    selectedCard.classList.add('selected');
                    selectedCard.querySelector('.plan-radio div').classList.remove('hidden');
                    selectedCard.querySelector('.plan-radio').classList.add('border-purple-500');
                }
            });
        });
        
        // Initialize first selected plan
        document.querySelector('input[name="plan_id"]:checked')?.closest('.plan-card')?.classList.add('selected');
    </script>

</body>
</html>
