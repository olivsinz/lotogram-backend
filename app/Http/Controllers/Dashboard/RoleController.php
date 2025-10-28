<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Role;
use App\Models\Permission;
use App\Service\RoleService;
use App\Models\HistoryLogger;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\RoleResource;
use App\Http\Requests\Dashboard\Role\ShowRequest;
use App\Http\Resources\Dashboard\HistoryResource;
use App\Http\Requests\Dashboard\Role\IndexRequest;
use App\Http\Requests\Dashboard\Role\StoreRequest;
use App\Http\Requests\Dashboard\Role\UpdateRequest;
use App\Http\Requests\Dashboard\Role\DestroyRequest;
use App\Http\Requests\Dashboard\Role\HistoryRequest;
use App\Http\Resources\Dashboard\PermissionResource;
use App\Http\Requests\Dashboard\Role\GetPermissionRequest;
use App\Http\Requests\Dashboard\Role\AssignPermissionRequest;
use App\Http\Requests\Dashboard\Role\RevokePermissionRequest;

class RoleController extends Controller
{
    public function index(IndexRequest $request)
    {
        $role = Role::filterByName($request->name)
            ->filterByStatus($request->status)
            ->paginate($request->per_page);

        return RoleResource::collection($role);
    }

    public function store(StoreRequest $request)
    {
        $role = Role::create($request->validated());

        return (new RoleResource($role))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ShowRequest $request)
    {
        $role = Role::uuid($request->uuid);
        return RoleResource::make($role);
    }

    public function update(UpdateRequest $request)
    {
        $role = Role::uuid($request->uuid);
        $role->update($request->validated());

        return RoleResource::make($role);
    }

    public function destroy(DestroyRequest $request)
    {
        $role = Role::uuid($request->uuid);
        $role->delete();

        return response()->noContent();
    }

    public function getPermission(GetPermissionRequest $request)
    {
        $role = Role::uuid($request->uuid);
        $permissions = $role->permissions()->paginate($request->per_page);

        return PermissionResource::collection($permissions);
    }

    public function assignPermission(AssignPermissionRequest $request)
    {
        $role = Role::uuid($request->uuid);
        $permission = Permission::uuid($request->input('permission.id'));

        RoleService::assignPermission($role, $permission);

        return (new PermissionResource($permission))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function revokePermission(RevokePermissionRequest $request)
    {
        $role = Role::uuid($request->uuid);
        $permission = Permission::uuid($request->permission_uuid);

        RoleService::revokePermission($role, $permission);

        return response()->noContent();
    }

    public function histories(HistoryRequest $request)
    {
        $role = Role::uuid($request->uuid);
        $histories = HistoryLogger::filterByOwnerable($role->id, Role::class)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return HistoryResource::collection($histories);
    }
}
