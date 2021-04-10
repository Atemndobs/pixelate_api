<?php

namespace App\Events;

use App\Http\Resources\LikeResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LikeCreatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Post
     */
    public Post $post;

    /**
     * LikeCreatedEvent constructor.
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("like-channel-post");
    }

    public function broadcastWith()
    {
        $reacter = auth()->user();
        return [
            'post' =>[
                'id' => $this->post->id,
                'likes' => (new LikeResource($this->post)),
                'reacter' => [
                    'id' => $this->post->reacter_id,
                    'name' => $reacter->name,
                    'avatar' => $reacter->avatar,
                    'reacter' => $reacter
                ],
                'author' => $this->post->user->id,
                'position' => $this->post['position'],
                'is_liked' => $this->post->getLoveReactant()->isReactedBy($reacter->getLoveReacter())

            ]
        ];
    }
}
