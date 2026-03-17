<x-platform-layout>
    <x-slot name="header">Reconfigure Gateway</x-slot>
    <x-slot name="subtitle">Modifikasi konfigurasi gerbang pembayaran: {{ $paymentGateway->display_name }}</x-slot>

    <!-- Navigation Actions -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('platform.payment-gateways.index') }}" 
           class="flex items-center gap-2 px-4 py-2 bg-white text-gray-600 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span class="text-sm font-medium">Back to Matrix</span>
        </a>

        <div class="px-3 py-1.5 bg-indigo-50 rounded-lg border border-indigo-100 text-indigo-600 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Secure Configuration Mode
        </div>
    </div>

    <form action="{{ route('platform.payment-gateways.update', $paymentGateway) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Basic Info & Features -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Core Identity Section -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="w-7 h-7 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-sm font-bold">1</span>
                        Core Identity
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">System Code Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $paymentGateway->name) }}" required
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                   placeholder="e.g., midtrans">
                            @error('name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="display_name" class="block text-sm font-medium text-gray-700 mb-2">Client-Facing Name</label>
                            <input type="text" id="display_name" name="display_name" value="{{ old('display_name', $paymentGateway->display_name) }}" required
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                   placeholder="e.g., Midtrans Official">
                            @error('display_name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Architectural Type</label>
                            <select id="type" name="type" required
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none cursor-pointer">
                                <option value="third_party" {{ old('type', $paymentGateway->type) === 'third_party' ? 'selected' : '' }}>Third Party Provider</option>
                                <option value="custom" {{ old('type', $paymentGateway->type) === 'custom' ? 'selected' : '' }}>Custom Logic / Manual</option>
                            </select>
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">System Priority</label>
                            <input type="number" id="priority" name="priority" value="{{ old('priority', $paymentGateway->priority) }}" min="1" required
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                </div>

                <!-- Features Section -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="w-7 h-7 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center text-sm font-bold">2</span>
                        Feature Capability
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <label class="group relative cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $paymentGateway->is_active) ? 'checked' : '' }} class="peer hidden">
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl transition-all peer-checked:bg-emerald-50 peer-checked:border-emerald-500 hover:bg-gray-100">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-gray-400 peer-checked:text-emerald-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </div>
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-500 flex items-center justify-center">
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>
                                <p class="font-bold text-gray-900 text-sm">Online Status</p>
                                <p class="text-xs text-gray-500 mt-0.5">Live Transactional</p>
                            </div>
                        </label>

                        <label class="group relative cursor-pointer">
                            <input type="checkbox" name="supports_recurring" value="1" {{ old('supports_recurring', $paymentGateway->supports_recurring) ? 'checked' : '' }} class="peer hidden">
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl transition-all peer-checked:bg-purple-50 peer-checked:border-purple-500 hover:bg-gray-100">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-gray-400 peer-checked:text-purple-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    </div>
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 peer-checked:border-purple-500 peer-checked:bg-purple-500 flex items-center justify-center">
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>
                                <p class="font-bold text-gray-900 text-sm">Recurring</p>
                                <p class="text-xs text-gray-500 mt-0.5">Subscription Engine</p>
                            </div>
                        </label>

                        <label class="group relative cursor-pointer">
                            <input type="checkbox" name="supports_split_payment" value="1" {{ old('supports_split_payment', $paymentGateway->supports_split_payment) ? 'checked' : '' }} class="peer hidden">
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl transition-all peer-checked:bg-orange-50 peer-checked:border-orange-500 hover:bg-gray-100">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-gray-400 peer-checked:text-orange-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4 4"/></svg>
                                    </div>
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 peer-checked:border-orange-500 peer-checked:bg-orange-500 flex items-center justify-center">
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>
                                <p class="font-bold text-gray-900 text-sm">Split Payment</p>
                                <p class="text-xs text-gray-500 mt-0.5">Multi-Vendor Settlement</p>
                            </div>
                        </label>
                    </div>

                    <div class="space-y-4">
                        <p class="text-sm font-medium text-gray-700">Supported Interaction Methods</p>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                            @php
                                $supportedMethods = is_array($paymentGateway->supported_methods) ? $paymentGateway->supported_methods : json_decode($paymentGateway->supported_methods, true) ?? [];
                            @endphp
                            @foreach(['credit_card', 'bank_transfer', 'virtual_account', 'ewallet', 'qris', 'cstore'] as $method)
                                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100 transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                    <input type="checkbox" name="supported_methods[]" value="{{ $method }}" 
                                           {{ in_array($method, old('supported_methods', $supportedMethods)) ? 'checked' : '' }}
                                           class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-xs font-semibold text-gray-700 uppercase tracking-tight">{{ str_replace('_', ' ', $method) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Commercial Logic -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="w-7 h-7 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-sm font-bold">3</span>
                        Commercial Policy
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label for="admin_fee_rate" class="block text-sm font-medium text-gray-700 mb-2">Platform Percentage Fee (%)</label>
                                <input type="number" id="admin_fee_rate" name="admin_fee_rate" value="{{ old('admin_fee_rate', $paymentGateway->admin_fee_rate) }}" step="0.01" required
                                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label for="fixed_admin_fee" class="block text-sm font-medium text-gray-700 mb-2">Fixed Operational Fee (IDR)</label>
                                <input type="number" id="fixed_admin_fee" name="fixed_admin_fee" value="{{ old('fixed_admin_fee', $paymentGateway->fixed_admin_fee) }}" step="1" required
                                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="min_amount" class="block text-sm font-medium text-gray-700 mb-2">Threshold Minimum</label>
                                <input type="number" id="min_amount" name="min_amount" value="{{ old('min_amount', $paymentGateway->min_amount) }}" required
                                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label for="max_amount" class="block text-sm font-medium text-gray-700 mb-2">Threshold Maximum</label>
                                <input type="number" id="max_amount" name="max_amount" value="{{ old('max_amount', $paymentGateway->max_amount) }}"
                                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                       placeholder="Infinite limit if empty">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Security & Config -->
            <div class="space-y-6">
                <!-- Data Synchronization Section -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="w-7 h-7 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center text-sm font-bold">4</span>
                        Data Sync Bridge
                    </h3>

                    <div class="space-y-4">
                        @php
                            $config = $paymentGateway->config ?? [];
                        @endphp
                        <div>
                            <label for="server_key" class="block text-sm font-medium text-gray-700 mb-2">Master Secret Key (API)</label>
                            <input type="password" id="server_key" name="config[server_key]" value="{{ old('config.server_key', $config['server_key'] ?? '') }}"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="client_key" class="block text-sm font-medium text-gray-700 mb-2">App Client Key (API)</label>
                            <input type="password" id="client_key" name="config[client_key]" value="{{ old('config.client_key', $config['client_key'] ?? '') }}"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="merchant_id" class="block text-sm font-medium text-gray-700 mb-2">Merchant Identifier / Bank Name</label>
                            <input type="text" id="merchant_id" name="config[merchant_id]" value="{{ old('config.merchant_id', $config['merchant_id'] ?? '') }}"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                   placeholder="MID-00000X / BCA">
                        </div>

                        <hr class="border-gray-200 my-4">
                        <p class="text-sm font-semibold text-gray-800">Manual Transfer Config</p>

                        <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Bank</label>
                            <input type="text" id="bank_name" name="config[bank_name]" value="{{ old('config.bank_name', $config['bank_name'] ?? '') }}"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                   placeholder="Contoh: BCA">
                        </div>

                        <div>
                            <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening</label>
                            <input type="text" id="account_number" name="config[account_number]" value="{{ old('config.account_number', $config['account_number'] ?? '') }}"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                   placeholder="Contoh: 1234567890">
                        </div>

                        <div>
                            <label for="account_name" class="block text-sm font-medium text-gray-700 mb-2">Atas Nama Rekening</label>
                            <input type="text" id="account_name" name="config[account_name]" value="{{ old('config.account_name', $config['account_name'] ?? '') }}"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                   placeholder="Contoh: PT EduSaaS Indonesia">
                        </div>

                        <div>
                            <label for="bank_branch" class="block text-sm font-medium text-gray-700 mb-2">Cabang Bank (Opsional)</label>
                            <input type="text" id="bank_branch" name="config[bank_branch]" value="{{ old('config.bank_branch', $config['bank_branch'] ?? '') }}"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                   placeholder="Contoh: KCU Sudirman">
                        </div>

                        <hr class="border-gray-200 my-4">

                        <div>
                            <label for="api_url" class="block text-sm font-medium text-gray-700 mb-2">Endpoint Proxy URL (API)</label>
                            <input type="url" id="api_url" name="config[api_url]" value="{{ old('config.api_url', $config['api_url'] ?? '') }}"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                   placeholder="https://api.gateway.com/v2">
                        </div>

                        <div>
                            <label for="webhook_token" class="block text-sm font-medium text-gray-700 mb-2">Webhook Relay Token (API)</label>
                            <input type="password" id="webhook_token" name="config[webhook_token]" value="{{ old('config.webhook_token', $config['webhook_token'] ?? '') }}"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100 flex gap-3 text-blue-700">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs leading-relaxed">Your configuration is encrypted with AES-256-GCM before storage.</p>
                    </div>
                </div>

                <!-- Final Actions -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 space-y-4">
                    <button type="submit" 
                            class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold transition-all shadow-md active:scale-[0.98]">
                        Update Configuration
                    </button>
                    
                    <p class="text-center text-xs text-gray-500">Changes are effective immediately after update.</p>
                </div>
            </div>
        </div>
    </form>
</x-platform-layout>
