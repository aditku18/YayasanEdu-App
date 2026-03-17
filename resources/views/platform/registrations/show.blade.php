<x-platform-layout>
    <x-slot name="header">Detail Registrasi</x-slot>
    <x-slot name="subtitle">{{ $registration->name }}</x-slot>

    <div class="mb-6">
        <a href="{{ route('platform.registrations.index') }}" class="text-indigo-600 hover:text-indigo-800">
            ← Kembali ke Daftar Registrasi
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-800">
            <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('warning') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
            <i class="fas fa-times-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif
    @if(session('info'))
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg text-blue-800">
            <i class="fas fa-info-circle mr-2"></i>{{ session('info') }}
        </div>
    @endif

    <div x-data="approvalManager()">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Foundation Info -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Yayasan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Yayasan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $registration->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subdomain</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $registration->subdomain }}.localhost</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $registration->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Telepon</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $registration->phone ?? '-' }}</p>
                    </div>
                    @if($registration->address)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $registration->address }}</p>
                    </div>
                    @endif
                    @if($registration->institution_type)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Institusi</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $registration->institution_type }}</p>
                    </div>
                    @endif
                    @if($registration->student_count)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah Siswa</label>
                        <p class="mt-1 text-sm text-gray-900">{{ number_format($registration->student_count) }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Verification Checklist -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-clipboard-check text-indigo-500 mr-2"></i>
                    Checklist Verifikasi
                </h3>
                
                @php
                    $adminUser = $registration->adminUser;
                    $emailVerified = $adminUser && $adminUser->hasVerifiedEmail();
                    $documentsVerified = $registration->hasVerifiedDocuments();
                    $allVerified = $emailVerified && $documentsVerified;
                @endphp

                <div class="space-y-4">
                    <!-- Email Verification -->
                    <div class="flex items-center justify-between p-4 rounded-lg {{ $emailVerified ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200' }}">
                        <div class="flex items-center gap-3">
                            @if($emailVerified)
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-green-800">Email Terverifikasi</p>
                                    <p class="text-xs text-green-600">{{ $adminUser->email }} — diverifikasi {{ $adminUser->email_verified_at->format('d M Y H:i') }}</p>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-yellow-800">Email Belum Diverifikasi</p>
                                    <p class="text-xs text-yellow-600">{{ $adminUser ? $adminUser->email : $registration->email }} — menunggu verifikasi user</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Document Verification -->
                    <div class="flex items-center justify-between p-4 rounded-lg {{ $documentsVerified ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200' }}">
                        <div class="flex items-center gap-3">
                            @if($documentsVerified)
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-green-800">Dokumen Terverifikasi</p>
                                    <p class="text-xs text-green-600">Diverifikasi {{ $registration->documents_verified_at->format('d M Y H:i') }}</p>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                                    <i class="fas fa-file-alt text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-yellow-800">Dokumen Belum Diverifikasi</p>
                                    <p class="text-xs text-yellow-600">Periksa dokumen di bawah lalu klik "Verifikasi Dokumen"</p>
                                </div>
                            @endif
                        </div>
                        @if(!$documentsVerified && $registration->hasUploadedDocuments())
                            <form method="POST" action="{{ route('platform.registrations.verify-documents', $registration) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition"
                                    onclick="return confirm('Apakah Anda yakin dokumen sudah valid dan terverifikasi?')">
                                    <i class="fas fa-check-double mr-1"></i> Verifikasi Dokumen
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Uploaded Documents -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-folder-open text-indigo-500 mr-2"></i>
                    Dokumen yang Diupload
                </h3>
                
                @php $documents = $registration->getDocumentPaths(); @endphp

                @if(count($documents) > 0)
                    <div class="space-y-3">
                        @foreach($documents as $label => $path)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border">
                                <div class="flex items-center gap-3">
                                    @php
                                        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                        $icon = match($ext) {
                                            'pdf' => 'fas fa-file-pdf text-red-500',
                                            'jpg', 'jpeg', 'png' => 'fas fa-file-image text-blue-500',
                                            default => 'fas fa-file text-gray-500',
                                        };
                                    @endphp
                                    <i class="{{ $icon }} text-lg"></i>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">{{ $label }}</p>
                                        <p class="text-xs text-gray-500">{{ basename($path) }}</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $path) }}" target="_blank" 
                                   class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50 text-gray-700 transition">
                                    <i class="fas fa-eye mr-1"></i> Lihat
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-folder-open text-4xl mb-3"></i>
                        <p class="text-sm">Tidak ada dokumen yang diupload</p>
                    </div>
                @endif
            </div>

            <!-- Status & Actions -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Registrasi</h3>
                <div class="flex items-center justify-between">
                    <div>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            @if($registration->status === 'active' || $registration->status === 'trial') bg-green-100 text-green-800
                            @elseif($registration->status === 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($registration->status) }}
                        </span>
                        <p class="text-sm text-gray-500 mt-2">
                            Terdaftar: {{ $registration->created_at->format('d M Y H:i') }}
                        </p>
                    </div>
                    
                    @if($registration->status === 'pending')
                        <div class="space-x-2">
                            @if($allVerified)
                                <button type="button" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition"
                                    @click="startApproval('{{ route('platform.registrations.approve', $registration) }}')">
                                    <i class="fas fa-check mr-1"></i> Setujui & Buat Tenant
                                </button>
                            @else
                                <button disabled class="px-4 py-2 bg-gray-300 text-gray-500 rounded-md cursor-not-allowed" 
                                    title="Email dan dokumen harus diverifikasi terlebih dahulu">
                                    <i class="fas fa-lock mr-1"></i> Setujui
                                </button>
                            @endif
                            <button onclick="showRejectForm()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                <i class="fas fa-times mr-1"></i> Tolak
                            </button>
                        </div>
                    @endif
                </div>

                @if(!$allVerified && $registration->status === 'pending')
                    <div class="mt-3 p-3 bg-yellow-50 rounded-lg border border-yellow-200 text-sm text-yellow-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        Tombol Setujui akan aktif setelah <strong>email terverifikasi</strong> dan <strong>dokumen terverifikasi</strong>.
                    </div>
                @endif

                <!-- Reject Form (Hidden by default) -->
                @if($registration->status === 'pending')
                    <div id="rejectForm" class="hidden mt-4 p-4 bg-red-50 rounded-lg">
                        <form method="POST" action="{{ route('platform.registrations.reject', $registration) }}">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                            <textarea name="reason" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                            <div class="mt-3 space-x-2">
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                    Konfirmasi Tolak
                                </button>
                                <button type="button" onclick="hideRejectForm()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Schools Info -->
            @if($registration->schools && $registration->schools->count() > 0)
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Unit Sekolah</h3>
                    <div class="space-y-3">
                        @foreach($registration->schools as $school)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $school->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $school->level }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    @if($school->status === 'active') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($school->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Admin User Info -->
            @if($registration->adminUser)
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Admin Yayasan</h3>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-2xl font-bold text-indigo-600">
                                {{ substr($registration->adminUser->name, 0, 1) }}
                            </span>
                        </div>
                        <p class="font-medium text-gray-900">{{ $registration->adminUser->name }}</p>
                        <p class="text-sm text-gray-500">{{ $registration->adminUser->email }}</p>
                        <div class="mt-2">
                            @if($registration->adminUser->hasVerifiedEmail())
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i> Email Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">
                                    <i class="fas fa-clock mr-1"></i> Email Unverified
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @elseif($registration->user)
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengguna</h3>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-2xl font-bold text-indigo-600">
                                {{ substr($registration->user->name, 0, 1) }}
                            </span>
                        </div>
                        <p class="font-medium text-gray-900">{{ $registration->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $registration->user->email }}</p>
                    </div>
                </div>
            @else
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Admin Yayasan</h3>
                    <div class="text-center py-4 text-gray-400">
                        <i class="fas fa-user-slash text-3xl mb-2"></i>
                        <p class="text-sm">Tidak ada user admin terasosiasi</p>
                    </div>
                </div>
            @endif

            <!-- Plan Info -->
            @if($registration->plan)
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Paket Langganan</h3>
                    <div>
                        <p class="font-medium text-gray-900">{{ $registration->plan->name }}</p>
                        <p class="text-sm text-gray-500">{{ $registration->plan->description }}</p>
                        <p class="text-lg font-semibold text-indigo-600 mt-2">
                            Rp {{ number_format($registration->plan->price_per_month, 0, ',', '.') }}/bulan
                        </p>
                    </div>
                </div>
            @endif

            <!-- Plugins Info -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Plugin Tambahan</h3>
                @if($registration->plugins->count() > 0)
                    <div class="space-y-3">
                        @foreach($registration->plugins as $plugin)
                            <div class="flex justify-between items-center p-2 bg-indigo-50 rounded-lg border border-indigo-100">
                                <span class="text-sm font-medium text-indigo-900">{{ $plugin->name }}</span>
                                <span class="text-xs font-semibold text-indigo-700">Rp {{ number_format($plugin->price, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        <div class="pt-2 border-t flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-900">Total Plugin</span>
                            <span class="text-sm font-bold text-indigo-600">Rp {{ number_format($registration->plugins->sum('price'), 0, ',', '.') }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4 text-gray-400">
                        <i class="fas fa-puzzle-piece text-3xl mb-2"></i>
                        <p class="text-sm">Tidak ada plugin tambahan</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Progress Modal (Alpine.js) -->
        <div x-show="isOpen" 
         class="fixed inset-0 z-[100] overflow-y-auto" 
         style="display: none;"
         x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900 opacity-75 backdrop-blur-sm"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                <div class="bg-white px-8 pt-8 pb-8">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 mb-6">
                            <svg class="h-8 w-8 text-indigo-600 animate-spin" x-show="!isComplete && !isError" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <div x-show="isComplete" class="text-green-500">
                                <i class="fas fa-check-circle text-4xl"></i>
                            </div>
                            <div x-show="isError" class="text-red-500">
                                <i class="fas fa-exclamation-circle text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-xl leading-6 font-bold text-gray-900 mb-2" x-text="title">Memproses Approval</h3>
                        <p class="text-sm text-gray-500 mb-8" x-text="description">Mohon tunggu sebentar, kami sedang menyiapkan infrastruktur untuk yayasan ini.</p>
                        
                        <!-- Progress Bar -->
                        <div class="relative pt-1">
                            <div class="flex mb-2 items-center justify-between">
                                <div>
                                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-indigo-600 bg-indigo-200" x-text="progress + '%'">
                                        0%
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-semibold inline-block text-indigo-600" x-text="currentStepText">
                                        Menyiapkan...
                                    </span>
                                </div>
                            </div>
                            <div class="overflow-hidden h-3 mb-4 text-xs flex rounded-full bg-indigo-100">
                                <div :style="'width: ' + progress + '%'" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-indigo-500 to-purple-600 transition-all duration-500 ease-out"></div>
                            </div>
                        </div>

                        <!-- Step Checklist -->
                        <div class="mt-6 space-y-3 text-left">
                            <template x-for="(step, index) in steps" :key="index">
                                <div class="flex items-center text-sm">
                                    <div class="flex-shrink-0 mr-3">
                                        <template x-if="index < currentStep">
                                            <i class="fas fa-check-circle text-green-500"></i>
                                        </template>
                                        <template x-if="index === currentStep && !isError">
                                            <i class="fas fa-circle-notch fa-spin text-indigo-500"></i>
                                        </template>
                                        <template x-if="index === currentStep && isError">
                                            <i class="fas fa-times-circle text-red-500"></i>
                                        </template>
                                        <template x-if="index > currentStep">
                                            <i class="far fa-circle text-gray-300"></i>
                                        </template>
                                    </div>
                                    <span :class="{'text-gray-900 font-medium': index === currentStep, 'text-gray-400': index > currentStep, 'text-green-600': index < currentStep}" x-text="step"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-8 py-4 flex flex-row-reverse" x-show="isError || isComplete">
                    <button type="button" 
                            @click="isComplete ? window.location.href = redirectUrl : isOpen = false" 
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white sm:ml-3 sm:w-auto transition-all"
                            :class="isError ? 'bg-red-600 hover:bg-red-700' : 'bg-indigo-600 hover:bg-indigo-700'">
                        <span x-text="isError ? 'Tutup & Perbaiki' : 'Selesai'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        function approvalManager() {
            return {
                isOpen: false,
                isComplete: false,
                isError: false,
                progress: 0,
                currentStep: 0,
                title: 'Memproses Approval',
                description: 'Mohon tunggu sebentar, kami sedang menyiapkan infrastruktur untuk yayasan ini.',
                redirectUrl: '',
                steps: [
                    'Validasi data dan verifikasi email',
                    'Pembuatan Database Tenant (Migrations)',
                    'Sinkronisasi Akun Administrator',
                    'Finalisasi dan Aktivasi Layanan'
                ],
                get currentStepText() {
                    if (this.isError) return 'Terjadi Kesalahan';
                    if (this.isComplete) return 'Selesai';
                    return this.steps[this.currentStep] || 'Memproses...';
                },
                async startApproval(url) {
                    this.isOpen = true;
                    this.isComplete = false;
                    this.isError = false;
                    this.progress = 5;
                    this.currentStep = 0;
                    this.title = 'Memproses Approval';
                    
                    // Progress animation logic
                    const interval = setInterval(() => {
                        if (this.progress < 90 && !this.isError) {
                            this.progress += 1;
                            if (this.progress > 25 && this.currentStep === 0) this.currentStep = 1;
                            if (this.progress > 60 && this.currentStep === 1) this.currentStep = 2;
                            if (this.progress > 85 && this.currentStep === 2) this.currentStep = 3;
                        }
                    }, 300);

                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const result = await response.json();
                        clearInterval(interval);

                        if (result.success) {
                            this.progress = 100;
                            this.currentStep = 4;
                            this.isComplete = true;
                            this.title = 'Berhasil Di-Approve!';
                            this.description = result.message;
                            this.redirectUrl = result.redirect_url;
                        } else {
                            throw new Error(result.message || 'Terjadi kesalahan sistem.');
                        }
                    } catch (error) {
                        clearInterval(interval);
                        this.isError = true;
                        this.title = 'Gagal Approve';
                        this.description = error.message;
                    }
                }
            }
        }

        function showRejectForm() {
            document.getElementById('rejectForm').classList.remove('hidden');
        }
        
        function hideRejectForm() {
            document.getElementById('rejectForm').classList.add('hidden');
        }
    </script>
</x-platform-layout>
