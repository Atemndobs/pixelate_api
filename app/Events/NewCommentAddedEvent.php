<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Laravelista\Comments\Comment;

class NewCommentAddedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @var Comment
     */
    public Comment $comment;

    /**
     * NewCommentAddedEvent constructor.
     * @param $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {

       // return new Channel($this->comment);
           return new PrivateChannel('coin-ticker');
    }
    /*
        public function broadcastAs()
        {
            return 'incoming-comment';
        }*/

    public function broadCastWith()
    {
        return  [
            'comment' => $this->comment->comment,
            'user' => $this->comment->commenter(),
            'post'=> $this->comment->commentable()
        ];
    }
}
