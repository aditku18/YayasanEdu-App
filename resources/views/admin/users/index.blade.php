<x-platform-layout>
    <x-slot name="header">Admin Platform</x-slot>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Pengguna Admin Platform</h2>
                <p class="text-sm text-gray-500">Atur tim internal yang memiliki akses ke dashboard platform.</p>
            </div>

            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('platform.users.index') }}" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama atau email..." class="border rounded-lg px-3 py-2 text-sm" />
                    <button class="px-3 py-2 bg-primary-600 text-white rounded-lg text-sm">Cari</button>
                </form>

                <a href="{{ route('platform.users.create') }}" class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition-colors">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
    </svg>
    Buat Pengguna
</a>
            </div>
        </div>

        <div class="flex items-center gap-4 mb-4">
            <div class="bg-white border rounded-xl p-4 shadow-sm">
                <p class="text-sm text-gray-500">Total Pengguna</p>
                <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white border rounded-xl p-4 shadow-sm">
                <p class="text-sm text-gray-500">Active</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
            </div>

            <div class="ml-auto">
                <select name="role" onchange="location = '?role=' + this.value" class="border rounded-lg px-3 py-2 text-sm">
                    <option value="">Semua Role</option>
                    <option value="platform_admin" {{ ($role ?? '') === 'platform_admin' ? 'selected' : '' }}>Platform Admin</option>
                    <option value="foundation_admin" {{ ($role ?? '') === 'foundation_admin' ? 'selected' : '' }}>Foundation Admin</option>
                    <option value="school_admin" {{ ($role ?? '') === 'school_admin' ? 'selected' : '' }}>School Admin</option>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse table-auto">
                    <thead>
                        <tr class="text-xs font-semibold text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Sekolah</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $user->created_at?->format('d M Y') }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $user->schoolUnit?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($user->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('platform.users.show', $user->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.514 1.514-3.182 3-5.254 3H5.414C3.182 15 1.514 13.486 0 12c1.514-1.486 3.182-3 5.254-3h12.092c2.072 0 3.74 1.514 5.254 3z"/>
                                            </svg>
                                            Show
                                        </a>
                                        <a href="{{ route('platform.users.edit', $user->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-medium rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5h-3V7a2 2 0 00-2-2z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 3.5a2 2 0 013 0V7a2 2 0 01-3 0V3.5z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('platform.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="event.preventDefault(); if(confirm('Hapus pengguna ini? Data tidak dapat dikembalikan.')) { this.submit(); }">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium rounded-lg transition-colors border border-red-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $users->links() }}</div>
        </div>
    </div>
</x-platform-layout>
