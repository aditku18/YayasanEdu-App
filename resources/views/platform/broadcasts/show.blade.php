<x-platform-layout>
    <x-slot name="header">Detail Broadcast</x-slot>
    <x-slot name="subtitle">Lihat detail dan status broadcast</x-slot>

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Broadcast Details Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $broadcast->title }}</h2>
                        <p class="text-sm text-gray-500 mt-1">Dibuat {{ $broadcast->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-3 py-1 text-sm font-medium rounded-full 
                        @if($broadcast->is_sent) bg-green-100 text-green-800
                        @elseif($broadcast->is_draft) bg-gray-100 text-gray-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        @if($broadcast->is_sent) Terkirim
                        @elseif($broadcast->is_draft) Draft
                        @else Dijadwalkan @endif
                    </span>
                </div>

                <div class="prose max-w-none text-gray-600">
                    <p>{{ $broadcast->message }}</p>
                </div>

                @if($broadcast->scheduled_at)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-500">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Dijadwalkan: {{ $broadcast->scheduled_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                @endif
            </div>

            {{-- Recipients Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Penerima ({{ $broadcast->recipients_count ?? $broadcast->recipients->count() }})</h3>
                
                @if($broadcast->recipients && $broadcast->recipients->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penerima</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dikirim</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($broadcast->recipients as $recipient)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            @if($recipient->foundation)
                                                {{ $recipient->foundation->name }}
                                            @else
                                                {{ $recipient->user->name ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                @if($recipient->status === 'sent') bg-green-100 text-green-800
                                                @elseif($recipient->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($recipient->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            {{ $recipient->sent_at ? $recipient->sent_at->format('d M Y, H:i') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada penerima untuk broadcast ini.</p>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Broadcast Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Informasi Broadcast</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-gray-500">Tipe</dt>
                        <dd class="text-sm font-medium text-gray-900">
                            <span class="px-2 py-0.5 rounded-full text-xs
                                @if($broadcast->type === 'info') bg-blue-100 text-blue-800
                                @elseif($broadcast->type === 'success') bg-green-100 text-green-800
                                @elseif($broadcast->type === 'warning') bg-yellow-100 text-yellow-800
                                @elseif($broadcast->type === 'error') bg-red-100 text-red-800
                                @else bg-purple-100 text-purple-800 @endif">
                                {{ ucfirst($broadcast->type) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Target</dt>
                        <dd class="text-sm font-medium text-gray-900">
                            @if($broadcast->target === 'all_users') Semua Pengguna
                            @elseif($broadcast->target === 'platform_admins') Admin Platform
                            @elseif($broadcast->target === 'foundation_admins') Admin Yayasan
                            @elseif($broadcast->target === 'school_admins') Admin Sekolah
                            @elseif($broadcast->target === 'specific_foundations') Yayasan Tertentu
                            @else {{ ucfirst($broadcast->target) }} @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Dibuat Oleh</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $broadcast->createdBy->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Dibuat</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $broadcast->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    @if($broadcast->sent_at)
                    <div>
                        <dt class="text-sm text-gray-500">Dikirim</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $broadcast->sent_at->format('d M Y, H:i') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Aksi</h3>
                <div class="space-y-3">
                    @if(!$broadcast->is_sent && !$broadcast->scheduled_at)
                        <form method="POST" action="{{ route('platform.broadcasts.send', $broadcast) }}">
                            @csrf
                            <button type="submit"
                                class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Kirim Sekarang
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('platform.broadcasts.index') }}"
                        class="block w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-center">
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-platform-layout>
