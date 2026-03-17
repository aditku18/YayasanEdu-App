<x-platform-layout>
    <x-slot name="header">Statistik Yayasan</x-slot>
    <x-slot name="subtitle">Analisis pertumbuhan dan demografi yayasan</x-slot>

    {{-- Period Selector --}}
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mb-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">Periode Analisis</h3>
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ route('platform.statistics.foundations') }}" class="flex items-center gap-2">
                    <select name="period" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Growth Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Pertumbuhan Yayasan</h3>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <div class="text-center">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="text-gray-500">Grafik pertumbuhan yayasan</p>
                <p class="text-sm text-gray-400 mt-1">{{ $growthData->count() }} data points</p>
            </div>
        </div>
    </div>

    <!-- Foundations Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Daftar Yayasan</h2>
                <span class="text-sm text-gray-500">{{ $foundations->total() }} yayasan</span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yayasan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sekolah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($foundations as $foundation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $foundation->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $foundation->email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-900">{{ $foundation->plan->name ?? 'Free' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($foundation->status == 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @elseif($foundation->status == 'trial')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Trial
                                    </span>
                                @elseif($foundation->status == 'suspended')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Suspended
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($foundation->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $foundation->schools->count() }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $foundation->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p>Belum ada yayasan yang ditemukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($foundations->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $foundations->links() }}
            </div>
        @endif
    </div>
</x-platform-layout>
