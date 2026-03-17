<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function adminYayasan()
    {
        $foundation = \App\Models\Foundation::where('tenant_id', tenant()->id)->first();
        if (!$foundation) {
            return redirect()->back()->with('error', 'Foundation not found.');
        }
        $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'yayasan_admin');
            })
            ->get();

        return view('tenant.users.admin-yayasan', compact('users'));
    }

    public function adminSekolah()
    {
        $foundation = \App\Models\Foundation::where('tenant_id', tenant()->id)->first();
        if (!$foundation) {
            return redirect()->back()->with('error', 'Foundation not found.');
        }
        $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin_sekolah');
            })
            ->get();

        return view('tenant.users.admin-sekolah', compact('users'));
    }

    public function roles()
    {
        // Roles are global in this system, not foundation-specific
        $roles = Role::all();

        return view('tenant.users.roles', compact('roles'));
    }

    public function activityLog()
    {
        $activities = ActivityLog::with('user')
            ->latest()
            ->paginate(20);
        return view('tenant.users.activity-log', compact('activities'));
    }

    // Activity Management Methods
    public function clearActivityLog(Request $request)
    {
        $request->validate([
            'days' => 'nullable|integer|min:1|max:365',
        ]);

        try {
            $days = $request->days ?? 30;
            $cutoffDate = now()->subDays($days);
            
            $deleted = ActivityLog::where('created_at', '<', $cutoffDate)->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deleted} log aktivitas yang lebih lama dari {$days} hari",
                'deleted_count' => $deleted
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membersihkan log aktivitas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportActivityLog(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:csv,xlsx'
        ]);

        try {
            $activities = ActivityLog::with('user')
                ->whereBetween('created_at', [$request->start_date, $request->end_date])
                ->latest()
                ->get();

            // For now, return JSON response. In a real implementation, 
            // you would generate CSV/Excel files here
            return response()->json([
                'success' => true,
                'message' => 'Export berhasil diproses',
                'data' => $activities->map(function($activity) {
                    return [
                        'tanggal' => $activity->created_at->format('d M Y H:i:s'),
                        'pengguna' => $activity->user ? $activity->user->name : 'System',
                        'module' => $activity->getModuleDisplayName(),
                        'aksi' => $activity->getActionDescription(),
                        'ip_address' => $activity->ip_address ?? '-'
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengekspor log aktivitas: ' . $e->getMessage()
            ], 500);
        }
    }

    // Role Management Methods
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'required|string|max:255|in:web,api',
        ]);

        try {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => $request->guard_name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil dibuat',
                'role' => $role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat role: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'guard_name' => 'required|string|max:255|in:web,api',
        ]);

        try {
            $role = Role::findOrFail($id);
            $role->update([
                'name' => $request->name,
                'guard_name' => $request->guard_name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil diperbarui',
                'role' => $role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui role: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteRole($id)
    {
        try {
            $role = Role::findOrFail($id);
            
            // Prevent deletion of super_admin role
            if ($role->name === 'super_admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Role super_admin tidak dapat dihapus'
                ], 403);
            }

            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus role: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRolePermissions($id)
    {
        try {
            $role = Role::findOrFail($id);
            $permissions = $role->permissions;

            return response()->json([
                'success' => true,
                'permissions' => $permissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat permissions: ' . $e->getMessage()
            ], 500);
        }
    }
}
