@extends('platform.layouts.app')

@section('title', 'Buat Yayasan Baru')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Buat Yayasan Baru</h1>
        <p class="text-gray-600 mt-1">Tambahkan yayasan baru ke platform EduSaaS</p>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('platform.foundations.store') }}">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Left Column --}}
                    <div class="space-y-6">
                        {{-- Basic Information --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Yayasan <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="subdomain" class="block text-sm font-medium text-gray-700 mb-2">Subdomain <span class="text-red-500">*</span></label>
                                    <div class="flex">
                                        <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain') }}" required
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                        <span class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-50 text-gray-500 rounded-r-lg">
                                            .edusaas.com
                                        </span>
                                    </div>
                                    @error('subdomain')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                    <textarea name="description" id="description" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-6">
                        {{-- Plan & Status --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Paket & Status</h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                                    <select name="status" id="status" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="trial" {{ old('status') == 'trial' ? 'selected' : '' }}>Trial</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="plan_id" class="block text-sm font-medium text-gray-700 mb-2">Paket Langganan</label>
                                    <select name="plan_id" id="plan_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Pilih Paket (Opsional)</option>
                                        @foreach($plans as $plan)
                                            <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                                {{ $plan->name }} - Rp {{ number_format($plan->price_per_month, 0, ',', '.') }}/bulan
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('plan_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Plan Details (Dynamic) --}}
                                <div id="planDetails" class="hidden bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 mb-2">Detail Paket</h4>
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <div id="planSchools">Max Sekolah: -</div>
                                        <div id="planUsers">Max User: -</div>
                                        <div id="planStudents">Max Siswa: -</div>
                                        <div id="planPrice">Harga: -</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Preview --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Pratinjau</h3>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-sm text-gray-600 space-y-1">
                                    <div><strong>URL Yayasan:</strong> <span id="previewUrl" class="text-indigo-600">https://[subdomain].edusaas.com</span></div>
                                    <div><strong>Admin Email:</strong> <span id="previewEmail" class="text-gray-900">[email]</span></div>
                                    <div><strong>Status:</strong> <span id="previewStatus" class="text-gray-900">Pending</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('platform.foundations.index') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                            Buat Yayasan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Live preview updates
        document.getElementById('subdomain').addEventListener('input', function() {
            const subdomain = this.value || '[subdomain]';
            document.getElementById('previewUrl').textContent = `https://${subdomain}.edusaas.com`;
        });

        document.getElementById('email').addEventListener('input', function() {
            document.getElementById('previewEmail').textContent = this.value || '[email]';
        });

        document.getElementById('status').addEventListener('change', function() {
            const statusText = this.options[this.selectedIndex].text;
            document.getElementById('previewStatus').textContent = statusText;
        });

        // Plan details dynamic loading
        document.getElementById('plan_id').addEventListener('change', function() {
            const planId = this.value;
            const planDetails = document.getElementById('planDetails');

            if (planId) {
                // In a real application, you would fetch plan details via AJAX
                // For now, we'll show the container
                planDetails.classList.remove('hidden');

                // You can add AJAX call here to fetch plan details
                // fetch(`/api/plans/${planId}`)
                //     .then(response => response.json())
                //     .then(data => {
                //         document.getElementById('planSchools').textContent = `Max Sekolah: ${data.max_schools}`;
                //         // ... update other fields
                //     });
            } else {
                planDetails.classList.add('hidden');
            }
        });

        // Initialize preview
        document.getElementById('subdomain').dispatchEvent(new Event('input'));
        document.getElementById('email').dispatchEvent(new Event('input'));
        document.getElementById('status').dispatchEvent(new Event('change'));
    </script>
    @endpush
</div>
@endsection
