<?php

namespace App\Http\Controllers;

use App\Events\ChildCommentCreatedEvent;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

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
        $comments = Comment::where('commentable_id', $this->request->comment_id )
            ->where('commentable_type', 'like', '%Comment')->get();

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
    public function create(Request $request, CommentService $commentService): \Illuminate\Http\Response
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}