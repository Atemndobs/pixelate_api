<?php


namespace App\Services;

use App\Models\Comment;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Eloquent\PostRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class CommentService
{
    private $user;

    /**
     * CommentService constructor.
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }

    /**
     * @param $commentable
     * @param $newComment
     * @return Comment|Application|ResponseFactory|Response
     */
    public function createComment($commentable, PostRepositoryInterface $postRepository)
    {

        $newComment = request()->comment;

        try {
            $comment = new Comment();
            $comment->commenter()->associate($this->user);
            $comment->commentable()->associate($commentable);
            $comment->comment = $newComment;
            $comment->approved = true;
            $comment->save();
        } catch (\Exception $e) {
            return Response([
                'message' => $e->getMessage(),
            ], 404);
        }

        $postRepository->update([
            'latest_comment' => request()->comment,
        ], $commentable->id);

        return $comment;
    }

    public function replyComment($commentable, string $newComment)
    {
        try {
            $reply = new Comment();
            $reply->commenter()->associate($this->user);
            $reply->commentable()->associate($commentable);
            $reply->parent()->associate($commentable);
            $reply->comment = $newComment;
            $reply->approved = true;
            $reply->save();
        } catch (\Exception $e) {
            return Response([
                'message' => $e->getMessage(),
            ], 404);
        }

        return $reply;
    }
}
