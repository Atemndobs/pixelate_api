<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\LatestFirst;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    protected UserRepositoryInterface $userRepository;

    /**
     * @var Request
     */
    public Request $request;

    /**
     * UserController constructor.
     * @param UserRepositoryInterface $userRepository
     * @param Request $request
     */
    public function __construct(UserRepositoryInterface $userRepository, Request $request)
    {
        $this->userRepository = $userRepository;
        $this->request = $request;
    }


    /**
     *
     *
     * @OA\Get(
     *     path="/api/users",
     *     summary="Retrieve All Users information",
     *     description="Gets alkl users from DB  ** Reqiures Authorisation: Add Auth heather by clicking the Lock icon above",
     *     tags={"User Profile"},
     *     security={ {"token": {} }},
     *
     *      @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *            @OA\Property(property="data", type="object", ref="#/components/schemas/UserProfile")
     *             )
     *          ),
     *      @OA\Response(
     *         response=401,
     *         description="User should be authorized to get profile information",
     *         @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="Not authorized"),
     *         )
     *      )
     * )
     */
    public function index()
    {
       // $users = User::with('posts', 'followers')->get();
       // return UserResource::collection($users);

        $users = $this->userRepository->with(['posts', 'followers'])->all();

        return responder()->success($users, UserTransformer::class);
    }

    /**
     *
     *
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Retrieve All Users information",
     *     description="Gets alkl users from DB  ** Reqiures Authorisation: Add Auth heather by clicking the Lock icon above",
     *     tags={"User Profile"},
     *     security={ {"token": {} }},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="number", example=1
     *         )
     *     ),
     *
     *      @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *            @OA\Property(property="data", type="object", ref="#/components/schemas/UserProfile")
     *             )
     *          ),
     *      @OA\Response(
     *         response=401,
     *         description="User should be authorized to get profile information",
     *         @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="Not authorized"),
     *         )
     *      )
     * )
     */
    public function findUser($id)
    {
        $user= $this->userRepository->find($id);
        return new UserResource($user);
    }


    public function search(Request $request)
    {
        $designers = $this->userRepository->search($request);

        $all_results = [];
        foreach ($designers as $designer) {
            $all_results[] = $designer[0];
        }
        return response()->json(['data' => $all_results], 200);
    }

    /**
     *
     *
     * @OA\Get(
     *     path="/api/user/{username}",
     *     summary="Retrieve All Users information",
     *     description="Gets alkl users from DB  ** Reqiures Authorisation: Add Auth heather by clicking the Lock icon above",
     *     tags={"User Profile"},
     *     security={ {"token": {} }},
     *
     *     @OA\Parameter(
     *         name="username",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string", example="pierce"
     *         )
     *     ),
     *
     *      @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *            @OA\Property(property="data", type="object", ref="#/components/schemas/UserProfile")
     *             )
     *          ),
     *      @OA\Response(
     *         response=401,
     *         description="User should be authorized to get profile information",
     *         @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="Not authorized"),
     *         )
     *      )
     * )
     */
    public function findByUserName($username)
    {
        $user = $this->userRepository->findWhereFirst('username', $username);

        return new UserResource($user);
    }


    /**
     *
     * @OA\Post(
     * path="/api/user/follow/{author_id}",
     * summary="Follow",
     * description="Follow a User",
     * tags={"User"},
     * security={ {"token": {} }},
     *     @OA\Parameter(
     *         name="author_id",
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
     *       @OA\Property(property="message", type="string", example="Following"),
     *    ),
     * ),
     * @OA\Response(
     *    response=404,
     *    description="No Found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User not found"),
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
     *    response=422,
     *    description="Unprocessable Entity",
     *    @OA\JsonContent(
     *       @OA\Property(property="eror", type="string", example="You cannot follow yourself"),
     *     )
     *     )
     * )
     */
    public function follow()
    {
        $author_id = $this->request->author_id;
        $author = User::find($author_id);
        $user = \Auth::user();


        if ($user->id === (int)$author_id) {
            return \Response::json(['message' => 'You cannot follow yourself'], '422');
        }

        if ($user->isFollowing($author)) {
            $author->revokeFollower($user);
            $message = 'Unfollowed';
        } else {
            $user->follow($author);
            $message = 'Following';
        }
        $isFollowing = $user->isFollowing($author);
        $user_following_count = $user->following()->count();
        $user_follower_count = $user->followers()->count();
        $author_follower_count = $author->followers()->count();
        $author_following_count = $author->following()->count();


        return \Response::json(
            [
                'is_user_following' => $isFollowing,
                'follower_count' => $author_follower_count,
                'following_count' => $author_following_count,
                'user_following_count' => $user_following_count,
               'user_follower_count' => $user_follower_count,
               // 'user_id' => $user->id,
              //  'author_id' => $author->id
            ],
            200
        );
    }
}
