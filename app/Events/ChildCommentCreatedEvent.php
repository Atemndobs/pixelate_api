<?php

namespace App\Events;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChildCommentCreatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public CommentResource $comment;

    /**
     * ChildCommentCreatedEvent constructor.
     * @param CommentResource $comment
     */
    public function __construct(CommentResource $comment)
    {
        $this->comment = $comment;
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
           // 'comments_count' => $this->postResource->comments->count(),
/*            'new_comment' => [
                "id" => $this->comment->id,
                "commenter_id" => $this->comment->commenter_id,
                "commentable_id" => $this->comment->commentable_id,
                "comment" => $this->comment->comment,
                'approved'=>$this->comment->approved,
                'child_id'=>$this->comment->child_id,
                'commenter' => [
                    'name' => $this->comment->commenter->name,
                    'photo_url' => $this->comment->commenter->photo_url,
                ],
                'created_at' => $this->comment->created_at*/
                // 'new' => $this->comment
            //]
        ];
    }
}
