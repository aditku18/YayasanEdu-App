<x-platform-layout>
    <x-slot name="header">Detail Tiket #{{ $ticket->ticket_number }}</x-slot>
    <x-slot name="subtitle">Lihat dan kelola detail tiket support</x-slot>

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
            {{-- Ticket Details Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $ticket->subject }}</h2>
                        <p class="text-sm text-gray-500 mt-1">Dibuat {{ $ticket->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-3 py-1 text-sm font-medium rounded-full 
                        @if($ticket->status === 'open') bg-green-100 text-green-800
                        @elseif($ticket->status === 'in_progress') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($ticket->status) }}
                    </span>
                </div>

                <div class="prose max-w-none text-gray-600">
                    <p>{{ $ticket->description }}</p>
                </div>

                @if($ticket->resolution)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Resolusi</h3>
                        <p class="text-gray-600">{{ $ticket->resolution }}</p>
                    </div>
                @endif
            </div>

            {{-- Responses Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Respons</h3>
                
                @if($ticket->responses && $ticket->responses->count() > 0)
                    <div class="space-y-4">
                        @foreach($ticket->responses as $response)
                            <div class="flex gap-4 p-4 rounded-lg {{ $response->is_internal ? 'bg-yellow-50 border border-yellow-100' : 'bg-gray-50 border border-gray-100' }}">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-indigo-600 font-medium">{{ substr($response->user->name ?? 'U', 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-medium text-gray-900">{{ $response->user->name ?? 'Unknown User' }}</span>
                                        <span class="text-sm text-gray-500">{{ $response->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-600">{{ $response->message }}</p>
                                    @if($response->is_internal)
                                        <span class="inline-block mt-2 text-xs text-yellow-700 bg-yellow-100 px-2 py-0.5 rounded">Internal</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada respons untuk ticket ini.</p>
                @endif

                {{-- Add Response Form --}}
                @if($ticket->status !== 'closed')
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <form method="POST" action="{{ route('platform.tickets.respond', $ticket) }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tambah Respons
                                    </label>
                                    <textarea name="message" id="message" rows="3" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Tulis respons Anda..."></textarea>
                                    @error('message')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="is_internal" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-600">Respons Internal</span>
                                    </label>
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Kirim Respons
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Ticket Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Informasi Tiket</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-gray-500">Nomor Tiket</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $ticket->ticket_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Status</dt>
                        <dd class="text-sm font-medium text-gray-900">
                            <span class="px-2 py-0.5 rounded-full text-xs
                                @if($ticket->status === 'open') bg-green-100 text-green-800
                                @elseif($ticket->status === 'in_progress') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Prioritas</dt>
                        <dd class="text-sm font-medium text-gray-900">
                            <span class="px-2 py-0.5 rounded-full text-xs
                                @if($ticket->priority === 'urgent') bg-red-100 text-red-800
                                @elseif($ticket->priority === 'high') bg-orange-100 text-orange-800
                                @elseif($ticket->priority === 'medium') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Kategori</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ ucfirst($ticket->category) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Yayasan</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $ticket->foundation->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Pelapor</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $ticket->user->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Dibuat</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $ticket->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    @if($ticket->closed_at)
                    <div>
                        <dt class="text-sm text-gray-500">Ditutup</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $ticket->closed_at->format('d M Y, H:i') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Aksi</h3>
                <div class="space-y-3">
                    @if($ticket->status === 'closed')
                        <form method="POST" action="{{ route('platform.tickets.reopen', $ticket) }}">
                            @csrf
                            <button type="submit"
                                class="w-full px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Buka Kembali Tiket
                            </button>
                        </form>
                    @else
                        <button type="button" onclick="document.getElementById('closeTicketModal').classList.remove('hidden')"
                            class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Tutup Tiket
                        </button>
                    @endif
                    
                    <a href="{{ route('platform.tickets.index') }}"
                        class="block w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-center">
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Close Ticket Modal --}}
    @if($ticket->status !== 'closed')
    <div id="closeTicketModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('closeTicketModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <form method="POST" action="{{ route('platform.tickets.close', $ticket) }}">
                    @csrf
                    <div>
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tutup Tiket</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Berikan resolusi untuk menutup ticket ini.</p>
                            </div>
                            <div class="mt-4">
                                <label for="resolution" class="sr-only">Resolusi</label>
                                <textarea name="resolution" id="resolution" rows="3" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="Tulis resolusi..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup Tiket
                        </button>
                        <button type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm"
                            onclick="document.getElementById('closeTicketModal').classList.add('hidden')">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</x-platform-layout>
