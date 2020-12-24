<?php

namespace App\Http\Controllers;

use App\Events\ChildCommentCreatedEvent;
use App\Events\CommentReactionEvent;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Services\CommentService;
use App\Services\ReactionService;
use DB;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController extends Controller
{
    /**
     * @var Request
     */
    public Request $request;

    /**
     * @var Comment
     */
    Public Comment $comment;

    /**
     * CommentController constructor.
     * @param Request $request
     * @param Comment $comment
     */
    public function __construct(Request $request, Comment $comment)
    {
        $this->request = $request;
        $this->comment = $comment;
    }

    /**
     * Display a listing of the Comments belonging to given comment.
     * GET|HEAD comments/{comment_id}'
     *
     * @OA\Get(
     *     path="/api/comments/{comment_id}",
     *     summary="Get all Replies for a comments",
     *     description="Get all Replies for a comments",
     *     tags={"Comment"},
     *     @OA\Parameter(
     *         name="comment_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="number", example=10
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *            @OA\Property(property="data", type="object", ref="#/components/schemas/Comment")
     *             )
     *          ),
     *      @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="Not Found"),
     *         )
     *      )
     * )
     *
     * @param Request $request
     * @return Application|ResponseFactory|AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::where('id', $this->request->comment_id )
            ->where('commentable_type', 'like', '%Comment')
            ->with([
                'loveReactant.reactions.reacter.reacterable',
                'loveReactant.reactions.type',
                'loveReactant.reactionCounters',
                'loveReactant.reactionTotal',
            ])
            ->get();

        return CommentResource::collection($comments);
    }

    /**
     * @OA\Post(
     * path="/api/comments/comment/{comment_id}",
     * summary="Comment a comment / reply a comment",
     * description="Comment a comment / reply a comment",
     * tags={"Comment"},
     * security={ {"token": {} }},
     *     @OA\Parameter(
     *         name="comment_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="number", example=11
     *         )
     *     ),
     * @OA\RequestBody(
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       @OA\Property(property="comment", type="string", example="Here is the mommemt of comment"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Comment Added"),
     *    ),
     * ),
     * @OA\Response(
     *    response=404,
     *    description="No Found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Post not found"),
     *     )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Not Logged In"),
     *     )
     *     ),
     * @OA\Response(
     *    response=409,
     *    description="Conflict",
     *    @OA\JsonContent(
     *       @OA\Property(property="eror", type="string", example="Comment error"),
     *     )
     *     )
     * )
     * @param Request $request
     * @param CommentService $commentService
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, CommentService $commentService)
    {
        $commentable = Comment::find($request->comment_id);

        if ($commentable === null){
            return Response([
                'message' => 'No Comment found',
            ], 404);
        }
        $comment = $commentService->createComment($commentable, $request->comment);
        $createdComment = new CommentResource($comment);
        $parentComment = new CommentResource($commentable);
        $commenterId = $comment->commenter->id;

           // send notification to owner
        // $notification = new \App\Notifications\CommentCreatedNotification();
       // \Notification::send(auth()->user(), $notification);

        broadcast(new ChildCommentCreatedEvent($parentComment, $commenterId));
        return Response([
            'child' => $createdComment,
            'parent' => $parentComment
        ], 200);
    }



    /**
     * POST /posts
     *
     * @OA\Post(
     * path="/api/comments/comment/react/{comment_id}",
     * summary="React to Comment",
     * description="React to  a Comment",
     * tags={"Comment"},
     * security={ {"token": {} }},
     *     @OA\Parameter(
     *         name="comment_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="number", example=1
     *         )
     *     ),
     * @OA\RequestBody(
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       @OA\Property(property="post_id", type="number", example=2),
     *       @OA\Property(property="type", type="string", example="Like"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="string", example="liked"),
     *    ),
     * ),
     * @OA\Response(
     *    response=404,
     *    description="No Found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Post not found"),
     *     )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Not Logged In"),
     *     )
     *     ),
     * @OA\Response(
     *    response=409,
     *    description="Conflict",
     *    @OA\JsonContent(
     *       @OA\Property(property="eror", type="string", example="Reaction of type `Like` already exists."),
     *     )
     *     )
     * )
     * @param ReactionService $reactionService
     * @return false|string
     */
    public function reactComment(ReactionService $reactionService)
    {

        if (!auth()->check()){
            return Response([
                'error' => 'Not logged in',
            ],401);
        }
        $type = $this->request->type;
        $post_id = $this->request->post_id;

        $reactable = Comment::find($this->request->comment_id);


        $reaction = $reactionService->processReaction($type, $reactable);

        $reaction->type = $type;
        $reactedComment = new CommentResource($reaction);

       $reactions = $reaction->where('id' , $this->request->comment_id)
           ->first()->loveReactant->reactionCounters;

       $reaction_count = [];
       foreach ($reactions as $reaction ){
           $reaction_type_id = $reaction->reaction_type_id;
           $count = $reaction->count;
          // $reaction_count[] = [$reaction_type_id => $count];
           $reaction_count[] = $count;
       }

        broadcast(new CommentReactionEvent((int)$this->request->comment_id, $post_id, $reaction_count));
        return Response([
            'post_id' => $post_id,
            'reaction_type' => $reactedComment->reaction_type,
            'comment_id' => (int)$this->request->comment_id,
            'user_id' => auth()->id(),
            'reaction_count' =>$reaction_count
        ],
            200);
    }
}
