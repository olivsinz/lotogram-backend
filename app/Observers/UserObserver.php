<?php

namespace App\Observers;

use App\Models\User;
use App\Service\LoggerService;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        LoggerService::History('created', $user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        LoggerService::History('updated', $user);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        LoggerService::History('deleted', $user);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        LoggerService::History('restored', $user);
    }

    /**
     * Handle the User "forceDeleted" event.
     */
    public function forceDeleted(User $user): void
    {
        LoggerService::History('forceDeleted', $user);
    }
}
