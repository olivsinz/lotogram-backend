<?php

namespace App\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // default url değiştirilmeli
        Broadcast::routes(['middleware' => ['auth:sanctum'], 'prefix' => 'api']);
        require base_path('routes/channels.php');
    }
}
