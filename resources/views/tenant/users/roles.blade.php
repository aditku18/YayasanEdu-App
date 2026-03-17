@extends('layouts.tenant-platform')

@section('title', 'Manajemen Roles')

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
    .animate-slide-in-left {
        animation: slideInLeft 0.8s ease-out forwards;
        opacity: 0;
    }
    .animate-pulse-slow {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    .animate-delay-1 { animation-delay: 0.1s; }
    .animate-delay-2 { animation-delay: 0.2s; }
    .animate-delay-3 { animation-delay: 0.3s; }
    .glass-effect {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto mb-12">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-600 via-emerald-500 to-teal-500 p-8 md:p-12 text-white animate-fade-in-up shadow-2xl shadow-emerald-500/20">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2 animate-pulse-slow" style="animation-delay: 1s;"></div>
            
            <div class="relative z-10 text-center">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">Manajemen Roles</h1>
                </div>
                <p class="text-emerald-100 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                    Kelola hak akses dan peran pengguna dalam sistem
                </p>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $roles->count() }}</p>
                        <p class="text-emerald-100 text-sm">Total Roles</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $roles->where('name', 'like', '%admin%')->count() }}</p>
                        <p class="text-emerald-100 text-sm">Role Admin</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                        <p class="text-3xl font-black text-white">{{ $roles->where('name', 'like', '%user%')->count() }}</p>
                        <p class="text-emerald-100 text-sm">Role User</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles Table Section -->
    <div class="max-w-7xl mx-auto">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-slate-50 to-white p-8 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">Daftar Roles Sistem</h2>
                            <p class="text-slate-600">Kelola hak akses dan peran pengguna</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-black text-emerald-600">{{ $roles->count() }}</p>
                        <p class="text-sm text-slate-500 font-medium">Total Roles</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                @if($roles->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="text-left p-4 font-bold text-slate-900">Nama Role</th>
                                    <th class="text-left p-4 font-bold text-slate-900">Guard</th>
                                    <th class="text-left p-4 font-bold text-slate-900">Jumlah User</th>
                                    <th class="text-left p-4 font-bold text-slate-900">Dibuat</th>
                                    <th class="text-left p-4 font-bold text-slate-900">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $index => $role)
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors animate-slide-in-left {{ $index % 3 === 0 ? 'animate-delay-1' : ($index % 3 === 1 ? 'animate-delay-2' : 'animate-delay-3') }}">
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center">
                                                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($role->name, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-900">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</p>
                                                    <p class="text-sm text-slate-600">{{ $role->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">{{ $role->guard_name }}</span>
                                        </td>
                                        <td class="p-4">
                                            <span class="text-slate-700">{{ $role->users->count() ?? 0 }} user</span>
                                        </td>
                                        <td class="p-4">
                                            <span class="text-slate-600">{{ $role->created_at->format('d M Y') }}</span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex items-center gap-2">
                                                <button onclick="editRole({{ $role->id }}, '{{ $role->name }}', '{{ $role->guard_name }}')" class="p-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg transition-colors" title="Edit Role">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-4h-4v4m0 0h4m0 0v-4"/>
                                                    </svg>
                                                </button>
                                                <button onclick="viewPermissions({{ $role->id }}, '{{ $role->name }}')" class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors" title="View Permissions">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                @if($role->name !== 'super_admin')
                                                <button onclick="deleteRole({{ $role->id }}, '{{ $role->name }}')" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors" title="Delete Role">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116 2.828H5.07a2 2 0 01-2.828 1.414L12 4.586A7.001 7.001 0 0010 10V17a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-8">
                            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Belum Ada Role</h3>
                        <p class="text-slate-600 max-w-md mx-auto mb-8">Belum ada role yang terdaftar dalam sistem.</p>
                        <button onclick="showCreateRoleModal()" class="group px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0 0v6m0 0v1m0-1c0 1.11.89 2 2 2h2a2 2 0 002-2v-1"/>
                                </svg>
                                Tambah Role Baru
                            </span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Role Modal -->
<div id="roleModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 p-6 rounded-t-2xl">
            <h3 class="text-xl font-bold text-white" id="modalTitle">Tambah Role Baru</h3>
        </div>
        <form id="roleForm" class="p-6">
            @csrf
            <input type="hidden" id="roleId" name="id">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Nama Role</label>
                <input type="text" id="roleName" name="name" required
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Guard</label>
                <select id="roleGuard" name="guard_name" required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="web">Web</option>
                    <option value="api">API</option>
                </select>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300">
                    Simpan
                </button>
                <button type="button" onclick="closeRoleModal()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2 px-4 rounded-lg transition-all duration-300">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Permissions Modal -->
<div id="permissionsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[80vh] overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-t-2xl">
            <h3 class="text-xl font-bold text-white">Permissions - <span id="permissionsRoleName"></span></h3>
        </div>
        <div class="p-6 overflow-y-auto max-h-[60vh]">
            <div id="permissionsList" class="space-y-2">
                <!-- Permissions will be loaded here -->
            </div>
            <div class="mt-6 flex gap-3">
                <button onclick="closePermissionsModal()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2 px-4 rounded-lg transition-all duration-300">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <div class="bg-gradient-to-r from-red-500 to-red-600 p-6 rounded-t-2xl">
            <h3 class="text-xl font-bold text-white">Konfirmasi Hapus</h3>
        </div>
        <div class="p-6">
            <p class="text-slate-700 mb-6">Apakah Anda yakin ingin menghapus role "<span id="deleteRoleName" class="font-bold"></span>"? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <button onclick="confirmDelete()" class="flex-1 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300">
                    Hapus
                </button>
                <button onclick="closeDeleteModal()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2 px-4 rounded-lg transition-all duration-300">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let deleteRoleId = null;

function showCreateRoleModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Role Baru';
    document.getElementById('roleForm').reset();
    document.getElementById('roleId').value = '';
    document.getElementById('roleModal').classList.remove('hidden');
}

function editRole(id, name, guard) {
    document.getElementById('modalTitle').textContent = 'Edit Role';
    document.getElementById('roleId').value = id;
    document.getElementById('roleName').value = name;
    document.getElementById('roleGuard').value = guard;
    document.getElementById('roleModal').classList.remove('hidden');
}

function closeRoleModal() {
    document.getElementById('roleModal').classList.add('hidden');
}

function viewPermissions(roleId, roleName) {
    document.getElementById('permissionsRoleName').textContent = roleName;
    document.getElementById('permissionsModal').classList.remove('hidden');
    
    // Load permissions via AJAX
    fetch(`/tenant/roles/${roleId}/permissions`)
        .then(response => response.json())
        .then(data => {
            const permissionsList = document.getElementById('permissionsList');
            if (data.permissions && data.permissions.length > 0) {
                permissionsList.innerHTML = data.permissions.map(permission => 
                    `<div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <span class="font-medium text-slate-700">${permission.name}</span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">${permission.guard_name}</span>
                    </div>`
                ).join('');
            } else {
                permissionsList.innerHTML = '<p class="text-slate-500 text-center">Tidak ada permissions untuk role ini.</p>';
            }
        })
        .catch(error => {
            console.error('Error loading permissions:', error);
            document.getElementById('permissionsList').innerHTML = '<p class="text-red-500 text-center">Gagal memuat permissions.</p>';
        });
}

function closePermissionsModal() {
    document.getElementById('permissionsModal').classList.add('hidden');
}

function deleteRole(id, name) {
    deleteRoleId = id;
    document.getElementById('deleteRoleName').textContent = name;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    deleteRoleId = null;
    document.getElementById('deleteModal').classList.add('hidden');
}

function confirmDelete() {
    if (deleteRoleId) {
        fetch(`/tenant/roles/${deleteRoleId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus role: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error deleting role:', error);
            alert('Gagal menghapus role. Silakan coba lagi.');
        });
    }
}

// Handle form submission
document.getElementById('roleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const roleId = document.getElementById('roleId').value;
    const url = roleId ? `/tenant/roles/${roleId}` : '/tenant/roles';
    const method = roleId ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: formData.get('name'),
            guard_name: formData.get('guard_name')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal menyimpan role: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error saving role:', error);
        alert('Gagal menyimpan role. Silakan coba lagi.');
    });
});
</script>
@endsection
