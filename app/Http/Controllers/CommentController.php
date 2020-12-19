<?php

namespace App\Http\Controllers;

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
     * Display a listing of the Post.
     * GET|HEAD /comments
     *
     * @OA\Get(
     *     path="/api/comments",
     *     summary="Get all Comments",
     *     description="Get all Posts available online (set to live )",
     *     tags={"Comment"},
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
        $comments = Comment::all();
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
     *       @OA\Property(property="user_id", type="number", example=21),
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
        // send notification to owner
        // send event to dom
        return Response($createdComment, 200);
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
