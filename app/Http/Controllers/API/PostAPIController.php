<?php

namespace App\Http\Controllers\API;

use App\Events\NewLikeHasBeenAddedEvent;
use App\Http\Requests\API\CreatePostAPIRequest;
use App\Http\Requests\API\UpdatePostAPIRequest;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use Cog\Laravel\Love\Reaction\Events\ReactionHasBeenAdded;
use Cog\Laravel\Love\Reaction\Events\ReactionHasBeenRemoved;
use Cog\Laravel\Love\Reaction\Models\Reaction;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class PostController
 * @package App\Http\Controllers\API
 */

class PostAPIController extends AppBaseController
{
    /** @var  PostRepository */
    private $postRepository;

    public function __construct(PostRepository $postRepo)
    {
        $this->postRepository = $postRepo;
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
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $posts = $this->postRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        if ($posts->count() === 0) {
            return $this->sendResponse($posts->toArray(),'No Posts Created Yet. Please create one');
        }

        return $this->sendResponse($posts->toArray(), 'Posts retrieved successfully');
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
     *       @OA\Property(property="imageUrl", type="string", example="some image url"),
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
     * @return JsonResponse
     */
    public function store(CreatePostAPIRequest $request, $user_id)
    {
        $input = $request->all();


        $user = User::findOrFail($user_id);

        $post = $user->posts()->create($input);

        if ($request->hasFile('image')){
            // $request->image->store('public');
         //   $request->image->storeAs('/public', '')


            $post->update(
                ['imageUrl' =>$request->image->store('','public')]
            );

            $imageUrl = $post->imageUrl;


            $post->update(
                ['imageUrl' =>asset('storage/'.$imageUrl)]
            );
        }

        return $this->sendResponse($post->toArray(), 'Post saved successfully');
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
     * @return JsonResponse
     */
    public function show($id)
    {
        /** @var Post $post */
        $post = $this->postRepository->find($id);

        if ($post === null) {
            return $this->sendError('Post not found');
        }

        return $this->sendResponse($post->toArray(), 'Post retrieved successfully');
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
     * @return Response
     */
    public function update($id, UpdatePostAPIRequest $request)
    {
        $input = $request->all();

        /** @var Post $post */
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            return $this->sendError('Post not found');
        }

        $post = $this->postRepository->update($input, $id);

        return $this->sendResponse($post->toArray(), 'Post updated successfully');
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
     * @return Response
     * @throws \Exception
     */
    public function destroy(int $id): Response
    {
        /** @var Post $post */
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            return $this->sendError('Post not found');
        }
        $post->delete();

        return $this->sendSuccess('Post deleted successfully');
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
     * @param $post_id
     * @param UpdatePostAPIRequest $request
     * @return false|string
     * @throws \JsonException
     */
    public function toggleLike($post_id, Request $request)
    {




        if (!auth()->check()){
            return Response([
                'error' => 'Not logged in',
            ],401);
        }

        $user_id = $request->user_id;

        $type = $request->type;


        try {
            $reactionType = ReactionType::fromName($type);
        }catch (\Exception $exception){
            return Response([
                'error' => $exception->getMessage(),
            ],404);
        }



        try {
            $post = Post::findOrFail($post_id);

        }catch (\Exception $exception) {
            return Response([
                'message' => $exception->getMessage(),
                'error' => 'Post does not Exist ',
            ],404);
        }


        $reacter = User::findOrFail($user_id)->getLoveReacter();
        $reactant = $post->getLoveReactant();

        $likes = ReactionType::fromName('Like');
       $reactions = Reaction::all();



        if ($reactions->count() !== 0) {
            try {
                $reacter->unreactTo($reactant, $reactionType);
                try {
                    $newReaction = Reaction::all();
                    event(new NewLikeHasBeenAddedEvent($reactions));

                }catch (\Exception $exception){
                    return Response([
                        'message' => $exception->getMessage(),
                    ],404);
                }
                return Response([
                    'message' => 'Created new Unlike',

                    'Event likes'=> $reactant->getReactionCounterOfType($likes)->getCount(),
                    'totalLikes' => $newReaction->where('reaction_type_id', 1)->count(),
                    'reactionType' => 'Un'.$reactionType->getName(),
                    $reactionType->getName() => $reactant->getReactionCounterOfType($reactionType)->getCount(),
                    'rate' => $reactant->getReactions(),
                    'reactant' => $reactant,
                    'all reactions' => $reactions

                ],200);
            }catch (\Exception $exception){
                if ($exception->getCode() === 400){
                    return Response([
                        'error' => $exception->getMessage(),
                        'reactionType'=>$reactionType->getName(),
                    ],404);
                }else {

                    $reacter->reactTo($reactant, $reactionType);
                     event(new NewLikeHasBeenAddedEvent($reactant));

                    $newReaction = Reaction::all();
                    return Response([
                        'message' => $exception->getMessage()." Creating new Like",

                        'Event likes'=> $reactant->getReactionCounterOfType($likes)->getCount(),
                        'totalLikes' => $newReaction->where('reaction_type_id', 1)->count(),
                        'reactionType'=>$reactionType->getName(),
                        $reactionType->getName() => $reactant->getReactionCounterOfType($reactionType)->getCount(),
                        'rate' => $reactant->getReactions(),
                        'reacter' => $reacter,
                        'reactant' => $reactant,
                        'all reactions' => $reacter->getReactions(),
                    ],200);
                }
            }
        }

        try {
            $reacter->reactTo($reactant, $reactionType);
            event(new NewLikeHasBeenAddedEvent($reactant));

           // $newReaction = Reaction::all();
            return Response([
                "message"=> "Created new LIKE  _______",
                'Event likes'=> $reactant->getReactionCounterOfType($likes)->getCount(),
                'totalLikes' => $reactant->getReactions(),
                'reactionType'=>$reactionType->getName(),
                $reactionType->getName() => $reactant->getReactionCounterOfType($reactionType)->getCount(),
                'reacter' => $reacter,
                'reactant' => $reactant,
                'all reactions' => $reacter->getReactions(),
                'rate' => $reactant->getReactions(),
            ],200);
        }catch (\Exception $exception){
                return Response([
                    'error' => $exception->getMessage(),
                    'reactionType'=>$reactionType->getName(),
                ],404);
            }
    }
}
