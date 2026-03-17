<x-platform-layout>
    <x-slot name="header">{{ $integration->name }}</x-slot>
    <x-slot name="subtitle">API integration details and activity logs</x-slot>

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
            {{-- Integration Details --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Integration Details</h3>
                    <div class="flex items-center gap-2">
                        @if($integration->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Name</h4>
                        <p class="text-gray-900">{{ $integration->name }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Type</h4>
                        <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $integration->type)) }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Base URL</h4>
                        <p class="text-gray-900 text-sm">{{ $integration->base_url }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Webhook URL</h4>
                        <p class="text-gray-900 text-sm">{{ $integration->webhook_url ?: 'Not set' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">API Key</h4>
                        <p class="text-gray-900 text-sm font-mono">{{ $integration->api_key_display ?? '••••••••' }}</p>
                    </div>
                    @if($integration->api_secret_display)
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">API Secret</h4>
                            <p class="text-gray-900 text-sm font-mono">{{ $integration->api_secret_display }}</p>
                        </div>
                    @endif
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Description</h4>
                        <p class="text-gray-900">{{ $integration->description ?: 'No description provided' }}</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Created {{ $integration->created_at->diffForHumans() }} by {{ $integration->createdBy->name ?? 'Unknown' }}
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('platform.api-integrations.edit', $integration) }}" 
                                class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                Edit
                            </a>
                            <form action="{{ route('platform.api-integrations.test', $integration) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                    Test Connection
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Logs --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent API Logs</h3>
                
                @if($integration->logs->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Endpoint</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Response</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($integration->logs as $log)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-2 text-sm text-gray-900">{{ Str::limit($log->endpoint, 40) }}</td>
                                        <td class="px-3 py-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $log->method }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2">
                                            @if($log->status === 'success')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    Success
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    Failed
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-sm text-gray-900">{{ $log->response_code ?: '-' }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-500">{{ $log->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-500">No API logs available yet</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('platform.api-integrations.edit', $integration) }}" 
                        class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                        Edit Integration
                    </a>
                    <form action="{{ route('platform.api-integrations.test', $integration) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                            Test Connection
                        </button>
                    </form>
                    <a href="{{ route('platform.api-integrations.index') }}" 
                        class="block w-full text-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                        Back to List
                    </a>
                </div>
            </div>

            {{-- Statistics --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statistics</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Total Calls</span>
                            <span class="text-sm font-medium text-gray-900">{{ $integration->logs->count() }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Success Rate</span>
                            <span class="text-sm font-medium text-gray-900">
                                @if($integration->logs->count() > 0)
                                    {{ round(($integration->logs->where('status', 'success')->count() / $integration->logs->count()) * 100, 1) }}%
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Last Activity</span>
                            <span class="text-sm font-medium text-gray-900">
                                @if($integration->logs->count() > 0)
                                    {{ $integration->logs->first()->created_at->diffForHumans() }}
                                @else
                                    Never
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-platform-layout>
