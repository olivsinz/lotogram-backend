<?php

namespace App\Providers;

use App\Models\{
    Role, User, Tag, Setting
};

use App\Observers\{
    RoleObserver, UserObserver, SettingObserver, TagObserver
};

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $observers = [
        User::class => [UserObserver::class],
        Role::class => [RoleObserver::class],
        Setting::class => [SettingObserver::class],
        Tag::class => [TagObserver::class],
    ];

    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {

    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
