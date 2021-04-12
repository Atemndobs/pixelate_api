<?php

namespace App\Events;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentReactionEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @var int
     */
    public  $comment_id;

    /**
     * @var int
     */
    public int $post_id;

    /**
     * @var array
     */
    public $reaction_count;

    /**
     * CommentReactionEvent constructor.
     * @param int $comment_id
     * @param int $post_id
     * @param array $reaction_count
     */
    public function __construct( int $comment_id, int $post_id, array $reaction_count)
    {
        $this->comment_id = $comment_id;
        $this->post_id = $post_id;
        $this->reaction_count = $reaction_count;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('react-channel');
    }

    public function broadcastWith()
    {
        return [
            'post_id' => $this->post_id,
            'comment_id' => $this->comment_id,
            'reacter_id' => auth()->id(),
            'reaction_count' => $this->reaction_count
        ];
    }
}
