<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePostAPIRequest;
use App\Http\Requests\API\UpdatePostAPIRequest;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;
use Storage;

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
     * @return Response
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
     * @return Response
     */
    public function show($id)
    {
        /** @var Post $post */
        $post = $this->postRepository->find($id);

        if (empty($post)) {
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
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Post $post */
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            return $this->sendError('Post not found');
        }

        $post->delete();

        return $this->sendSuccess('Post deleted successfully');
    }
}
