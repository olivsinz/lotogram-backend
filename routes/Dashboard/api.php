<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\TagController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\CompetitionController;
use App\Http\Controllers\Dashboard\MethodController;
use App\Http\Controllers\Dashboard\PlannedCompetitionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum', 'verify.user'])->group(function ()
{
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{uuid}', [UserController::class, 'show']);
        Route::put('/{uuid}', [UserController::class, 'update']);
        Route::delete('/{uuid}', [UserController::class, 'destroy']);

        Route::get('/{uuid}/roles', [UserController::class, 'getRoles']);
        Route::post('/{uuid}/roles', [UserController::class, 'assignRole']);
        Route::delete('/{uuid}/roles/{role_uuid}', [UserController::class, 'revokeRole']);

        Route::get('/{uuid}/permissions', [UserController::class, 'getPermission']);
        Route::put('/{uuid}/permissions', [UserController::class, 'assignPermission']);
        Route::delete('/{uuid}/permissions', [UserController::class, 'revokePermission']);

        Route::get('/{uuid}/ip_addresses', [UserController::class, 'getIpAddress']);
        Route::post('/{uuid}/ip_addresses', [UserController::class, 'assignIpAddress']);
        Route::delete('/{uuid}/ip_addresses/{ip_address_uuid}', [UserController::class, 'revokeIpAddress']);

        Route::get('/{uuid}/histories', [UserController::class, 'histories']);
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('/{uuid}', [RoleController::class, 'show']);
        Route::put('/{uuid}', [RoleController::class, 'update']);
        Route::delete('/{uuid}', [RoleController::class, 'destroy']);
        Route::get('/{uuid}/histories', [RoleController::class, 'histories']);

        Route::get('/{uuid}/permissions', [RoleController::class, 'getPermission']);
        Route::post('/{uuid}/permissions', [RoleController::class, 'assignPermission']);
        Route::delete('/{uuid}/permissions/{permission_uuid}', [RoleController::class, 'revokePermission']);
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index']);
    });

    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'index']);
        Route::put('/{uuid}', [SettingController::class, 'update']);
        Route::get('/{uuid}/histories', [SettingController::class, 'histories']);
    });

    Route::prefix('tags')->group(function () {
        Route::get('/', [TagController::class, 'index']);
        Route::post('/', [TagController::class, 'store']);
        Route::get('/{uuid}', [TagController::class, 'show']);
        Route::put('/{uuid}', [TagController::class, 'update']);
        Route::delete('/{uuid}', [TagController::class, 'destroy']);
        Route::get('/{uuid}/histories', [TagController::class, 'histories']);
    });

    Route::prefix('planned-competitions')->group(function () {
        Route::get('/', [PlannedCompetitionController::class, 'index']);
        Route::post('/', [PlannedCompetitionController::class, 'store']);
        Route::get('/{uuid}', [PlannedCompetitionController::class, 'show']);
        Route::put('/{uuid}', [PlannedCompetitionController::class, 'update']);

        Route::get('/{uuid}/rewards', [PlannedCompetitionController::class, 'getReward']);
        Route::post('/{uuid}/rewards', [PlannedCompetitionController::class, 'assignReward']);
        Route::put('/{uuid}/rewards/{reward_uuid}', [PlannedCompetitionController::class, 'editReward']);
        Route::delete('/{uuid}/rewards/{reward_uuid}', [PlannedCompetitionController::class, 'revokeReward']);
    });

    Route::prefix('competitions')->group(function () {
        Route::get('/', [CompetitionController::class, 'index']);
        Route::get('/{uuid}', [CompetitionController::class, 'show']);
        Route::put('/{uuid}', [CompetitionController::class, 'update']);
    });

    Route::prefix('methods')->group(function () {
        Route::get('/', [MethodController::class, 'index']);
        Route::post('/', [MethodController::class, 'store']);
        Route::get('/{uuid}', [MethodController::class, 'show']);
        Route::put('/{uuid}', [MethodController::class, 'update']);
    });

});
