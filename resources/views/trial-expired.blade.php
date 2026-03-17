<x-guest-layout>
    <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
        <div class="w-20 h-20 rounded-full bg-red-50 text-red-500 flex items-center justify-center mb-6 shadow-sm">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-3">Masa Trial Telah Berakhir</h2>
        <p class="text-gray-500 max-w-md mx-auto mb-4">
            Masa percobaan gratis 14 hari untuk yayasan Anda telah berakhir. 
            Untuk melanjutkan menggunakan platform EduSaaS, silakan upgrade ke paket berbayar.
        </p>

        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-6 max-w-sm w-full">
            <p class="text-sm font-medium text-gray-700 mb-2">Langkah selanjutnya:</p>
            <ul class="text-sm text-gray-500 text-left space-y-2">
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-primary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                    Hubungi admin platform untuk informasi paket langganan
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-primary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                    Pilih paket yang sesuai dan lakukan pembayaran
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-primary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                    Akses akan aktif kembali setelah pembayaran diverifikasi
                </li>
            </ul>
        </div>

        <div class="flex items-center gap-3">
            <a href="mailto:admin@edusaas.com" class="px-5 py-2.5 bg-primary-600 text-white rounded-xl font-medium shadow-sm hover:bg-primary-700 transition-colors text-sm">
                Hubungi Admin
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-colors text-sm">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
