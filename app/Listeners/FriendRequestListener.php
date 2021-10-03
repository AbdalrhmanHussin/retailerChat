<?php

namespace App\Listeners;

use App\Events\FriendRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FriendRequestListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FriendRequest  $event
     * @return void
     */
    public function handle(FriendRequest $event)
    {
        //
    }
}
