<x-platform-layout>
    <x-slot name="header">Payment Gateways</x-slot>
    <x-slot name="subtitle">Kelola konfigurasi gerbang pembayaran platform</x-slot>

    <!-- Navigation & Quick Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-2">
            <a href="{{ route('platform.transactions.index') }}" 
               class="flex items-center gap-2 px-4 py-2 bg-white text-gray-600 rounded-lg border border-gray-200 hover:bg-gray-50 transition-all font-bold text-xs uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a2 2 0 00-2-2H5a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Transactions
            </a>
            <a href="{{ route('platform.webhooks.index') }}" 
               class="flex items-center gap-2 px-4 py-2 bg-white text-gray-600 rounded-lg border border-gray-200 hover:bg-gray-50 transition-all font-bold text-xs uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Webhooks
            </a>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="window.location.reload()" 
                    class="p-2.5 bg-white text-gray-600 rounded-lg border border-gray-200 hover:bg-gray-50 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>
            <a href="{{ route('platform.payment-gateways.create') }}" 
               class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all shadow-md active:scale-95 font-bold text-xs uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Provision Gateway
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg flex items-center gap-3">
            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="font-medium text-sm">Berhasil!</p>
                <p class="text-xs opacity-80">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg flex items-center gap-3">
            <div class="w-8 h-8 bg-rose-100 rounded-full flex items-center justify-center text-rose-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="font-medium text-sm">Gagal!</p>
                <p class="text-xs opacity-80">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Stats Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Total -->
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 text-sm font-medium">Total Gateways</p>
                    <p class="text-3xl font-bold mt-1">{{ $gateways->count() }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-100 text-sm font-medium">Active Now</p>
                    <p class="text-3xl font-bold mt-1">{{ $gateways->where('is_active', true)->count() }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Recurring -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Recurring Support</p>
                    <p class="text-3xl font-bold mt-1">{{ $gateways->where('supports_recurring', true)->count() }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Split -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-sm font-medium">Split Payment</p>
                    <p class="text-3xl font-bold mt-1">{{ $gateways->where('supports_split_payment', true)->count() }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4 4"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Active Interfaces</h3>
                    <p class="text-sm text-gray-500">Monitoring and configuration for all payment pipelines.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <input type="text" placeholder="Search gateways..." class="pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-64">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button class="p-2 bg-gray-50 text-gray-500 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b border-gray-100">
                        <th class="pb-4 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Gateway Provider</th>
                        <th class="pb-4 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Type & Scale</th>
                        <th class="pb-4 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Operational Status</th>
                        <th class="pb-4 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Feature Matrix</th>
                        <th class="pb-4 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Fee Structure</th>
                        <th class="pb-4 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Priority</th>
                        <th class="pb-4 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider text-right">Control Center</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($gateways as $gateway)
                        <tr class="group hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center text-white font-semibold text-sm">
                                            {{ substr($gateway->display_name, 0, 1) }}
                                        </div>
                                        @if($gateway->is_active)
                                            <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $gateway->display_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $gateway->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($gateway->type === 'third_party') bg-indigo-50 text-indigo-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ $gateway->type }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex justify-center">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                                        @if($gateway->is_active) bg-emerald-50 text-emerald-700
                                        @else bg-rose-50 text-rose-700
                                        @endif">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $gateway->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                        {{ $gateway->is_active ? 'Online' : 'Offline' }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex flex-wrap gap-1">
                                    @if($gateway->supports_recurring)
                                        <div class="flex items-center gap-1 px-2 py-1 bg-purple-50 text-purple-700 rounded text-xs font-medium" title="Recurring Payment Support">
                                            Recur
                                        </div>
                                    @endif
                                    @if($gateway->supports_split_payment)
                                        <div class="flex items-center gap-1 px-2 py-1 bg-orange-50 text-orange-700 rounded text-xs font-medium" title="Split Payment Support">
                                            Split
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">{{ number_format($gateway->admin_fee_rate, 2) }}%</span>
                                    <span class="text-xs text-gray-500">+ IDR {{ number_format($gateway->fixed_admin_fee, 0) }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded text-xs font-medium text-gray-600">
                                    {{ $gateway->priority }}
                                </div>
                            </td>
                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Toggle Status -->
                                    <form action="{{ route('platform.payment-gateways.toggle', $gateway) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="p-2 {{ $gateway->is_active ? 'text-emerald-600 bg-emerald-50 hover:bg-emerald-100' : 'text-gray-400 bg-gray-50 hover:bg-gray-100' }} rounded-lg transition-all border border-transparent shadow-sm"
                                                title="{{ $gateway->is_active ? 'Disable Gateway' : 'Enable Gateway' }}">
                                            @if($gateway->is_active)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>

                                    <!-- View Analytics -->
                                    <a href="{{ route('platform.payment-gateways.show', $gateway) }}" 
                                       class="p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-all border border-transparent shadow-sm"
                                       title="Intelligence & Analytics">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </a>

                                    <!-- Edit Config -->
                                    <a href="{{ route('platform.payment-gateways.edit', $gateway) }}" 
                                       class="p-2 text-amber-600 bg-amber-50 hover:bg-amber-100 rounded-lg transition-all border border-transparent shadow-sm"
                                       title="Reconfigure Gateway">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <!-- Test Integration -->
                                    <button type="button" 
                                            onclick="testConnection({{ $gateway->id }})"
                                            class="p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all border border-transparent shadow-sm"
                                            title="Signal Integrity Scan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </button>

                                    <!-- Delete -->
                                    <form action="{{ route('platform.payment-gateways.destroy', $gateway) }}" method="POST" class="inline" onsubmit="return confirm('Hapus gateway {{ $gateway->display_name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition-all border border-transparent shadow-sm"
                                                title="Destroy Gateway">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center text-gray-300 mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900">No Payment Pipelines</h4>
                                    <p class="text-sm text-gray-500">Add a gateway provider to begin processing transactions.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Test Connection Modal -->
    <div id="testConnectionModal" 
         x-data="{ open: false }" 
         x-show="open" 
         x-cloak
         class="fixed inset-0 bg-gray-900/50 flex items-center justify-center z-[100] p-6"
         @payment-gateway-test.window="open = true; $nextTick(() => window.testConnectionExecute($event.detail.id))">
        
        <div class="bg-white rounded-xl p-8 max-w-lg w-full shadow-xl border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">System Bridge Integrity</h3>
                <button @click="open = false" class="p-2 hover:bg-gray-100 rounded-lg text-gray-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div id="testResult" class="mb-8 min-h-[120px] flex flex-col items-center justify-center">
                <!-- Content will be injected via JS -->
                 <div class="animate-pulse flex flex-col items-center gap-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-xl"></div>
                    <div class="h-3 w-32 bg-gray-100 rounded-full"></div>
                 </div>
            </div>

            <div class="flex gap-3">
                <button @click="open = false" class="flex-1 py-3 bg-gray-900 text-white rounded-lg font-medium hover:bg-gray-800 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        window.testConnectionExecute = function(gatewayId) {
            const result = document.getElementById('testResult');
            
            result.innerHTML = `
                <div class="flex flex-col items-center gap-4">
                    <div class="relative">
                        <div class="w-16 h-16 border-4 border-indigo-500/10 rounded-full"></div>
                        <div class="absolute inset-0 w-16 h-16 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="font-medium text-gray-900">Pinging Endpoint...</p>
                        <p class="text-gray-500 text-sm">Establishing encrypted handshake</p>
                    </div>
                </div>
            `;
            
            fetch(`/platform/payment-gateways/${gatewayId}/test`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    result.innerHTML = `
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-16 h-16 bg-emerald-500 text-white rounded-xl flex items-center justify-center">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div class="text-center">
                                <h4 class="text-lg font-medium text-gray-900">Bridge Stable</h4>
                                <p class="text-gray-500 text-sm">${data.message}</p>
                            </div>
                        </div>
                    `;
                } else {
                    result.innerHTML = `
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-16 h-16 bg-rose-500 text-white rounded-xl flex items-center justify-center">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <div class="text-center">
                                <h4 class="text-lg font-medium text-rose-600">Sync Failure</h4>
                                <p class="text-gray-500 text-sm">${data.message}</p>
                            </div>
                        </div>
                    `;
                }
            })
            .catch(error => {
                result.innerHTML = `<p class="text-rose-500 font-medium">${error.message}</p>`;
            });
        }

        function testConnection(id) {
            window.dispatchEvent(new CustomEvent('payment-gateway-test', { detail: { id: id } }));
        }
    </script>
</x-platform-layout>
