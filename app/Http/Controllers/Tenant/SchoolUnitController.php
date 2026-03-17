<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\SchoolUnit;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SchoolUnitController extends Controller
{
    public function index(Request $request)
    {
        $query = SchoolUnit::withCount(['students', 'teachers']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('npsn', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $schools = $query->get();
        return view('yayasan.unit-sekolah', compact('schools'));
    }

    public function create()
    {
        return view('yayasan.unit-sekolah-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|in:TK,SD,SMP,SMA,SMK',
            'npsn' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'postal_code' => 'required|string|max:10',
            'principal_name' => 'required|string|max:255',
            'principal_email' => 'required|email',
            'principal_phone' => 'required|string',
            // Admin Account
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            // 1. Create School Unit
            $school = SchoolUnit::create([
                'name' => $validated['name'],
                'level' => $validated['level'],
                'jenjang' => $validated['level'], // mapping for compatibility
                'npsn' => $validated['npsn'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'province' => $validated['province'],
                'city' => $validated['city'],
                'district' => $validated['district'],
                'postal_code' => $validated['postal_code'],
                'principal_name' => $validated['principal_name'],
                'principal_email' => $validated['principal_email'],
                'principal_phone' => $validated['principal_phone'],
                'status' => 'draft',
            ]);

            // 2. Create School Admin Account
            $user = User::create([
                'school_unit_id' => $school->id,
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
                'role' => 'school_admin', // keep for legacy column support
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // 3. Assign Spatie Role
            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'school_admin', 'guard_name' => 'web']);
            $user->assignRole($role);

            DB::commit();

            return redirect()->route('tenant.units.index')->with('success', 'Unit sekolah dan akun admin berhasil dibuat.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(SchoolUnit $school)
    {
        $admin = User::where('school_unit_id', $school->id)->where('role', 'school_admin')->first();
        return view('yayasan.unit-sekolah-edit', compact('school', 'admin'));
    }

    /**
     * Display the school unit profile page.
     */
    public function profile(Request $request)
    {
        $user = auth()->user();

        // Get the school unit from user's school_unit_id
        $school = SchoolUnit::withCount(['students', 'teachers'])->findOrFail($user->school_unit_id);

        // Check if user has access to this school
        if ($user->school_unit_id != $school->id) {
            return abort(403, 'Anda tidak memiliki akses ke profil unit sekolah ini.');
        }

        // Get admin user for this school unit
        $admin = User::where('school_unit_id', $school->id)->where('role', 'school_admin')->first();

        // Get school slug for routing
        $schoolSlug = $school->slug;

        return view('yayasan.units.profile', compact('school', 'admin', 'schoolSlug'));
    }

    /**
     * Update the school unit profile.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        // Get the school unit from user's school_unit_id
        $school = SchoolUnit::findOrFail($user->school_unit_id);

        // Check if user has access to this school
        if ($user->school_unit_id != $school->id) {
            return abort(403, 'Anda tidak memiliki akses untuk mengubah profil unit sekolah ini.');
        }

        $admin = User::where('school_unit_id', $school->id)->where('role', 'school_admin')->first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|in:TK,SD,SMP,SMA,SMK',
            'npsn' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'postal_code' => 'required|string|max:10',
            'principal_name' => 'required|string|max:255',
            'principal_email' => 'required|email',
            'principal_phone' => 'required|string',
            // Admin Account Validation
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Update school unit
            $school->update([
                'name' => $validated['name'],
                'level' => $validated['level'],
                'npsn' => $validated['npsn'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'province' => $validated['province'],
                'city' => $validated['city'],
                'district' => $validated['district'],
                'postal_code' => $validated['postal_code'],
                'principal_name' => $validated['principal_name'],
                'principal_email' => $validated['principal_email'],
                'principal_phone' => $validated['principal_phone'],
            ]);

            // Update admin user if exists
            if ($admin) {
                $admin->update([
                    'name' => $validated['admin_name'],
                    'email' => $validated['admin_email'],
                ]);
            }

            DB::commit();
            return redirect()->route('tenant.school.profile')->with('success', 'Profil unit sekolah berhasil diperbarui.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, SchoolUnit $school)
    {
        $admin = User::where('school_unit_id', $school->id)->where('role', 'school_admin')->first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|in:TK,SD,SMP,SMA,SMK',
            'npsn' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'postal_code' => 'required|string|max:10',
            'principal_name' => 'required|string|max:255',
            'principal_email' => 'required|email',
            'principal_phone' => 'required|string',
            // Admin Account Validation
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email,' . ($admin ? $admin->id : 'NULL'),
            'admin_password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $validated['jenjang'] = $validated['level'];
            $school->update($validated);

            // Update or Create Admin Account
            if ($admin) {
                $adminData = [
                    'name' => $validated['admin_name'],
                    'email' => $validated['admin_email'],
                ];
                if ($request->filled('admin_password')) {
                    $adminData['password'] = Hash::make($validated['admin_password']);
                }
                if (!$admin->email_verified_at) {
                    $adminData['email_verified_at'] = now();
                }
                $admin->update($adminData);
            }
            else {
                $user = User::create([
                    'school_unit_id' => $school->id,
                    'name' => $validated['admin_name'],
                    'email' => $validated['admin_email'],
                    'password' => Hash::make($validated['admin_password'] ?? 'password123'), // Default or required
                    'role' => 'school_admin',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
                $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'school_admin', 'guard_name' => 'web']);
                $user->assignRole($role);
            }

            DB::commit();
            return redirect()->route('tenant.units.index')->with('success', "Data unit sekolah {$school->name} dan akun admin berhasil diperbarui.");
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function activate(SchoolUnit $school)
    {
        $school->update(['status' => 'active']);
        return back()->with('success', "Unit sekolah {$school->name} telah diaktifkan.");
    }

    public function deactivate(SchoolUnit $school)
    {
        $school->update(['status' => 'nonactive']);
        return back()->with('success', "Unit sekolah {$school->name} telah dinonaktifkan.");
    }

    /**
     * Show school status page
     */
    public function status(Request $request, $school)
    {
        $school = SchoolUnit::findOrFail($school);
        return view('yayasan.unit-sekolah-status', compact('school'));
    }

    /**
     * Show school settings page
     */
    public function settings(Request $request, $school)
    {
        $school = SchoolUnit::findOrFail($school);
        return view('yayasan.unit-sekolah-settings', compact('school'));
    }
}
