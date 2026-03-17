{{-- Trial Banner Component --}}
@if(isset($trialDaysLeft) && isset($trialEndsAt))
    <div class="mb-6 {{ $trialDaysLeft <= 3 ? 'bg-orange-50 border-orange-200' : 'bg-blue-50 border-blue-200' }} border rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg {{ $trialDaysLeft <= 3 ? 'bg-orange-100' : 'bg-blue-100' }} flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 {{ $trialDaysLeft <= 3 ? 'text-orange-600' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold {{ $trialDaysLeft <= 3 ? 'text-orange-800' : 'text-blue-800' }}">
                        {{ $trialDaysLeft <= 3 ? '⚠️ Trial Akan Berakhir' : '🎯 Masa Trial Aktif' }}
                    </p>
                    <p class="text-sm {{ $trialDaysLeft <= 3 ? 'text-orange-700' : 'text-blue-700' }}">
                        {{ $trialDaysLeft }} hari tersisa ({{ \Carbon\Carbon::parse($trialEndsAt)->format('d M Y') }})
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($trialDaysLeft <= 3)
                    <a href="mailto:admin@edusaas.com?subject=Upgrade Paket - {{ tenant('id') }}" 
                       class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                        Upgrade Sekarang
                    </a>
                @else
                    <a href="mailto:admin@edusaas.com?subject=Informasi Paket - {{ tenant('id') }}" 
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                        Lihat Paket
                    </a>
                @endif
                <button onclick="this.parentElement.parentElement.parentElement.style.display='none'" 
                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endif
