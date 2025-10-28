<?php

namespace App\Service;

use App\Models\Permission;
use App\Models\Role;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleService
{
    public static function addPermission(Role $role, array $permissions): void
    {
        $permissions = Permission::whereIn('uuid', $permissions)->get();
        $role->syncPermissions($permissions);
    }

    public static function assignPermission(Role $role, Permission $permission)
    {
        $isExists = $role->permissions()->where('id', $permission->id)->exists();

        if ($isExists) {
            return throw new ConflictHttpException;
        }

        return $role->givePermissionTo($permission);
    }

    public static function revokePermission(Role $role, Permission $permission)
    {
        $isExists = $role->permissions()->where('id', $permission->id)->exists();

        if (!$isExists) {
            return throw new NotFoundHttpException;
        }

        return $role->revokePermissionTo($permission);
    }
}
