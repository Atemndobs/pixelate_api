<?php

namespace App\Events;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class CommentCreatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var PostResource
     */
    public PostResource $postResource;

    /**
     * @var CommentResource
     */
    public CommentResource $comment;

    /**
     * CommentCreatedEvent constructor.
     * @param PostResource $postResource
     * @param CommentResource $comment
     */
    public function __construct(PostResource $postResource, CommentResource $comment)
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
