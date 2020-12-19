<?php

namespace App\Events;

use App\Http\Resources\LikeResource;
use App\Http\Resources\PostResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LikeCreatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @var PostResource
     */
    public PostResource $postResource;

    /**
     * LikeCreatedEvent constructor.
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
        return new Channel('like-channel');
    }

    public function broadcastWith()
    {
        return [
            'post' =>[
                'id' => $this->postResource->id,
                'likes' => (new LikeResource($this->postResource)),
                'reacter' => $this->postResource->reacter->id,
            ]
        ];
    }
}
