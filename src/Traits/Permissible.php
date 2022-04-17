<?php

namespace Shahnewaz\Permissible\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Shahnewaz\Permissible\Role;

trait Permissible
{

    /**
     * @return Bool checks if user has certain role
     * */
    public function hasRole($role)
    {
        $roles = $this->roles()->pluck('code')->toArray();
        return in_array($role, $roles);
    }

    /**
     * User has many roles
     * @return BelongsToMany
     * */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @return  Boolean whether a user can do certain activity
     * */
    public function hasPermission($permission, $arguments = [])
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return  array list of permissions a user has
     * */
    public function getPermissionsAttribute()
    {
        $permissions = [];
        foreach ($this->roles as $role) {
            $rolePermissions = [];
            foreach ($role->permissions as $permission) {
                $rolePermissions[] = $permission->type . '.' . $permission->name;
            }
            $permissions = array_merge($permissions, $rolePermissions);
        }
        return $permissions;
    }
}