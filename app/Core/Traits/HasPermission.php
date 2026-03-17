<?php

namespace App\Core\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Trait HasPermission
 * 
 * Provides helper methods for checking permissions and roles.
 * Use this trait in the User model or any model that needs permission checks.
 */
trait HasPermission
{
    /**
     * Check if user has a specific permission.
     *
     * @param string $permission
     * @param bool $requireAll
     * @return bool
     */
    public function hasPermissionTo(string $permission, bool $requireAll = false): bool
    {
        if (!$this->is_active) {
            return false;
        }

        return parent::hasPermissionTo($permission, $requireAll);
    }

    /**
     * Check if user has any of the given permissions.
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->can($permission)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if user has all of the given permissions.
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->can($permission)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Check if user has permission for a specific module.
     *
     * @param string $module
     * @param string $action
     * @return bool
     */
    public function hasModulePermission(string $module, string $action = 'view'): bool
    {
        return $this->can("{$module}.{$action}");
    }

    /**
     * Check if user has permission based on tenant/school.
     *
     * @param string $permission
     * @param int|null $schoolId
     * @return bool
     */
    public function hasSchoolPermission(string $permission, ?int $schoolId = null): bool
    {
        // Platform admins can access everything
        if ($this->hasRole(['super_admin', 'platform_admin'])) {
            return true;
        }

        // Check base permission
        if (!$this->can($permission)) {
            return false;
        }

        // For school-specific roles, check school assignment
        $userSchoolId = $schoolId ?? $this->school_unit_id;
        
        if (!$userSchoolId && $this->hasRole(['kepala_sekolah', 'admin_sekolah', 'guru'])) {
            return false;
        }

        return true;
    }

    /**
     * Get all permissions grouped by module.
     *
     * @return array
     */
    public function getPermissionsByModule(): array
    {
        $permissions = $this->getAllPermissions();
        $grouped = [];

        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->name);
            $module = $parts[0] ?? 'other';
            $action = $parts[1] ?? 'view';
            
            if (!isset($grouped[$module])) {
                $grouped[$module] = [];
            }
            
            $grouped[$module][] = [
                'name' => $permission->name,
                'description' => $permission->description ?? $permission->name,
                'action' => $action,
            ];
        }

        return $grouped;
    }

    /**
     * Get permissions for a specific module.
     *
     * @param string $module
     * @return array
     */
    public function getModulePermissions(string $module): array
    {
        $permissions = $this->getAllPermissions();
        $modulePermissions = [];

        foreach ($permissions as $permission) {
            if (str_starts_with($permission->name, "{$module}.")) {
                $modulePermissions[] = $permission;
            }
        }

        return $modulePermissions;
    }

    /**
     * Check if user has access to a specific module.
     *
     * @param string $module
     * @return bool
     */
    public function hasAccessToModule(string $module): bool
    {
        $permissions = $this->getAllPermissions();
        
        foreach ($permissions as $permission) {
            if (str_starts_with($permission->name, "{$module}.")) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get all modules the user has access to.
     *
     * @return array
     */
    public function getAccessibleModules(): array
    {
        $permissions = $this->getAllPermissions();
        $modules = [];

        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->name);
            $module = $parts[0] ?? 'other';
            
            if (!in_array($module, $modules)) {
                $modules[] = $module;
            }
        }

        return $modules;
    }

    /**
     * Check if user can access specific school data.
     *
     * @param int $schoolId
     * @return bool
     */
    public function canAccessSchool(int $schoolId): bool
    {
        // Platform admins can access all schools
        if ($this->hasRole(['super_admin', 'platform_admin'])) {
            return true;
        }

        // Foundation admins can access their foundation's schools
        if ($this->hasRole(['yayasan_admin', 'yayasan_operator'])) {
            return true;
        }

        // School-level users can only access their assigned school
        return $this->school_unit_id === $schoolId;
    }

    /**
     * Get role level (higher = more access).
     *
     * @return int
     */
    public function getRoleLevel(): int
    {
        $roles = $this->roles;
        
        if ($roles->isEmpty()) {
            return 0;
        }

        return $roles->max('level') ?? 0;
    }

    /**
     * Check if user has higher or equal level than given level.
     *
     * @param int $level
     * @return bool
     */
    public function hasRoleLevel(int $level): bool
    {
        return $this->getRoleLevel() >= $level;
    }

    /**
     * Scope to filter by permission.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $permission
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPermission($query, string $permission)
    {
        return $query->whereHas('permissions', function ($q) use ($permission) {
            $q->where('name', $permission);
        });
    }

    /**
     * Scope to filter by role.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRole($query, string $role)
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }

    /**
     * Scope to filter active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get display name for the user.
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?? $this->email;
    }

    /**
     * Get avatar URL.
     *
     * @return string
     */
    public function getAvatarUrlAttribute(): string
    {
        // If user has avatar, return it
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }

        // Generate initials avatar
        $initials = strtoupper(substr($this->name ?? 'U', 0, 2));
        
        return "https://ui-avatars.com/api/?name={$initials}&background=random&color=fff";
    }
}
