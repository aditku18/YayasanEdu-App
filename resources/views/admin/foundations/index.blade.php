<x-platform-layout>
    <x-slot name="header">Data Yayasan</x-slot>
    <x-slot name="subtitle">Kelola data yayasan terdaftar</x-slot>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        <a href="{{ route('platform.foundations.index') }}" class="bg-white rounded-xl p-4 shadow-sm border {{ !$status ? 'border-primary-300 ring-1 ring-primary-100' : 'border-gray-100' }} hover:shadow-md transition-all">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Semua</p>
        </a>
        <a href="{{ route('platform.foundations.index', ['status' => 'pending']) }}" class="bg-white rounded-xl p-4 shadow-sm border {{ $status === 'pending' ? 'border-yellow-300 ring-1 ring-yellow-100' : 'border-gray-100' }} hover:shadow-md transition-all">
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Pending</p>
        </a>
        <a href="{{ route('platform.foundations.index', ['status' => 'trial']) }}" class="bg-white rounded-xl p-4 shadow-sm border {{ $status === 'trial' ? 'border-blue-300 ring-1 ring-blue-100' : 'border-gray-100' }} hover:shadow-md transition-all">
            <p class="text-2xl font-bold text-blue-600">{{ $stats['trial'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Trial</p>
        </a>
        <a href="{{ route('platform.foundations.index', ['status' => 'active']) }}" class="bg-white rounded-xl p-4 shadow-sm border {{ $status === 'active' ? 'border-green-300 ring-1 ring-green-100' : 'border-gray-100' }} hover:shadow-md transition-all">
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Aktif</p>
        </a>
        <a href="{{ route('platform.foundations.index', ['status' => 'expired']) }}" class="bg-white rounded-xl p-4 shadow-sm border {{ $status === 'expired' ? 'border-red-300 ring-1 ring-red-100' : 'border-gray-100' }} hover:shadow-md transition-all">
            <p class="text-2xl font-bold text-red-600">{{ $stats['expired'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Expired</p>
        </a>
        <a href="{{ route('platform.foundations.index', ['status' => 'rejected']) }}" class="bg-white rounded-xl p-4 shadow-sm border {{ $status === 'rejected' ? 'border-gray-400 ring-1 ring-gray-200' : 'border-gray-100' }} hover:shadow-md transition-all">
            <p class="text-2xl font-bold text-gray-600">{{ $stats['rejected'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Ditolak</p>
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">
                Daftar Yayasan
                @if($status)
                    <span class="text-sm font-normal text-gray-500">— {{ ucfirst($status) }}</span>
                @endif
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                        <th class="px-4 py-3 border-r border-white font-medium">Informasi Yayasan</th>
                        <th class="px-4 py-3 border-r border-white font-medium">Subdomain</th>
                        <th class="px-4 py-3 border-r border-white font-medium">Status & Trial</th>
                        <th class="px-4 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($foundations as $foundation)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <p class="text-sm font-semibold text-gray-900">{{ $foundation->name }}</p>
                                <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    {{ $foundation->email }}
                                </p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm font-medium text-gray-700 bg-gray-100 px-2 py-1 rounded">
                                    {{ $foundation->subdomain }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                {{-- Status Badge --}}
                                @if($foundation->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mb-1">
                                        Pending Approval
                                    </span>
                                @elseif($foundation->status === 'trial')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-1">
                                        Trial
                                    </span>
                                    {{-- Trial Info --}}
                                    @if($foundation->trial_ends_at)
                                        @php $daysLeft = $foundation->daysLeftInTrial(); @endphp
                                        @if($daysLeft > 3)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-600">
                                                {{ $daysLeft }} hari tersisa
                                            </span>
                                        @elseif($daysLeft > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-orange-50 text-orange-600 animate-pulse">
                                                ⚠ {{ $daysLeft }} hari lagi!
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-red-50 text-red-600">
                                                Segera expired
                                            </span>
                                        @endif
                                    @endif
                                @elseif($foundation->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-1">
                                        Aktif
                                    </span>
                                @elseif($foundation->status === 'expired')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mb-1">
                                        Trial Expired
                                    </span>
                                @elseif($foundation->status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-700 mb-1">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mb-1">
                                        {{ ucfirst($foundation->status) }}
                                    </span>
                                @endif
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $foundation->created_at->format('d M Y, H:i') }}</p>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="relative inline-block text-left" x-data="{ open: false }">
                                    <button @click="open = !open" @click.outside="open = false" @keydown.escape.window="open = false" type="button" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        Aksi
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 z-10 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 focus:outline-none" style="display: none;">
                                        @if($foundation->status === 'pending')
                                            <form action="{{ route('platform.foundations.approve', $foundation->id) }}" method="POST" class="block">
                                                @csrf
                                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-green-700 hover:bg-green-50" onclick="return confirm('Approve yayasan ini dan mulai trial 14 hari?')">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('platform.foundations.reject', $foundation->id) }}" method="POST" class="block">
                                                @csrf
                                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50" onclick="return confirm('Tolak pendaftaran yayasan ini?')">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    Reject
                                                </button>
                                            </form>
                                        @elseif($foundation->status === 'trial' || $foundation->status === 'active')
                                            <a href="{{ route('platform.foundations.show', $foundation->id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.514 1.514-3.182 3-5.254 3H5.414C3.182 15 1.514 13.486 0 12c1.514-1.486 3.182-3 5.254-3h12.092c2.072 0 3.74 1.514 5.254 3z"/></svg>
                                                Lihat Detail
                                            </a>
                                            <a href="{{ route('platform.foundations.edit', $foundation->id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5h-3V7a2 2 0 00-2-2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 3.5a2 2 0 013 0V7a2 2 0 01-3 0V3.5z"/></svg>
                                                Edit
                                            </a>
                                            @if($foundation->status === 'active' || $foundation->status === 'trial')
                                                <form action="{{ route('platform.foundations.suspend', $foundation->id) }}" method="POST" class="block">
                                                    @csrf
                                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-orange-700 hover:bg-orange-50" onclick="return confirm('Tangguhkan yayasan ini?')">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        Tangguhkan
                                                    </button>
                                                </form>
                                            @endif
                                            @if($foundation->status === 'trial')
                                                <form action="{{ route('platform.foundations.extend-trial', $foundation->id) }}" method="POST" class="block">
                                                    @csrf
                                                    <input type="hidden" name="days" value="7">
                                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-blue-700 hover:bg-blue-50" onclick="return confirm('Perpanjang trial 7 hari?')">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        +7 Hari Trial
                                                    </button>
                                                </form>
                                            @endif
                                            <div class="border-t border-gray-100 my-1"></div>
                                            <form action="{{ route('platform.foundations.destroy', $foundation->id) }}" method="POST" class="block" onsubmit="event.preventDefault(); if(confirm('Hapus yayasan ini? Data tidak dapat dikembalikan.')) { this.submit(); }">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @elseif($foundation->status === 'suspended' || $foundation->status === 'expired')
                                            <a href="{{ route('platform.foundations.show', $foundation->id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.514 1.514-3.182 3-5.254 3H5.414C3.182 15 1.514 13.486 0 12c1.514-1.486 3.182-3 5.254-3h12.092c2.072 0 3.74 1.514 5.254 3z"/></svg>
                                                Lihat Detail
                                            </a>
                                            <a href="{{ route('platform.foundations.edit', $foundation->id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5h-3V7a2 2 0 00-2-2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 3.5a2 2 0 013 0V7a2 2 0 01-3 0V3.5z"/></svg>
                                                Edit
                                            </a>
                                            <form action="{{ route('platform.foundations.activate', $foundation->id) }}" method="POST" class="block">
                                                @csrf
                                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-green-700 hover:bg-green-50" onclick="return confirm('Aktifkan yayasan ini?')">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Aktifkan
                                                </button>
                                            </form>
                                            <div class="border-t border-gray-100 my-1"></div>
                                            <form action="{{ route('platform.foundations.destroy', $foundation->id) }}" method="POST" class="block" onsubmit="event.preventDefault(); if(confirm('Hapus yayasan ini? Data tidak dapat dikembalikan.')) { this.submit(); }">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('platform.foundations.show', $foundation->id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.514 1.514-3.182 3-5.254 3H5.414C3.182 15 1.514 13.486 0 12c1.514-1.486 3.182-3 5.254-3h12.092c2.072 0 3.74 1.514 5.254 3z"/></svg>
                                                Lihat Detail
                                            </a>
                                            <a href="{{ route('platform.foundations.edit', $foundation->id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5h-3V7a2 2 0 00-2-2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 3.5a2 2 0 013 0V7a2 2 0 01-3 0V3.5z"/></svg>
                                                Edit
                                            </a>
                                            <div class="border-t border-gray-100 my-1"></div>
                                            <form action="{{ route('platform.foundations.destroy', $foundation->id) }}" method="POST" class="block" onsubmit="event.preventDefault(); if(confirm('Hapus yayasan ini? Data tidak dapat dikembalikan.')) { this.submit(); }">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                @if($status)
                                    Belum ada yayasan dengan status "{{ $status }}".
                                @else
                                    Belum ada data yayasan.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $foundations->links() }}
        </div>
    </div>
</x-platform-layout>
