<?php

use Illuminate\Support\Facades\Auth;
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


Broadcast::channel('friendrequest.{userid}', function ($user) {
    return ['name'=>$user['name']];
});

Broadcast::channel('chat.{userid}', function ($user) {
    return ['name'=>$user['name']];
});

Broadcast::channel('retailer', function ($user) {
    return ['id' => $user->id,'status' => $user->status];
});
