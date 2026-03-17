<x-platform-layout>
    <x-slot name="header">Webhook Logs</x-slot>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Webhook Logs</h2>
                <p class="text-sm text-gray-500">Monitor and manage incoming webhook requests from payment gateways.</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Processed</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['processed'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Failed</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['failed'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Duplicate</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['duplicate'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <form method="GET" action="{{ route('platform.webhooks.index') }}" class="mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <input type="text" name="search" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Search by event type or webhook ID..." value="{{ $search }}">
                <select name="gateway" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Gateways</option>
                    <option value="midtrans" {{ $gateway == 'midtrans' ? 'selected' : '' }}>Midtrans</option>
                    <option value="xendit" {{ $gateway == 'xendit' ? 'selected' : '' }}>Xendit</option>
                    <option value="doku" {{ $gateway == 'doku' ? 'selected' : '' }}>DOKU</option>
                </select>
                <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="processed" {{ $status == 'processed' ? 'selected' : '' }}>Processed</option>
                    <option value="failed" {{ $status == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="duplicate" {{ $status == 'duplicate' ? 'selected' : '' }}>Duplicate</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">
                    Search
                </button>
                <a href="{{ route('platform.webhooks.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-300 transition-colors">
                    Reset
                </a>
            </div>
        </form>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Webhook ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gateway</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono text-xs">{{ $log->webhook_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->event_type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $log->paymentGateway ? $log->paymentGateway->name : 'Unknown' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($log->status)
                                        @case('processed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Processed</span>
                                            @break
                                        @case('failed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Failed</span>
                                            @break
                                        @case('duplicate')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Duplicate</span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $log->status }}</span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $log->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="showWebhookDetails({{ $log->id }})" class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.514 1.514-3.182 3-5.254 3H5.414C3.182 15 1.514 13.486 0 12c1.514-1.486 3.182-3 5.254-3h12.092c2.072 0 3.74 1.514 5.254 3z"/>
                                            </svg>
                                        </button>
                                        @if($log->status === 'failed')
                                            <button onclick="retryWebhook({{ $log->id }})" class="text-green-600 hover:text-green-900">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                    No webhook logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} results
                    </div>
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Webhook Details Modal -->
    <div id="webhookModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Webhook Details</h3>
                <button onclick="closeWebhookModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="webhookModalContent" class="space-y-4">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        function showWebhookDetails(logId) {
            fetch(`/platform/webhooks/logs/${logId}/details`)
                .then(response => response.json())
                .then(data => {
                    const content = document.getElementById('webhookModalContent');
                    content.innerHTML = `
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ID</label>
                                <p class="text-sm text-gray-900">${data.id}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Webhook ID</label>
                                <p class="text-sm text-gray-900 font-mono">${data.webhook_id}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Event Type</label>
                                <p class="text-sm text-gray-900">${data.event_type}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-${getStatusColor(data.status)}-100 text-${getStatusColor(data.status)}-800">${data.status}</span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Gateway</label>
                                <p class="text-sm text-gray-900">${data.gateway || 'Unknown'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Created</label>
                                <p class="text-sm text-gray-900">${data.created_at}</p>
                            </div>
                        </div>
                        ${data.payload ? `
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payload</label>
                                <pre class="bg-gray-100 p-3 rounded-lg text-xs overflow-x-auto">${JSON.stringify(data.payload, null, 2)}</pre>
                            </div>
                        ` : ''}
                        ${data.error_message ? `
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Error Message</label>
                                <div class="bg-red-50 border border-red-200 p-3 rounded-lg text-sm text-red-800">${data.error_message}</div>
                            </div>
                        ` : ''}
                    `;
                    document.getElementById('webhookModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching webhook details:', error);
                });
        }

        function closeWebhookModal() {
            document.getElementById('webhookModal').classList.add('hidden');
        }

        function getStatusColor(status) {
            switch(status) {
                case 'processed': return 'green';
                case 'failed': return 'red';
                case 'duplicate': return 'yellow';
                default: return 'gray';
            }
        }

        function retryWebhook(logId) {
            if (confirm('Are you sure you want to retry this webhook?')) {
                fetch(`/platform/webhooks/logs/${logId}/retry`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to retry webhook: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error retrying webhook:', error);
                    alert('Error retrying webhook');
                });
            }
        }
    </script>
</x-platform-layout>
