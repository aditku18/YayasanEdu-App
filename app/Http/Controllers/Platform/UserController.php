<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $search = request()->query('search');
        $role = request()->query('role');
        $status = request()->query('status');

        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->role($role);
        }

        if ($status) {
            $query->where('is_active', $status === 'active');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Calculate comprehensive statistics for the view
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', 1)->count(),
            'inactive_users' => User::where('is_active', 0)->count(),
            'today_users' => User::whereDate('created_at', today())->count(),
            'platform_admins' => User::role('platform_admin')->count(),
            'yayasan_admins' => User::role('yayasan_admin')->count(),
            'school_admins' => User::role('admin_sekolah')->count(),
            'teachers' => User::role('guru')->count(),
            'students' => User::role('siswa')->count(),
        ];

        return view('platform.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        return view('platform.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:platform_admin,yayasan_admin,admin_sekolah,guru,siswa',
            'is_active' => 'boolean',
        ]);

        // Extract role before creating user
        $role = $validated['role'];
        unset($validated['role']);

        $validated['password'] = bcrypt($validated['password']);
        $validated['is_active'] = $validated['is_active'] ?? true;

        // Create user first
        $user = User::create($validated);

        // Assign role using Spatie
        $user->assignRole($role);

        return redirect()->route('platform.users.index')
            ->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load(['schoolUnit']);
        return view('platform.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $user->load(['schoolUnit']);
        return view('platform.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:platform_admin,yayasan_admin,admin_sekolah,guru,siswa',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        }

        // Extract role before updating user
        $role = $validated['role'];
        unset($validated['role']);

        $user->update($validated);

        // Update role using Spatie
        $user->syncRoles([$role]);

        return redirect()->route('platform.users.show', $user->id)
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $name = $user->name;
        $user->delete();

        return redirect()->route('platform.users.index')
            ->with('success', "Pengguna {$name} berhasil dihapus.");
    }
}
