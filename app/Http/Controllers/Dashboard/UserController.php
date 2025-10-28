<?php

namespace App\Http\Controllers\Dashboard;

use App\Service\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Events\UserUpdate;

use App\Models\{
    User, Role, HistoryLogger, UserIpWhitelist
};

use App\Http\Resources\Dashboard\{
    HistoryResource, RoleResource, PermissionResource, UserIpAddressResource, UserResource
};

use App\Http\Requests\Dashboard\User\{
    ShowRequest, IndexRequest, StoreRequest, UpdateRequest, DestroyRequest,
    GetRoleRequest, HistoryRequest, AssignRoleRequest, RevokeRoleRequest,
    GetPermissionRequest, AssignPermissionRequest, RevokePermissionRequest, GetIpAddressRequest,
    AssignIpAddressRequest, RevokeIpAddressRequest
};

class UserController extends Controller
{
    public function index(IndexRequest $request)
    {
        $user = User::filterByFirstName($request->first_name)
            ->filterByLastName($request->last_name)
            ->filterByTitle($request->title_id)
            ->filterByEMail($request->email)
            ->filterByStatus($request->is_active)
            ->paginate($request->per_page);

        $user->load('title:id,uuid,name', 'userGroup:id,uuid,name');

        return UserResource::collection($user);
    }

    public function store(StoreRequest $request)
    {
        $user = User::create($request->payload());
        UserService::createOrUpdateUserInterfaceSetting($user, $request->settings);

        $user->load('title:id,uuid,name', 'userGroup:id,uuid,name');

        return (new User($user))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ShowRequest $request)
    {
        $user = User::uuid($request->uuid);
        $user->load('title:id,uuid,name', 'userGroup:id,uuid,name');

        return UserResource::make($user);
    }

    public function update(UpdateRequest $request)
    {
        $user = User::uuid($request->uuid);
        $user->update($request->payload());
        $user->load('title:id,uuid,name', 'userGroup:id,uuid,name');

        return UserResource::make($user);
    }

    public function destroy(DestroyRequest $request)
    {
        $user = User::uuid($request->uuid);
        $user->delete();

        return response()->noContent();
    }

    public function histories(HistoryRequest $request)
    {
        $user = User::uuid($request->uuid);
        $histories = HistoryLogger::filterByOwnerable($user->id, User::class)
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page);

        return HistoryResource::collection($histories);
    }

    public function getRoles(GetRoleRequest $request)
    {
        $user = User::uuid($request->uuid);
        $roles = $user->roles()->paginate($request->per_page);

        return RoleResource::collection($roles);
    }

    public function assignRole(AssignRoleRequest $request)
    {
        $user = User::uuid($request->uuid);
        $role = Role::uuid($request->input('role.id'));

        UserService::assignRole($user, $role);

        return (new RoleResource($role))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function revokeRole(RevokeRoleRequest $request)
    {
        $user = User::uuid($request->uuid);
        $role = Role::uuid($request->role_uuid);

        UserService::revokeRole($user, $role);

        return response()->noContent();
    }

    public function getPermission(GetPermissionRequest $request)
    {
        $user = User::uuid($request->uuid);
        $permissions = $user->getDirectPermissions(); //FIXME: paginate burada uygulanmıyor. Uygulamak mı daha doğru uygulamamak mı bakmak lazım? ->paginate($request->per_page);

        return PermissionResource::collection($permissions);
    }

    public function assignPermission(AssignPermissionRequest $request)
    {
        // TODO: burada array ile alıyoruz dataları. aslında tek tek gelmesi lazım. burası tekrar bir gözden geçirilmeli.
        $user = User::uuid($request->uuid);
        UserService::assignPermission($user, $request->permission_id);

        return response()->noContent();
    }

    public function revokePermission(RevokePermissionRequest $request)
    {
        $user = User::uuid($request->uuid);
        UserService::revokePermission($user, $request->permission_id);

        return response()->noContent();
    }

    public function getIpAddress(GetIpAddressRequest $request)
    {
        $user = User::uuid($request->uuid);
        $ipAddresses = $user->ipAddresses()->paginate($request->per_page);

        return UserIpAddressResource::collection($ipAddresses);
    }

    public function assignIpAddress(AssignIpAddressRequest $request)
    {
        $user = User::uuid($request->uuid);
        $ipAddress = UserService::assignIpAddress($user, $request->ip_address);

        return (new UserIpAddressResource($ipAddress))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function revokeIpAddress(RevokeIpAddressRequest $request)
    {
        $user = User::uuid($request->uuid);
        $ipAddress = UserIpWhitelist::uuid($request->ip_address_uuid);

        UserService::revokeIpAddress($user, $ipAddress);

        return response()->noContent();
    }
}
