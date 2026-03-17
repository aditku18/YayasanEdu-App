<x-platform-layout>
    <x-slot name="header">Data Sekolah</x-slot>

    {{-- Filter & Search Bar --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('platform.schools.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <select name="status" onchange="this.form.submit()"
                    class="bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm">
                <option value="">Semua Status</option>
                @foreach(['draft','setup','active','suspended','expired'] as $st)
                    <option value="{{ $st }}" {{ $status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                @endforeach
            </select>

            <input type="text" name="search" value="{{ $search ?? '' }}"
                   placeholder="Cari nama / NPSN / yayasan..."
                   class="bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm w-64">

            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm">Cari</button>
            @if($search || $status)
                <a href="{{ route('platform.schools.index') }}" class="text-sm text-red-600">Reset</a>
            @endif
        </form>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        <a href="{{ route('platform.schools.index') }}" class="bg-white rounded-xl p-4 shadow-sm border {{ !$status ? 'border-primary-300 ring-1 ring-primary-100' : 'border-gray-100' }} hover:shadow-md transition-all">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Semua</p>
        </a>
        @foreach(['draft','setup','active','suspended','expired'] as $st)
            <a href="{{ route('platform.schools.index', ['status' => $st]) }}" class="bg-white rounded-xl p-4 shadow-sm border {{ $status === $st ? 'border-primary-300 ring-1 ring-primary-100' : 'border-gray-100' }} hover:shadow-md transition-all">
                <p class="text-2xl font-bold text-gray-900">{{ $stats[$st] }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ ucfirst($st) }}</p>
            </a>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                        <th class="px-4 py-3">Sekolah</th>
                        <th class="px-4 py-3">Yayasan</th>
                        <th class="px-4 py-3">NPSN</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($schools as $school)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <p class="text-sm font-semibold text-gray-900">{{ $school->name }}</p>
                                <p class="text-xs text-gray-500">{{ $school->level }} / {{ $school->jenjang }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm text-gray-700">{{ $school->foundation?->name ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $school->npsn ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($school->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="text-xs text-gray-400">—</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                @if($status || $search)
                                    Tidak ada sekolah yang cocok dengan kriteria.
                                @else
                                    Belum ada data sekolah.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $schools->links() }}
        </div>
    </div>
</x-platform-layout>
