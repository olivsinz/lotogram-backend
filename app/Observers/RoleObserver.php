<?php

namespace App\Observers;

use App\Models\Role;
use App\Service\LoggerService;

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     */
    public function created(Role $role): void
    {
        LoggerService::History('created', $role);
    }

    /**
     * Handle the Role "updated" event.
     */
    public function updated(Role $role): void
    {
        LoggerService::History('updated', $role);
    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        LoggerService::History('deleted', $role);
    }

    /**
     * Handle the Role "restored" event.
     */
    public function restored(Role $role): void
    {
        LoggerService::History('restored', $role);
    }

    /**
     * Handle the Role "force deleted" event.
     */
    public function forceDeleted(Role $role): void
    {
        LoggerService::History('forceDeleted', $role);
    }
}
