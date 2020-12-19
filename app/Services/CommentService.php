<?php


namespace App\Services;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Laravelista\Comments\Comment;

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
     * @return Application|ResponseFactory|Response|Comment
     */
    public function createComment($commentable, $newComment)
    {
        try {
            $comment = new Comment();

            $comment->commenter()->associate($this->user);
            $comment->commentable()->associate($commentable);
            $comment->comment = $newComment;
            $comment->approved = true;
            $comment->save();
        }catch (\Exception $e){
            return Response([
                'message' => $e->getMessage(),
            ], 404);
        }

        return $comment;
    }

}
