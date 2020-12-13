<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePostAPIRequest;
use App\Http\Requests\API\UpdatePostAPIRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\LikeResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Laravelista\Comments\Comment;
use Laravelista\Comments\Commenter;
use Response;

/**
 * Class PostController
 * @package App\Http\Controllers\API
 */

class PostAPIController extends AppBaseController
{
    /** @var  PostRepository */
    private PostRepository $postRepository;

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
     * @return Application|ResponseFactory|AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
/*        $posts = $this->postRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );*/

        $posts = $this->postRepository->all();

        if ($posts->count() === 0) {
          //  return $this->sendResponse($posts->toArray(),'No Posts Created Yet. Please create one');
            return Response([
                'message' => 'No Posts Created Yet. Please create one',
            ], 404);
        }

       // return $this->sendResponse($posts->toArray(), 'Posts retrieved successfully');
        return PostResource::collection($posts);
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
     * @return PostResource
     */
    public function store(CreatePostAPIRequest $request, $user_id)
    {
        $input = $request->all();

        $user = User::findOrFail($user_id);

        $post = $user->posts()->create($input);

        if ($request->hasFile('image')){
            // $request->image->store('public');
            // $request->image->storeAs('/public', '')


            $post->update(
                ['imageUrl' =>$request->image->store('','public')]
            );

            $imageUrl = $post->imageUrl;


            $post->update(
                ['imageUrl' =>asset('storage/'.$imageUrl)]
            );


        }

       // return $this->sendResponse($post->toArray(), 'Post saved successfully');
        return new PostResource($post);
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
    public function show($id)
    {
        /** @var Post $post */
        $post = $this->postRepository->find($id);

        if ($post === null) {
            return Response([
                'message' => 'No Posts found',
            ], 404);

        }

      //  return $this->sendResponse($post->toArray(), 'Post retrieved successfully');
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
    public function update($id, UpdatePostAPIRequest $request)
    {
        $input = $request->all();

        /** @var Post $post */
        $post = $this->postRepository->find($id);

        if ($post === null) {
            return Response([
                'message' => 'No Posts found',
            ], 404);
        }

        $post = $this->postRepository->update($input, $id);

      //  return $this->sendResponse($post->toArray(), 'Post updated successfully');

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
    public function destroy(int $id)
    {
        /** @var Post $post */
        $post = $this->postRepository->find($id);

        if ($post === null) {
            return Response([
                'message' => 'No Posts found',
            ], 404);
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
     * @param Request $request
     * @return false|string
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

        return $this->processReaction($user_id, $post_id, $type);
    }

    /**
     * @param $user_id
     * @param $post_id
     * @param $type
     * @return PostResource|Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function processReaction($user_id, $post_id, $type)
    {
        try {
            $reactionType = ReactionType::fromName($type);
        } catch (\Exception $exception) {
            return Response([
                'error' => $exception->getMessage(),
            ], 404);
        }

/*        try {
           // $post = Post::findOrFail($post_id);
            $post = $this->postRepository->find($post_id);

        } catch (\Exception $exception) {
            return Response([
                'message' => $exception->getMessage(),
                'error' => 'Post does not Exist ',
            ], 404);
        }*/

        $post = $this->postRepository->find($post_id);
        $reacter = User::findOrFail($user_id)->getLoveReacter();
        $reactant = $post->getLoveReactant();
        $reactantId = $post->getLoveReactant()->getId();

        $reactionTypeId = $reactionType->getId();
        $existing_reaction = $reacter->getReactions()
            ->where('reactant_id',$reactantId)->all();

        $existing_like = $reacter->getReactions()
            ->where('reactant_id',$reactantId )
            ->where('reaction_type_id',1)
            ->first();

        $existing_disLike = $reacter->getReactions()
            ->where('reactant_id',$reactantId )
            ->where('reaction_type_id',2)
            ->first();

        $like = ReactionType::fromName('Like');
        $dislike = ReactionType::fromName('Dislike');
        $reaction_type = '';
        if (!empty($existing_reaction) ){
            if (!empty($existing_like))
            {
                if ((int)$reactionTypeId ===2) {
                   // echo 'LIKE EXISTS but want to Dislike';
                    $reacter->unreactTo($reactant, $like);
                    $reacter->reactTo($reactant, $reactionType);
                    $reaction_type = 'Dislike';
                } else{
                   // echo 'LIKE EXISTS so Unlike';
                    $reacter->unreactTo($reactant, $reactionType);
                    $reaction_type = 'unLike';
                }
            }

            if (!empty($existing_disLike))
            {
                if ((int)$reactionTypeId ===1) {
                  //  echo 'DISLIKE EXISTS But want to Like';
                    $reacter->unreactTo($reactant, $dislike);
                    $reacter->reactTo($reactant, $reactionType);
                    $reaction_type = 'Like';
                } else{
                  //  echo 'DISLIKE EXISTS so unDislike';
                    $reacter->unreactTo($reactant, $dislike);
                    $reaction_type = 'unDislike';
                }
            }


            }else{
              // echo('never liked or disliked before so REACT');
                $reacter->reactTo($reactant, $reactionType);
            $reaction_type = $reactionType->getName();
            }

        $reactedPost = new PostResource($post);
        $reactedPost['reaction_type'] = $reaction_type;
        $reactedPost['likes'] =  new LikeResource($post);

        return $reactedPost;
    }

    /**
    /**
     * POST /posts
     *
     * @OA\Post(
     * path="/api/posts/comment/{post_id}",
     * summary="Comment Post",
     * description="Comment a Post",
     * tags={"Post"},

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
     */
    public function addComment(Request $request, $post_id)
    {
        $post = $this->postRepository->find($post_id);

        $user = User::find($request->user_id);

        $comment = new Comment();
        $comment->commenter()->associate($user);
        $comment->commentable()->associate($post);
        $comment->comment = $request->comment;
        $comment->approved = true;
        $comment->save();

        $commentedPost = new PostResource($post);
       // $reactedPost['new_comment'] = 'codmdd';


        return $commentedPost;
    }
}
