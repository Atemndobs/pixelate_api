<?php

namespace App\Events;

use App\Http\Resources\PostResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostCreatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var PostResource
     */
    public PostResource $postResource;

    /**
     * PostCreatedEvent constructor.
     * @param PostResource $postResource
     */
    public function __construct(PostResource $postResource)
    {
        $this->postResource = $postResource;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('post-channel');
    }

    public function broadcastWith()
    {
        return [
            'post' => $this->postResource
            ];

    }
}
