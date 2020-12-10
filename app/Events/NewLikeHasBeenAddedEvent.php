<?php

namespace App\Events;

use Cog\Contracts\Love\Reactant\Models\Reactant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewLikeHasBeenAddedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reactant;

    /**
     * NewLikeHasBeenAddedEvent constructor.
     * @param $reactant
     */
    public function __construct($reactant)
    {
        $this->reactant = $reactant;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
/*    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }*/
}
