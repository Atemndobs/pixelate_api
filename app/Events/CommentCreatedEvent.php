<?php

namespace App\Events;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class CommentCreatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @var Post
     */
    public Post $post;

    /**
     * @var Comment
     */
    public Comment $comment;

    /**
     * CommentCreatedEvent constructor.
     * @param Post $post
     * @param Comment $comment
     */
    public function __construct(Post $post, Comment $comment)
    {
        $this->post= $post;
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
            'post_id' => $this->post->id,
            'comments_count' => $this->post->comments_count,
            'new_comment' => $this->comment,
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
