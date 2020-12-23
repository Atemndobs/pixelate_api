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
     * @var CommentResource
     */
    public CommentResource $comment;

    /**
     * @var int
     */
    public int $post_id;

    /**
     * CommentCreatedEvent constructor.
     * @param int $post_id
     * @param CommentResource $comment
     */
    public function __construct(int $post_id, CommentResource $comment)
    {
        $this->post_id = $post_id;
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('react-comment');
    }

    public function broadcastWith()
    {
        return [
            'post_id' => $this->post_id,
            'like_count' => $this->comment,
            'new_comment' => $this->comment
            /*            'new_comment' => [
                            "id" => $this->comment->id,
                            "commenter_id" => $this->comment->commenter_id,
                            "commentable_id" => $this->comment->commentable_id,
                            "comment" => $this->comment->comment,
                            'approved'=>$this->comment->approved,
                            'child_id'=>$this->comment->child_id,
                            'childComments' => $this->comment->childComments,
                            'commenter' => [
                                'name' => $this->comment->commenter->name,
                                'photo_url' => $this->comment->commenter->photo_url,
                            ],
                            "created_dates" => [
                                "created_at_human" => $this->comment->created_at->diffForHumans(),
                                "created_at" => $this->comment->created_at,
                            ],
                            "updated_dates" => [
                                "updated_at_human" => $this->comment->updated_at->diffForHumans(),
                                "updated_at" => $this->comment->updated_at,
                            ],
                        ]*/
        ];
    }
}
