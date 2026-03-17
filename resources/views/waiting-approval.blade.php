<x-guest-layout>
    <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
        @if(($status ?? 'pending') === 'trial' || ($status ?? 'pending') === 'active')
            <div class="w-20 h-20 rounded-full bg-green-50 text-green-500 flex items-center justify-center mb-6 shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Pendaftaran Disetujui!</h2>
            <p class="text-gray-500 max-w-md mx-auto mb-8">
                Yayasan <strong>{{ $foundation->name ?? '' }}</strong> telah berhasil disetujui. 
                Anda sekarang dapat masuk ke dashboard sekolah melalui tautan di bawah ini.
            </p>
            
            <a href="http://{{ ($foundation->subdomain ?? '') . (str_contains($foundation->subdomain ?? '', '.') ? '' : '.localhost') }}:8000/login" 
               class="px-6 py-2.5 bg-primary-600 text-white rounded-xl font-medium shadow-sm hover:bg-primary-700 transition-colors mb-4 inline-block">
                Buka Dashboard Sekolah
            </a>
        @elseif(($status ?? 'pending') === 'rejected')
            <div class="w-20 h-20 rounded-full bg-red-50 text-red-500 flex items-center justify-center mb-6 shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Pendaftaran Ditolak</h2>
            <p class="text-gray-500 max-w-md mx-auto mb-8">
                Mohon maaf, pendaftaran yayasan Anda belum dapat kami setujui saat ini. 
                Silakan hubungi administrator platform untuk informasi lebih lanjut.
            </p>
        @else
            <div class="w-20 h-20 rounded-full bg-yellow-50 text-yellow-500 flex items-center justify-center mb-6 shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Pendaftaran Sedang Ditinjau</h2>
            <p class="text-gray-500 max-w-md mx-auto mb-8">
                Terima kasih telah mendaftar di SIS Platform EduSaaS. Saat ini tim kami sedang meninjau pendaftaran yayasan Anda. 
                Mohon tunggu hingga proses persetujuan selesai. Kami akan mengirimkan notifikasi via email kepada Anda.
            </p>
        @endif
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white rounded-xl font-medium shadow-sm hover:bg-gray-800 transition-colors">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
