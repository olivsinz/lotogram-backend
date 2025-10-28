<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('competition', function ($user) {
    return true;
});

Broadcast::channel('user.{userId}', function (User $user, $userId   ) {
    return (int) $user->uuid === (int) $userId;
});
