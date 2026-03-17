<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Yayasan - YayasanEdu Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .step-indicator {
            transition: all 0.3s ease;
        }
        .step-indicator.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .form-input {
            transition: border-color 0.3s ease;
        }
        .form-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .package-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .package-card:hover {
            transform: translateY(-5px);
        }
        .package-card.selected {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <i class="fas fa-graduation-cap text-2xl text-indigo-600 mr-3"></i>
                    <h1 class="text-xl font-bold text-gray-900">YayasanEdu Platform</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 py-8">
        <!-- Progress Steps -->
        <div class="mb-8">
            <!-- Progress Bar -->
            <div class="relative mb-6">
                <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 rounded-full"></div>
                <div class="absolute top-5 left-0 h-1 bg-indigo-600 rounded-full transition-all duration-500 ease-out" 
                     style="width: {{ ($step / 4) * 100 }}%"></div>
                
                <div class="relative flex items-center justify-between">
                    <div class="step-indicator {{ $step >= 1 ? 'active' : '' }} w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold transition-all duration-300 {{ $step >= 1 ? 'bg-indigo-600 shadow-lg' : 'bg-gray-300' }} z-10">
                        <span class="text-sm">{!! $step >= 1 ? '<i class="fas fa-check text-xs"></i>' : '1' !!}</span>
                    </div>
                    <div class="step-indicator {{ $step >= 2 ? 'active' : '' }} w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold transition-all duration-300 {{ $step >= 2 ? 'bg-indigo-600 shadow-lg' : 'bg-gray-300' }} z-10">
                        <span class="text-sm">{!! $step >= 2 ? '<i class="fas fa-check text-xs"></i>' : '2' !!}</span>
                    </div>
                    <div class="step-indicator {{ $step >= 3 ? 'active' : '' }} w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold transition-all duration-300 {{ $step >= 3 ? 'bg-indigo-600 shadow-lg' : 'bg-gray-300' }} z-10">
                        <span class="text-sm">{!! $step >= 3 ? '<i class="fas fa-check text-xs"></i>' : '3' !!}</span>
                    </div>
                    <div class="step-indicator {{ $step >= 4 ? 'active' : '' }} w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold transition-all duration-300 {{ $step >= 4 ? 'bg-indigo-600 shadow-lg' : 'bg-gray-300' }} z-10">
                        <span class="text-sm">{!! $step >= 4 ? '<i class="fas fa-check text-xs"></i>' : '4' !!}</span>
                    </div>
                </div>
            </div>
            
            <!-- Step Labels -->
            <div class="flex justify-between mt-8">
                <div class="text-center flex-1">
                    <div class="{{ $step >= 1 ? 'text-indigo-600 font-bold' : 'text-gray-500' }} text-sm font-medium transition-colors duration-300">
                        Data Institusi
                    </div>
                    <div class="{{ $step >= 1 ? 'text-indigo-500' : 'text-gray-400' }} text-xs mt-1">
                        Informasi yayasan
                    </div>
                </div>
                <div class="text-center flex-1">
                    <div class="{{ $step >= 2 ? 'text-indigo-600 font-bold' : 'text-gray-500' }} text-sm font-medium transition-colors duration-300">
                        Upload Dokumen
                    </div>
                    <div class="{{ $step >= 2 ? 'text-indigo-500' : 'text-gray-400' }} text-xs mt-1">
                        Verifikasi berkas
                    </div>
                </div>
                <div class="text-center flex-1">
                    <div class="{{ $step >= 3 ? 'text-indigo-600 font-bold' : 'text-gray-500' }} text-sm font-medium transition-colors duration-300">
                        Pilih Paket
                    </div>
                    <div class="{{ $step >= 3 ? 'text-indigo-500' : 'text-gray-400' }} text-xs mt-1">
                        Langganan & plugin
                    </div>
                </div>
                <div class="text-center flex-1">
                    <div class="{{ $step >= 4 ? 'text-indigo-600 font-bold' : 'text-gray-500' }} text-sm font-medium transition-colors duration-300">
                        Akun Admin
                    </div>
                    <div class="{{ $step >= 4 ? 'text-indigo-500' : 'text-gray-400' }} text-xs mt-1">
                        Data administrator
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            @if($step == 1)
                <!-- Step 1: Institution Data -->
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Data Institusi</h2>
                
                <form action="{{ route('register.foundation.step1.post') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Yayasan/Sekolah <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="foundation_name" required
                                   class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none"
                                   value="{{ old('foundation_name', $data['step1']['foundation_name'] ?? '') }}">
                            @error('foundation_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jenjang Pendidikan <span class="text-red-500">*</span>
                            </label>
                            <select name="institution_type" required
                                    class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none">
                                <option value="">Pilih Jenis</option>
                                <option value="Yayasan" {{ old('institution_type', $data['step1']['institution_type'] ?? '') == 'Yayasan' ? 'selected' : '' }}>Yayasan</option>
                                <option value="Sekolah" {{ old('institution_type', $data['step1']['institution_type'] ?? '') == 'Sekolah' ? 'selected' : '' }}>Sekolah</option>
                                <option value="Lembaga Kursus" {{ old('institution_type', $data['step1']['institution_type'] ?? '') == 'Lembaga Kursus' ? 'selected' : '' }}>Lembaga Kursus</option>
                            </select>
                            @error('institution_type')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Education Levels -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenjang Pendidikan <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="education_levels[]" value="TK" 
                                       {{ in_array('TK', old('education_levels', $data['step1']['education_levels'] ?? [])) ? 'checked' : '' }}
                                       class="mr-2">
                                <span>TK</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="education_levels[]" value="SD" 
                                       {{ in_array('SD', old('education_levels', $data['step1']['education_levels'] ?? [])) ? 'checked' : '' }}
                                       class="mr-2">
                                <span>SD</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="education_levels[]" value="SMP" 
                                       {{ in_array('SMP', old('education_levels', $data['step1']['education_levels'] ?? [])) ? 'checked' : '' }}
                                       class="mr-2">
                                <span>SMP</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="education_levels[]" value="SMA" 
                                       {{ in_array('SMA', old('education_levels', $data['step1']['education_levels'] ?? [])) ? 'checked' : '' }}
                                       class="mr-2">
                                <span>SMA</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="education_levels[]" value="SMK" 
                                       {{ in_array('SMK', old('education_levels', $data['step1']['education_levels'] ?? [])) ? 'checked' : '' }}
                                       class="mr-2">
                                <span>SMK</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="education_levels[]" value="Lainnya" 
                                       {{ in_array('Lainnya', old('education_levels', $data['step1']['education_levels'] ?? [])) ? 'checked' : '' }}
                                       class="mr-2">
                                <span>Lainnya</span>
                            </label>
                        </div>
                        @error('education_levels')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email Resmi <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" required
                                   class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none"
                                   value="{{ old('email', $data['step1']['email'] ?? '') }}">
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" required
                                   class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none"
                                   value="{{ old('phone', $data['step1']['phone'] ?? '') }}">
                            @error('phone')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea name="address" required rows="3"
                                  class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none">{{ old('address', $data['step1']['address'] ?? '') }}</textarea>
                        @error('address')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Provinsi <span class="text-red-500">*</span>
                            </label>
                            <select name="province" required id="province"
                                    class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province['name'] }}" 
                                            {{ old('province', $data['step1']['province'] ?? '') == $province['name'] ? 'selected' : '' }}>
                                        {{ $province['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kabupaten/Kota <span class="text-red-500">*</span>
                            </label>
                            <select name="regency" required id="regency"
                                    class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none">
                                <option value="">Pilih Kabupaten/Kota</option>
                            </select>
                            @error('regency')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                NPSN
                            </label>
                            <input type="text" name="npsn"
                                   class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none"
                                   value="{{ old('npsn', $data['step1']['npsn'] ?? '') }}">
                            @error('npsn')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Siswa <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="student_count" required min="1"
                                   class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none"
                                   value="{{ old('student_count', $data['step1']['student_count'] ?? '') }}">
                            @error('student_count')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Website (Opsional)
                        </label>
                        <input type="url" name="website"
                               class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none"
                               value="{{ old('website', $data['step1']['website'] ?? '') }}"
                               placeholder="https://">
                        @error('website')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-200">
                            Lanjutkan <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>

            @elseif($step == 2)
                <!-- Step 2: Document Upload -->
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Upload Dokumen</h2>
                
                <form action="{{ route('register.foundation.step2.post') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Penting:</strong> Pastikan semua dokumen yang diupload jelas dan dapat dibaca. 
                            Format yang diterima: PDF, JPG, PNG.
                        </p>
                    </div>

                    <!-- Document Upload Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- SK Pendirian -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                SK Pendirian <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-indigo-500 transition-colors">
                                <input type="file" name="sk_pendirian" id="sk_pendirian" required
                                       accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                <label for="sk_pendirian" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600">Klik untuk upload dokumen</p>
                                    <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (Max 2MB)</p>
                                </label>
                                <div id="sk_pendirian_preview" class="mt-3 hidden">
                                    <div class="flex items-center justify-between bg-green-50 p-2 rounded">
                                        <span class="text-sm text-green-700 font-medium truncate"></span>
                                        <button type="button" onclick="removeFile('sk_pendirian')" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('sk_pendirian')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Dokumen NPSN -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Dokumen NPSN
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-indigo-500 transition-colors">
                                <input type="file" name="npsn_document" id="npsn_document"
                                       accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                <label for="npsn_document" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600">Klik untuk upload dokumen</p>
                                    <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (Max 2MB)</p>
                                </label>
                                <div id="npsn_document_preview" class="mt-3 hidden">
                                    <div class="flex items-center justify-between bg-green-50 p-2 rounded">
                                        <span class="text-sm text-green-700 font-medium truncate"></span>
                                        <button type="button" onclick="removeFile('npsn_document')" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('npsn_document')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Logo Yayasan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Logo Yayasan <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-indigo-500 transition-colors">
                                <input type="file" name="logo" id="logo" required
                                       accept=".jpg,.jpeg,.png" class="hidden">
                                <label for="logo" class="cursor-pointer">
                                    <i class="fas fa-image text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600">Klik untuk upload logo</p>
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG (Max 1MB)</p>
                                </label>
                                <div id="logo_preview" class="mt-3 hidden">
                                    <div class="flex items-center justify-between bg-green-50 p-2 rounded">
                                        <span class="text-sm text-green-700 font-medium truncate"></span>
                                        <button type="button" onclick="removeFile('logo')" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('logo')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Foto Gedung -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Gedung <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-indigo-500 transition-colors">
                                <input type="file" name="building_photo" id="building_photo" required
                                       accept=".jpg,.jpeg,.png" class="hidden">
                                <label for="building_photo" class="cursor-pointer">
                                    <i class="fas fa-building text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600">Klik untuk upload foto</p>
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG (Max 2MB)</p>
                                </label>
                                <div id="building_photo_preview" class="mt-3 hidden">
                                    <div class="flex items-center justify-between bg-green-50 p-2 rounded">
                                        <span class="text-sm text-green-700 font-medium truncate"></span>
                                        <button type="button" onclick="removeFile('building_photo')" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('building_photo')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- KTP Penanggung Jawab -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                KTP Penanggung Jawab <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-indigo-500 transition-colors">
                                <input type="file" name="ktp" id="ktp" required
                                       accept=".jpg,.jpeg,.png" class="hidden">
                                <label for="ktp" class="cursor-pointer">
                                    <i class="fas fa-id-card text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600">Klik untuk upload KTP</p>
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG (Max 1MB)</p>
                                </label>
                                <div id="ktp_preview" class="mt-3 hidden">
                                    <div class="flex items-center justify-between bg-green-50 p-2 rounded">
                                        <span class="text-sm text-green-700 font-medium truncate"></span>
                                        <button type="button" onclick="removeFile('ktp')" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('ktp')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('register.foundation.step1') }}" 
                           class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-200">
                            Lanjutkan <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>

            @elseif($step == 3)
                <!-- Step 3: Package Selection -->
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Pilih Paket Langganan</h2>
                
                <form action="{{ route('register.foundation.step3.post') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($plans as $plan)
                            <div class="package-card border-2 border-gray-200 rounded-lg p-6 {{ (old('plan_id', $data['step3']['plan_id'] ?? '')) == $plan->id ? 'selected' : '' }}"
                                 onclick="selectPackage({{ $plan->id }})">
                                <div class="text-center">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                                    <div class="text-3xl font-bold text-indigo-600 mb-4">
                                        Rp {{ number_format($plan->price_per_month, 0, ',', '.') }}
                                        <span class="text-sm text-gray-500">/bulan</span>
                                    </div>
                                    
                                    <ul class="text-left space-y-2 mb-6">
                                        <li class="flex items-center">
                                            <i class="fas fa-check text-green-500 mr-2"></i>
                                            <span>{{ $plan->max_students }} Siswa</span>
                                        </li>
                                        <li class="flex items-center">
                                            <i class="fas fa-check text-green-500 mr-2"></i>
                                            <span>{{ $plan->plugin_slots }} Plugin</span>
                                        </li>
                                        @if($plan->included_plugins && count($plan->included_plugins) > 0)
                                            <li class="flex items-center">
                                                <i class="fas fa-check text-green-500 mr-2"></i>
                                                <span>{{ count($plan->included_plugins) }} Plugin Gratis</span>
                                            </li>
                                        @endif
                                    </ul>
                                    
                                    <input type="radio" name="plan_id" value="{{ $plan->id }}" 
                                           {{ (old('plan_id', $data['step3']['plan_id'] ?? '')) == $plan->id ? 'checked' : '' }}
                                           class="hidden">
                                    <button type="button" 
                                            class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition duration-200"
                                            onclick="selectPackage({{ $plan->id }})">
                                        Pilih Paket
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @error('plan_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <!-- Additional Plugins -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Plugin Tambahan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($plugins as $plugin)
                                <label class="flex items-center p-4 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="additional_plugins[]" value="{{ $plugin->id }}"
                                           {{ in_array($plugin->id, old('additional_plugins', $data['step3']['additional_plugins'] ?? [])) ? 'checked' : '' }}
                                           class="mr-3">
                                    <div class="flex-1">
                                        <div class="font-medium">{{ $plugin->name }}</div>
                                        <div class="text-sm text-gray-500">Rp {{ number_format($plugin->price, 0, ',', '.') }}/bulan</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('register.foundation.step2') }}" 
                           class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-200">
                            Lanjutkan <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>

            @elseif($step == 4)
                <!-- Step 4: Admin Account -->
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Buat Akun Admin</h2>
                
                <form action="{{ route('register.foundation.step4.post') }}" method="POST" class="space-y-6" id="registrationForm">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="admin_name" required
                                   class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none"
                                   value="{{ old('admin_name') }}">
                            @error('admin_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="admin_email" required
                                   class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none"
                                   value="{{ old('admin_email') }}">
                            @error('admin_email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor HP <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="admin_phone" required
                               class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none"
                               value="{{ old('admin_phone') }}">
                        @error('admin_phone')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" required
                                   class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none">
                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" required
                                   class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none">
                            @error('password_confirmation')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('register.foundation.step3') }}" 
                           class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-200">
                            <i class="fas fa-check mr-2"></i> Selesai Registrasi
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="text-center">
                <p>&copy; 2024 YayasanEdu Platform. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Package selection
        function selectPackage(planId) {
            document.querySelectorAll('input[name="plan_id"]').forEach(radio => {
                radio.checked = radio.value == planId;
            });
            
            document.querySelectorAll('.package-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            event.currentTarget.classList.add('selected');
        }

        // Load regencies based on province
        document.getElementById('province')?.addEventListener('change', function() {
            const provinceName = this.value;
            const regencySelect = document.getElementById('regency');
            
            regencySelect.innerHTML = '<option value="">Memuat...</option>';
            
            fetch(`/api/regencies?province_name=${encodeURIComponent(provinceName)}`)
                .then(response => response.json())
                .then(data => {
                    regencySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                    if (data.regencies && data.regencies.length > 0) {
                        data.regencies.forEach(regency => {
                            const option = document.createElement('option');
                            option.value = regency.name;
                            option.textContent = regency.name;
                            regencySelect.appendChild(option);
                        });
                    } else {
                        regencySelect.innerHTML = '<option value="">Tidak ada kabupaten/kota tersedia</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading regencies:', error);
                    regencySelect.innerHTML = '<option value="">Gagal memuat data</option>';
                });
        });

        // Email validation
        document.querySelector('input[name="admin_email"]')?.addEventListener('blur', function() {
            const email = this.value;
            if (email && email.includes('@')) {
                fetch(`/api/check-email?email=${encodeURIComponent(email)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            this.setCustomValidity('Email ini sudah terdaftar');
                        } else {
                            this.setCustomValidity('');
                        }
                    });
            }
        });

        // File upload handlers
        function setupFileUpload(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            
            if (input && preview) {
                input.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        preview.classList.remove('hidden');
                        const fileName = preview.querySelector('span');
                        fileName.textContent = file.name;
                        
                        // Change border color
                        this.closest('.border-dashed').classList.add('border-green-500', 'bg-green-50');
                    }
                });
            }
        }

        function removeFile(inputId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(inputId + '_preview');
            
            if (input) {
                input.value = '';
                input.closest('.border-dashed').classList.remove('border-green-500', 'bg-green-50');
            }
            
            if (preview) {
                preview.classList.add('hidden');
            }
        }

        // Initialize file upload handlers
        document.addEventListener('DOMContentLoaded', function() {
            setupFileUpload('sk_pendirian', 'sk_pendirian_preview');
            setupFileUpload('npsn_document', 'npsn_document_preview');
            setupFileUpload('logo', 'logo_preview');
            setupFileUpload('building_photo', 'building_photo_preview');
            setupFileUpload('ktp', 'ktp_preview');
            
            // Form submission debug
            const form = document.getElementById('registrationForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Form submitting...', {
                        action: form.action,
                        method: form.method,
                        formData: new FormData(form)
                    });
                    
                    // Basic validation
                    const adminName = form.querySelector('input[name="admin_name"]').value;
                    const adminEmail = form.querySelector('input[name="admin_email"]').value;
                    const adminPhone = form.querySelector('input[name="admin_phone"]').value;
                    const password = form.querySelector('input[name="password"]').value;
                    const passwordConfirm = form.querySelector('input[name="password_confirmation"]').value;
                    
                    console.log('Form validation:', {
                        adminName: adminName ? '✓' : '✗',
                        adminEmail: adminEmail ? '✓' : '✗', 
                        adminPhone: adminPhone ? '✓' : '✗',
                        password: password ? '✓' : '✗',
                        passwordConfirm: passwordConfirm ? '✓' : '✗',
                        passwordsMatch: password === passwordConfirm ? '✓' : '✗'
                    });
                    
                    if (!adminName || !adminEmail || !adminPhone || !password || !passwordConfirm) {
                        e.preventDefault();
                        alert('Mohon lengkapi semua field yang wajib diisi!');
                        return false;
                    }
                    
                    if (password !== passwordConfirm) {
                        e.preventDefault();
                        alert('Password dan konfirmasi password harus cocok!');
                        return false;
                    }
                    
                    console.log('Form validation passed, submitting...');
                });
            }
        });
    </script>
</body>
</html>
