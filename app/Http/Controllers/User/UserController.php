<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\LatestFirst;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    protected UserRepositoryInterface $userRepository;

    /**
     * UserController constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
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
/*        $users = $this->userRepository->withCriteria([

        ])->all();*/
       // $users = User::with('posts')->get();
       $users = User::all();
        return UserResource::collection($users);
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
        foreach ($designers as $designer){
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
}
