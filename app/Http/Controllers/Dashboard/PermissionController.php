<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Permission;
use App\Service\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\PermissionResource;
use App\Http\Requests\Dashboard\Permission\IndexRequest;

class PermissionController extends Controller
{
    public function index(IndexRequest $request)
    {
        $role = Permission::when(!UserService::isOwner(), fn ($query) => $query->whereIn('id', auth()->user()->getAllPermissions()->pluck('id')))->get();
        return PermissionResource::collection($role);
    }
}
