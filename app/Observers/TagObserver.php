<?php

namespace App\Observers;

use App\Models\Tag;
use App\Service\LoggerService;

class TagObserver
{
    /**
     * Handle the Tag "created" event.
     */
    public function created(Tag $tag): void
    {
        LoggerService::History('created', $tag);
    }

    /**
     * Handle the Tag "updated" event.
     */
    public function updated(Tag $tag): void
    {
        LoggerService::History('updated', $tag);
    }

    /**
     * Handle the Tag "deleted" event.
     */
    public function deleted(Tag $tag): void
    {
        LoggerService::History('deleted', $tag);
    }

    /**
     * Handle the Tag "restored" event.
     */
    public function restored(Tag $tag): void
    {
        LoggerService::History('restored', $tag);
    }

    /**
     * Handle the Tag "force deleted" event.
     */
    public function forceDeleted(Tag $tag): void
    {
        LoggerService::History('forceDeleted', $tag);
    }
}
