<?php

namespace App\Observers;

use App\Models\Setting;
use App\Service\LoggerService;

class SettingObserver
{
    /**
     * Handle the Setting "created" event.
     */
    public function created(Setting $setting): void
    {
        LoggerService::History('created', $setting);
    }

    /**
     * Handle the Setting "updated" event.
     */
    public function updated(Setting $setting): void
    {
        LoggerService::History('updated', $setting);
    }

    /**
     * Handle the Setting "deleted" event.
     */
    public function deleted(Setting $setting): void
    {
        LoggerService::History('deleted', $setting);
    }

    /**
     * Handle the Setting "restored" event.
     */
    public function restored(Setting $setting): void
    {
        LoggerService::History('restored', $setting);
    }

    /**
     * Handle the Setting "force deleted" event.
     */
    public function forceDeleted(Setting $setting): void
    {
        LoggerService::History('forceDeleted', $setting);
    }
}
