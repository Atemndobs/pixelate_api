<?php

namespace App\Http\Controllers\API;

use App\Events\CommentCreatedEvent;
use App\Events\Hallo;
use App\Events\LikeCreatedEvent;
use App\Events\NewCommentAddedEvent;
use App\Events\PostCreatedEvent;
use App\Http\Requests\API\CreatePostAPIRequest;
use App\Http\Requests\API\UpdatePostAPIRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\LikeResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\PostRepository;
use App\Services\CommentService;
use App\Services\ReactionService;
use Cog\Laravel\Love\Reaction\Events\ReactionHasBeenAdded;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
//use Laravelista\Comments\Comment;
use Laravelista\Comments\Commenter;
use Laravelista\Comments\Events\CommentCreated;
use Response;

/**
 * Class PostController
 * @package App\Http\Controllers\API
 */

class PostAPIController extends AppBaseController
{
    /** @var  PostRepository */
    private PostRepository $postRepository;

    private Request $request;

    /**
     * PostAPIController constructor.
     * @param PostRepository $postRepository
     * @param Request $request
     */
    public function __construct(PostRepository $postRepository, Request $request)
    {
        $this->postRepository = $postRepository;
        $this->request = $request;
    }


    /**
     * Display a listing of the Post.
     * GET|HEAD /posts
     *
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Get all Posts",
     *     description="Get all Posts available online (set to live )",
     *     tags={"Post"},
     *      @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *            @OA\Property(property="data", type="object", ref="#/components/schemas/Post")
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
/*        $posts = $this->postRepository->withCriteria([
            new EagerLoad(['user', 'comments'])
        ])->all();*/
        $posts = Post::with('comments')->get();

/*        $comments = Comment::query()
            ->with([
                'loveReactant.reactions.reacter.reacterable',
                'loveReactant.reactions.type',
                'loveReactant.reactionCounters',
                'loveReactant.reactionTotal',
            ])
            ->get();*/

        if ($posts->count() === 0) {
            return Response([
                'message' => 'No Posts Created Yet. Please create one',
            ], 404);
        }
        $postResource = PostResource::collection($posts);

        return $postResource;
    }

    /**
     * Store a newly created Post in storage.
     * POST /posts
     *
     * @OA\Post(
     * path="/api/posts/{user_id}",
     * summary="Create Post",
     * description="Create A Post",
     * security={ {"token": {} }},
     * tags={"Post"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="number", example=1
     *         )
     *     ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Post data",
     *    @OA\JsonContent(
     *       @OA\Property(property="caption", type="string", example="Here"),
     *       @OA\Property(property="imageUrl", type="string", example="https://lorempixel.com/640/480/?86115"),
     *       @OA\Property(property="location", type="string", example="Dusseldorf"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="string", example="false"),
     *       @OA\Property(property="message", type="string", example="Post not found"),
     *     )
     *     )
     * )
     *
     * @param CreatePostAPIRequest $request
     *
     * @return PostResource
     */
    public function store(CreatePostAPIRequest $request, $user_id)
    {
        $input = $request->all();

        $user = User::findOrFail($user_id);

        $post = $user->posts()->create($input);

        if ($request->hasFile('image')){

            $post->update(
                ['imageUrl' =>$request->image->store('','public')]
            );

            $imageUrl = $post->imageUrl;


            $post->update(
                ['imageUrl' =>asset('storage/'.$imageUrl)]
            );


        }

        $createdPost = new PostResource($post);
        broadcast(new PostCreatedEvent($createdPost));

        return $createdPost;
    }

    /**
     * Display the specified Post.
     * GET|HEAD /posts/{id}
     *
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     summary="Get Post by Id",
     *     description="Get Single Post",
     *     tags={"Post"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="number", example=1
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *            @OA\Property(property="data", type="object", ref="#/components/schemas/Post")
     *             )
     *          ),
     *      @OA\Response(
     *         response=404,
     *         description="Not found",
     *           @OA\JsonContent(
     *              @OA\Property(property="success", type="string", example="false"),
     *              @OA\Property(property="message", type="string", example="Post not found"),
     *           )
     *      )
     * )
     *
     * @param int $id
     *
     * @return PostResource|Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function show()
    {
       $post = Post::with('comments')
            ->find($this->request->id);


        if ($post === null) {
            return Response([
                'message' => 'No Posts found',
            ], 404);

        }

        return new PostResource($post);
    }

    /**
     * Update the specified Post in storage.
     * PUT/PATCH /posts/{id}
     *
     * @OA\Put(
     * path="/api/posts/{id}",
     * summary="Update Post",
     * description="Update A Post by Id",
     * tags={"Post"},
     * security={ {"token": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="number", example=1
     *         )
     *     ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Post data",
     *    @OA\JsonContent(
     *       @OA\Property(property="caption", type="string", example="Here"),
     *       @OA\Property(property="location", type="string", example="Dusseldorf"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="string", example="true"),
     *       @OA\Property(property="message", type="string", example="Post deleted successfully"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="string", example="false"),
     *       @OA\Property(property="message", type="string", example="Post not found"),
     *     )
     *     )
     * )
     *
     * @param int $id
     * @param UpdatePostAPIRequest $request
     *
     * @return PostResource|Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function update(UpdatePostAPIRequest $request)
    {
        $input = $request->all();

        /** @var Post $post */
        $post = $this->postRepository->find($request->id);

        if ($post === null) {
            return Response([
                'message' => 'No Posts found',
            ], 404);
        }

        $post = $this->postRepository->update($request->id, $input);

        return new PostResource($post);
    }

    /**
     * Remove the specified Post from storage.
     * DELETE /posts/{id}
     *
     * @OA\Delete (
     * path="/api/posts/{id}",
     * summary="Delete Post",
     * description="Delete A Post by Id",
     * tags={"Post"},
     * security={ {"token": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="number", example=1
     *         )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="string", example="true"),
     *       @OA\Property(property="message", type="string", example="Post deleted successfully"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="string", example="false"),
     *       @OA\Property(property="message", type="string", example="Post not found"),
     *     )
     *     )
     * )
     *
     *
     * @param int $id
     *
     * @return Application|ResponseFactory|\Illuminate\Http\Response|Response
     * @throws \Exception
     */
    public function destroy()
    {
        /** @var Post $post */
        $post = $this->postRepository->find($this->request->id);

        if ($post === null) {
            return Response([
                'message' => 'No Posts found',
            ], 404);
        }
        $post->delete();

        return Response([
            'message' => 'Post deleted successfully',
        ], 200);
    }

    /**
     * POST /posts
     *
     * @OA\Post(
     * path="/api/posts/like/{post_id}",
     * summary="Like Post",
     * description="Like a Post",
     * tags={"Post"},
     * security={ {"token": {} }},
     *     @OA\Parameter(
     *         name="post_id",
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
    public function toggleLike(ReactionService $reactionService)
    {

        if (!auth()->check()){
            return Response([
                'error' => 'Not logged in',
            ],401);
        }


        $type = $this->request->type;

        $post = $this->postRepository->find($this->request->post_id);

        try {
            $reaction = $reactionService->processReaction($type, $post);
        }catch (\Exception $exception){
            return Response('REACTION NOT FOUND' . $type, 404);
        }


        $reaction->type = $type;
        $reactedPost = new PostResource($reaction);

        broadcast(new LikeCreatedEvent($reactedPost))->toOthers();

        return Response($reactedPost, 200);
    }

    /**
     * /**
     * POST /posts
     *
     * @OA\Post(
     * path="/api/posts/comment/{post_id}",
     * summary="Comment Post",
     * description="Comment a Post",
     * tags={"Post"},
     * security={ {"token": {} }},
     *     @OA\Parameter(
     *         name="post_id",
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
     * @param Request $request
     * @param $post_id
     * @param CommentService $commentService
     * @return PostResource
     */
    public function addComment(CommentService $commentService)
    {
        $post = $this->postRepository->find($this->request->post_id);
        $comment = $commentService->createComment($post, $this->request->comment);

        $newComment = new CommentResource($comment);
        $postResource = new PostResource($post);
        $postResource->new_comment = $newComment;

        broadcast(new CommentCreatedEvent($postResource, $newComment))->toOthers();
     //   $notification = new \App\Notifications\CommentCreatedNotification();
      //  \Notification::send(auth()->user(), $notification);

         return $postResource;
    }
}
