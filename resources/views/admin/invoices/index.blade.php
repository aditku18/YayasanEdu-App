<x-platform-layout>
    <x-slot name="header">Invoice Validasi</x-slot>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Invoice Validasi</h2>
                <p class="text-sm text-gray-500">Verifikasi bukti transfer yayasan untuk perpanjangan langganan.</p>
            </div>

            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('platform.invoices.index') }}" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari yayasan atau email..." class="border rounded-lg px-3 py-2 text-sm" />
                    <button class="px-3 py-2 bg-primary-600 text-white rounded-lg text-sm">Cari</button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="bg-white border rounded-xl p-4 shadow-sm">
                <p class="text-sm text-gray-500">Total Yayasan</p>
                <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white border rounded-xl p-4 shadow-sm">
                <p class="text-sm text-gray-500">Berakhir dalam 7 hari</p>
                <p class="text-2xl font-bold text-amber-600">{{ $stats['expiring'] }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-auto">
                <thead>
                    <tr class="text-xs font-semibold text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                        <th class="px-4 py-3">Yayasan</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Paket</th>
                        <th class="px-4 py-3">Berakhir</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($foundations as $f)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="text-sm font-semibold text-gray-900">{{ $f->name }}</div>
                                <div class="text-xs text-gray-400">{{ $f->subdomain }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $f->email }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $f->plan?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $f->subscription_ends_at ? $f->subscription_ends_at->format('d M Y') : '-' }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($f->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('platform.foundations.show', $f->id) }}" class="text-sm text-blue-600">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada item billing.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $foundations->links() }}</div>
    </div>
</x-platform-layout>
