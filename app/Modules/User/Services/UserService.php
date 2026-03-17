<?php

namespace App\Modules\User\Services;

use App\Core\Base\BaseService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
 * User Service - Business logic for User module
 * 
 * Extends BaseService to provide CRUD operations
 * with additional user-specific methods.
 */
class UserService extends BaseService
{
    /**
     * Create a new service instance.
     */
    public function __construct()
    {
        $this->setModel(new User());
    }

    /**
     * Create a new user with role.
     *
     * @param array $data
     * @return User
     */
    public function createWithRole(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $role = $data['role'] ?? null;
            unset($data['role']);

            // Hash password if provided
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user = $this->create($data);

            // Assign role if provided
            if ($role) {
                $user->assignRole($role);
            }

            return $user;
        });
    }

    /**
     * Update user with role.
     *
     * @param int|string $id
     * @param array $data
     * @return User
     */
    public function updateWithRole($id, array $data): User
    {
        return DB::transaction(function () use ($id, $data) {
            $role = $data['role'] ?? null;
            unset($data['role']);

            // Hash password if provided and not empty
            if (isset($data['password']) && !empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user = $this->update($id, $data);

            // Update role if provided
            if ($role) {
                $user->syncRoles([$role]);
            }

            return $user;
        });
    }

    /**
     * Get users by role.
     *
     * @param string $roleName
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByRole(string $roleName)
    {
        return $this->model->role($roleName)->get();
    }

    /**
     * Get users by school unit.
     *
     * @param int $schoolUnitId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBySchoolUnit(int $schoolUnitId)
    {
        return $this->query()->where('school_unit_id', $schoolUnitId)->get();
    }

    /**
     * Get active users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveUsers()
    {
        return $this->query()->where('is_active', true)->get();
    }

    /**
     * Search users by name or email.
     *
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(string $search)
    {
        return $this->query()
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->get();
    }

    /**
     * Activate user.
     *
     * @param int|string $id
     * @return User
     */
    public function activate($id): User
    {
        $user = $this->findOrFail($id);
        $user->update(['is_active' => true]);
        
        return $user->fresh();
    }

    /**
     * Deactivate user.
     *
     * @param int|string $id
     * @return User
     */
    public function deactivate($id): User
    {
        $user = $this->findOrFail($id);
        $user->update(['is_active' => false]);
        
        return $user->fresh();
    }

    /**
     * Change user password.
     *
     * @param int|string $id
     * @param string $newPassword
     * @return User
     */
    public function changePassword($id, string $newPassword): User
    {
        $user = $this->findOrFail($id);
        $user->update(['password' => Hash::make($newPassword)]);
        
        return $user->fresh();
    }

    /**
     * Get paginated users with role filter.
     *
     * @param string|null $role
     * @param int|null $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginateWithRole(?string $role = null, ?int $perPage = null)
    {
        $query = $this->query();

        if ($role) {
            $query->role($role);
        }

        return $query->paginate($perPage ?? $this->perPage);
    }
}
