<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Service\UserService;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\AuthorizationException;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return UserService::isOwner($user);
        });

        /*
        TODO: Pulse gate izni. çalışmadığı için devre dışı bırakıldı.
        Gate::define('viewPulse', function (User $user) {
            return true;
        });
        */

        Gate::define('required-tfa', function ($user) {
            if(Cache::has('tfa_guard_session_model_user_' . $user->id))
                return true;

            throw AuthorizationException::requiresTFA();
        });
    }
}
