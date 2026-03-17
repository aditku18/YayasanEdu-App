<x-platform-layout>
    <x-slot name="header">Data Siswa Global</x-slot>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('platform.students.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <select name="school_id" onchange="this.form.submit()" class="bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm">
                <option value="">Semua Unit Sekolah</option>
                @foreach($schools as $s)
                    <option value="{{ $s->id }}" {{ (string)($school ?? '') === (string)$s->id ? 'selected' : '' }}>{{ $s->name }} ({{ $s->level }})</option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="active" {{ ($status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ ($status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama / NIS / NISN..." class="bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm w-64">

            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm">Cari</button>
            @if($search || $school || $status)
                <a href="{{ route('platform.students.index') }}" class="text-sm text-red-600">Reset</a>
            @endif
        </form>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        <div class="bg-white rounded-xl p-4 shadow-sm border">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Semua Siswa</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Active</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['inactive'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Inactive</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-auto">
                <thead>
                    <tr class="text-xs font-semibold text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">NIS / NISN</th>
                        <th class="px-4 py-3">Sekolah</th>
                        <th class="px-4 py-3">Orang Tua</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $student)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <p class="text-sm font-semibold text-gray-900">{{ $student->name }}</p>
                                <p class="text-xs text-gray-500">{{ $student->birth_place }}, {{ optional($student->birth_date)->format('d M Y') }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $student->nis ?? '-' }} / {{ $student->nisn ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $student->school?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $student->parent_name ?? '-' }}<br><span class="text-xs text-gray-400">{{ $student->parent_phone ?? '-' }}</span></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($student->status ?? '—') }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="#" class="text-xs text-blue-600 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada data siswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $students->links() }}
        </div>
    </div>
</x-platform-layout>
