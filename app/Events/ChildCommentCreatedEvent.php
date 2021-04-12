<?php

namespace App\Events;

use App\Http\Resources\CommentResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChildCommentCreatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public CommentResource $comment;

    /**
     * @var string
     */
    public string $comenter_id;

    /**
     * ChildCommentCreatedEvent constructor.
     * @param CommentResource $comment
     */
    public function __construct(CommentResource $comment, string $comenter_id)
    {
        $this->comment = $comment;
        $this->comenter_id = $comenter_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('reply-channel');
    }

    public function broadcastWith()
    {
        return [
            'comment' => $this->comment,
            'commenter_id' => $this->comenter_id

        ];
    }
}
