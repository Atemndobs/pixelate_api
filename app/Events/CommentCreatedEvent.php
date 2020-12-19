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
use Laravelista\Comments\Comment;

class CommentCreatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @var PostResource
     */
    public PostResource $postResource;

    /**
     * @var Comment
     */
    public Comment $comment;

    /**
     * CommentCreatedEvent constructor.
     * @param PostResource $postResource
     * @param Comment $comment
     */
    public function __construct(PostResource $postResource, Comment $comment)
    {
        $this->postResource = $postResource;
        $this->comment = $comment;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        //return new Channel('comment.'.$this->postResource->id.$this->comment->id);
        return new Channel('comment-channel');
    }

    public function broadcastWith()
    {
        return [
            'post_id' => $this->postResource->id,
            'comments_count' => $this->postResource->comments->count(),
            'new_comment' => [
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
                'created_at' => $this->comment->created_at
               // 'new' => $this->comment
            ]
        ];
    }
}
