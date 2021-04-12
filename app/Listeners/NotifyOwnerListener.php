<?php

namespace App\Listeners;

use App\Events\NewLikeHasBeenAddedEvent;
use Cog\Laravel\Love\Reaction\Models\Reaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyOwnerListener
{
    /**
     * Handle the event.
     *
     * @param  NewLikeHasBeenAddedEvent  $event
     * @return void
     */
    public function handle(NewLikeHasBeenAddedEvent $event)
    {
        $reaction = Reaction::all();
        // send email with new Count status and number of likes
    }
}
