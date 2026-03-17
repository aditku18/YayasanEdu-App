<x-platform-layout>
    <x-slot name="header">Gateway Intelligence</x-slot>
    <x-slot name="subtitle">Analisis mendalam dan status operasional gerbang pembayaran</x-slot>

    <!-- Navigation & Main Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('platform.payment-gateways.index') }}" 
               class="flex items-center gap-2 px-4 py-2 bg-white text-gray-600 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="text-sm font-medium">Back</span>
            </a>

            <button onclick="window.location.reload()" 
                    class="p-2 bg-white text-gray-600 rounded-lg border border-gray-200 hover:bg-gray-50 transition-all shadow-sm"
                    title="Refresh Intelligence">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>

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
            
            <div class="px-3 py-1.5 bg-gray-900 text-white rounded-lg text-xs font-bold uppercase tracking-wider">
                ID: #{{ str_pad($paymentGateway->id, 5, '0', STR_PAD_LEFT) }}
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- Toggle Status -->
            <form action="{{ route('platform.payment-gateways.toggle', $paymentGateway) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="flex items-center gap-2 px-6 py-2.5 {{ $paymentGateway->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-gray-50 text-gray-400 border border-gray-100' }} rounded-lg hover:bg-white transition-all active:scale-95 font-bold text-xs uppercase tracking-wider">
                    @if($paymentGateway->is_active)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636"/></svg>
                    @endif
                    {{ $paymentGateway->is_active ? 'Disable' : 'Enable' }}
                </button>
            </form>

            <a href="{{ route('platform.payment-gateways.edit', $paymentGateway) }}" 
               class="flex items-center gap-2 px-6 py-2.5 bg-amber-50 text-amber-600 border border-amber-200 rounded-lg hover:bg-white transition-all shadow-sm active:scale-95 font-bold text-xs uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Reconfigure
            </a>

            <!-- Test Connection -->
            <button type="button" 
                    onclick="testConnection({{ $paymentGateway->id }})"
                    class="flex items-center gap-2 px-6 py-2.5 bg-blue-50 text-blue-600 border border-blue-100 rounded-lg hover:bg-white transition-all shadow-sm active:scale-95 font-bold text-xs uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Test Signal
            </button>

            <!-- Delete -->
            <form action="{{ route('platform.payment-gateways.destroy', $paymentGateway) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus gateway ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="flex items-center gap-2 px-6 py-2.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-lg hover:bg-rose-100 transition-all active:scale-95 font-bold text-xs uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Destroy
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Vital Stats & Identity -->
        <div class="space-y-6">
            <!-- Identity Card -->
            <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100 flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg mb-4 uppercase">
                    {{ substr($paymentGateway->name, 0, 2) }}
                </div>
                <h2 class="text-xl font-bold text-gray-900">{{ $paymentGateway->display_name }}</h2>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mt-1">{{ $paymentGateway->type }} Provider</p>
                
                <div class="mt-4">
                    @if($paymentGateway->is_active)
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-bold uppercase border border-emerald-100">Live</span>
                    @else
                        <span class="px-3 py-1 bg-gray-50 text-gray-400 rounded-full text-[10px] font-bold uppercase border border-gray-100">Hidden</span>
                    @endif
                </div>

                <div class="w-full space-y-3 pt-6 mt-6 border-t border-gray-50">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">System Code</span>
                        <span class="text-xs font-mono font-medium text-gray-600">{{ $paymentGateway->name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Priority</span>
                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Rank {{ $paymentGateway->priority }}</span>
                    </div>
                </div>
            </div>

            <!-- Commercial Policy -->
            <div class="bg-indigo-600 rounded-xl p-6 shadow-md text-white">
                <h3 class="text-sm font-bold uppercase tracking-wider mb-4">Commercial Engine</h3>
                
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="p-3 bg-white/10 rounded-lg border border-white/10">
                        <p class="text-[9px] font-bold text-indigo-200 uppercase mb-1">Fee Rate</p>
                        <p class="text-lg font-bold">{{ $paymentGateway->admin_fee_rate }}%</p>
                    </div>
                    <div class="p-3 bg-white/10 rounded-lg border border-white/10">
                        <p class="text-[9px] font-bold text-indigo-200 uppercase mb-1">Fixed Fee</p>
                        <p class="text-lg font-bold">{{ number_format($paymentGateway->fixed_admin_fee, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="text-indigo-200">Min Amount</span>
                        <span class="font-bold">Rp {{ number_format($paymentGateway->min_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-indigo-200">Max Amount</span>
                        <span class="font-bold">{{ $paymentGateway->max_amount ? 'Rp '.number_format($paymentGateway->max_amount, 0, ',', '.') : '∞' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle & Right Columns: Technical Detail & Activity -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Operational Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase mb-2">Total Settled</p>
                    <div class="flex items-baseline gap-2">
                        @php
                            $settledCount = \App\Models\PlatformPayment::where('payment_method', $paymentGateway->name)
                                ->where('status', 'success')
                                ->count();
                        @endphp
                        <span class="text-2xl font-bold text-gray-900">{{ $settledCount }}</span>
                        <span class="text-[10px] font-bold text-emerald-500 uppercase">TXS</span>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase mb-2">Success Rate</p>
                    @php
                        $totalTxs = \App\Models\PlatformPayment::where('payment_method', $paymentGateway->name)->count();
                        $successTxs = \App\Models\PlatformPayment::where('payment_method', $paymentGateway->name)
                            ->where('status', 'success')
                            ->count();
                        $rate = $totalTxs > 0 ? round(($successTxs / $totalTxs) * 100, 1) : 100;
                    @endphp
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-indigo-600">{{ $rate }}%</span>
                        <span class="text-[10px] font-bold text-indigo-400 uppercase">OPS</span>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase mb-2">Methods</p>
                    <div class="flex gap-1 flex-wrap">
                        @php
                            $methods = is_array($paymentGateway->supported_methods) ? $paymentGateway->supported_methods : json_decode($paymentGateway->supported_methods, true) ?? [];
                        @endphp
                        @foreach(array_slice($methods, 0, 3) as $method)
                            <span class="px-1.5 py-0.5 bg-gray-50 text-gray-500 rounded text-[9px] font-bold uppercase border border-gray-100">{{ str_replace('_', ' ', $method) }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Configuration Matrix -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Configuration Matrix
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @php $config = $paymentGateway->config ?? []; @endphp
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Server Key</p>
                            <div class="px-3 py-2 bg-gray-50 rounded-lg border border-gray-100 text-xs font-mono text-gray-400">
                                {{ isset($config['server_key']) ? '************************' . substr($config['server_key'], -4) : '—' }}
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">API URL</p>
                            <div class="px-3 py-2 bg-gray-50 rounded-lg border border-gray-100 text-xs font-mono text-indigo-400 truncate">
                                {{ $config['api_url'] ?? 'DEFAULT' }}
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Merchant ID</p>
                            <div class="px-3 py-2 bg-gray-50 rounded-lg border border-gray-100 text-xs font-mono text-gray-600">
                                {{ $config['merchant_id'] ?? '—' }}
                            </div>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <p class="text-[10px] font-bold text-blue-700 uppercase">Security Note</p>
                            <p class="text-[9px] text-blue-600 leading-tight mt-1">Keys are encrypted with AES-256 before storage.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 mb-6">Activity Streams</h3>

                <div class="space-y-3">
                    @forelse($paymentGateway->webhookLogs()->latest()->take(5)->get() as $log)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="w-12 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-[10px] font-bold {{ $log->status === 'processed' ? 'text-emerald-600' : ($log->status === 'failed' ? 'text-rose-600' : 'text-amber-600') }} shrink-0 uppercase">
                                {{ $log->status }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-center mb-0.5">
                                    <p class="text-[10px] font-bold text-gray-900 uppercase">{{ $log->getEventTypeText() }}</p>
                                    <p class="text-[9px] text-gray-400">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                                <p class="text-[10px] text-gray-500 truncate">{{ is_array($log->payload) ? json_encode($log->payload) : substr($log->payload, 0, 50) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center border border-dashed border-gray-200 rounded-xl">
                            <p class="text-xs font-bold text-gray-400 uppercase">No recent activity</p>
                        </div>
                    @endforelse
                </div>
            </div>
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
