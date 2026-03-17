<x-platform-layout>
    <x-slot name="header">Activity Log Details</x-slot>
    <x-slot name="subtitle">View detailed information about this activity</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            {{-- Header --}}
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <h3 class="text-lg font-medium text-gray-900">Log #{{ $log->id }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($log->action == 'create') bg-green-100 text-green-800
                            @elseif($log->action == 'update') bg-yellow-100 text-yellow-800
                            @elseif($log->action == 'delete') bg-red-100 text-red-800
                            @elseif(str_contains($log->action, 'error')) bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($log->action) }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('platform.activity-logs.index') }}" 
                            class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Basic Information --}}
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900">Basic Information</h4>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Timestamp</label>
                                <p class="text-gray-900">{{ $log->created_at->format('Y-m-d H:i:s') }} ({{ $log->created_at->diffForHumans() }})</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Module</label>
                                <p class="text-gray-900">{{ $log->getModuleDisplayName() }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Action</label>
                                <p class="text-gray-900">{{ ucfirst($log->action) }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Description</label>
                                <p class="text-gray-900">{{ $log->description ?: 'No description provided' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- User Information --}}
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900">User Information</h4>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">User</label>
                                @if($log->user)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-xs font-medium text-gray-600">
                                                    {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $log->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $log->user->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-900">System</p>
                                @endif
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Foundation</label>
                                <p class="text-gray-900">{{ $log->foundation ? $log->foundation->name : 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">IP Address</label>
                                <p class="text-gray-900">{{ $log->ip_address ?: 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">User Agent</label>
                                <p class="text-gray-900 text-sm">{{ $log->user_agent ?: 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Changes Section --}}
                @if($log->old_values || $log->new_values)
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-900 mb-4">Changes Made</h4>
                        
                        @php
                            $changes = $log->getChanges();
                        @endphp
                        
                        @if($changes)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="text-left py-2 font-medium text-gray-700">Field</th>
                                            <th class="text-left py-2 font-medium text-gray-700">Old Value</th>
                                            <th class="text-left py-2 font-medium text-gray-700">New Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($changes as $field => $change)
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $field)) }}</td>
                                                <td class="py-2 text-gray-600">
                                                    <span class="line-through">{{ $change['old'] ?: '(empty)' }}</span>
                                                </td>
                                                <td class="py-2 text-green-600 font-medium">
                                                    {{ $change['new'] ?: '(empty)' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-blue-800 text-sm">No changes detected in this log entry.</p>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Raw Data Section --}}
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Raw Data</h4>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @if($log->old_values)
                            <div>
                                <label class="text-sm font-medium text-gray-700 block mb-2">Old Values</label>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <pre class="text-xs text-gray-700 overflow-x-auto">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        @endif
                        
                        @if($log->new_values)
                            <div>
                                <label class="text-sm font-medium text-gray-700 block mb-2">New Values</label>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <pre class="text-xs text-gray-700 overflow-x-auto">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Reference Information --}}
                @if($log->reference_type && $log->reference_id)
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-900 mb-4">Reference Information</h4>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Reference Type</label>
                                    <p class="text-gray-900">{{ $log->reference_type }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Reference ID</label>
                                    <p class="text-gray-900">{{ $log->reference_id }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700">UUID</label>
                                    <p class="text-gray-900 font-mono text-sm">{{ $log->uuid }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-platform-layout>
