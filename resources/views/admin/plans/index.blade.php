<x-platform-layout>
    <x-slot name="header">Paket Langganan</x-slot>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Manajemen Paket (Plans)</h2>
                <p class="text-gray-500 text-sm">Atur paket langganan, harga, dan batasan kuot untuk masing-masing yayasan.</p>
            </div>

            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('platform.plans.index') }}" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari paket..." class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500" />
                    <button type="submit" class="px-3 py-2 bg-primary-600 text-white rounded-lg text-sm hover:bg-primary-700 transition">Cari</button>
                </form>

                <a href="{{ route('platform.plans.create') }}" class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition flex items-center gap-1">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Buat Paket
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($plans as $plan)
                <div class="bg-white border-2 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all {{ $plan->is_featured ? 'border-primary-500 ring-2 ring-primary-100' : 'border-gray-200' }}">
                    {{-- Featured Badge --}}
                    @if($plan->is_featured)
                        <div class="mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                <i data-lucide="star" class="w-3 h-3 mr-1 fill-current"></i>
                                Terpopuler
                            </span>
                        </div>
                    @endif

                    {{-- Plan Name & Price --}}
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-900">{{ $plan->name }}</h3>
                        @if($plan->price_per_month > 0)
                            <p class="text-2xl font-bold text-primary-600">
                                Rp {{ number_format($plan->price_per_month, 0, ',', '.') }}
                                <span class="text-sm font-normal text-gray-500">/bulan</span>
                            </p>
                            <p class="text-sm text-gray-500">
                                Rp {{ number_format($plan->price_per_year, 0, ',', '.') }} /tahun
                            </p>
                        @else
                            <p class="text-2xl font-bold text-gray-900">Kontak Sales</p>
                        @endif
                    </div>

                    {{-- Limits --}}
                    <div class="grid grid-cols-2 gap-3 mb-4 p-3 bg-gray-50 rounded-xl">
                        <div>
                            <p class="text-xs text-gray-500">Sekolah</p>
                            <p class="font-semibold text-gray-900">{{ $plan->max_schools ?? 'Unlimited' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">User</p>
                            <p class="font-semibold text-gray-900">{{ $plan->max_users ?? 'Unlimited' }}</p>
                        </div>
                    </div>

                    {{-- Features --}}
                    @if(!empty($plan->features) && is_array($plan->features))
                        <ul class="space-y-2 mb-4">
                            @foreach(array_slice($plan->features, 0, 5) as $feat)
                                <li class="flex items-center gap-2 text-sm text-gray-700">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500 flex-shrink-0"></i>
                                    {{ $feat }}
                                </li>
                            @endforeach
                            @if(count($plan->features) > 5)
                                <li class="text-sm text-gray-500">+{{ count($plan->features) - 5 }} fitur lainnya</li>
                            @endif
                        </ul>
                    @endif

                    {{-- Actions --}}
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <span class="text-xs text-gray-500">Slug: {{ $plan->slug }}</span>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('platform.plans.edit', $plan->id) }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 py-12">
                    <i data-lucide="package" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                    <p class="text-lg font-medium">Belum ada paket tersedia</p>
                    <p class="text-sm">Silakan tambah paket terlebih dahulu</p>
                </div>
            @endforelse
        </div>

        @if($plans->hasPages())
            <div class="mt-6">
                {{ $plans->links() }}
            </div>
        @endif
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center">
                    <i data-lucide="package" class="w-6 h-6 text-primary-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Paket</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Paket Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $plans->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i data-lucide="star" class="w-6 h-6 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Paket Unggulan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $plans->where('is_featured', true)->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</x-platform-layout>
